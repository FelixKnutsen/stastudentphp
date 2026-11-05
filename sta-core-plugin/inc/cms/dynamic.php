<?php
/**
 * Dynamic Elementor sections ("loops") without Elementor Pro.
 *
 * A container in Elementor marked with the CSS classes «sta-loop sta-src-<source>» is repeated for
 * every entry from wp-admin: its first child is the template (a card), and it is cloned once per
 * entry. Clones keep the template's element IDs, so they get exactly the template's Elementor
 * styling – the design is still edited in Elementor, the content in wp-admin.
 *
 * Inside a template:
 *   {{field}}            in any widget text or link is replaced with the entry's value
 *   sta-if-<field>       (CSS class) removes the element when the field is empty
 *   sta-ifnot-<field>    removes the element when the field has a value
 *   sta-img-<field>      sets an Image widget to the entry's image
 *   sta-tpl-<variant>    an alternative template child, used for entries of that variant
 * On the loop container:
 *   sta-arg-<value>      argument for the source (e.g. the group slug)
 *   sta-limit-<n>        show at most n entries
 * Any container marked «sta-hide-empty» is removed when all loops inside it are empty.
 * A Text editor widget marked «sta-html sta-src-doclist sta-arg-<category>» gets a generated
 * document list (year headings + links).
 *
 * Runs only on the public site (not in the Elementor editor, which shows the template itself).
 */

if (!defined('ABSPATH')) {
    exit;
}

add_filter('elementor/frontend/builder_content_data', 'sta_dyn_filter_content', 10, 2);

function sta_dyn_filter_content($data, $post_id) {
    if (!is_array($data) || !$data) {
        return $data;
    }
    if (class_exists('\Elementor\Plugin')) {
        $el = \Elementor\Plugin::$instance;
        if ((isset($el->editor) && $el->editor->is_edit_mode()) || (isset($el->preview) && $el->preview->is_preview_mode())) {
            return $data;
        }
    }
    $json = wp_json_encode($data);
    if (strpos($json, 'sta-loop') === false && strpos($json, 'sta-html') === false && strpos($json, '{{') === false) {
        return $data;
    }
    // Elementor's element cache stores one rendering per page and reuses it for every language
    // and every later change, so skip it while this page is printed.
    $GLOBALS['sta_dyn_rendering'] = true;
    return sta_dyn_walk_list($data, array());
}

add_filter('pre_option_elementor_element_cache_ttl', function ($value) {
    return empty($GLOBALS['sta_dyn_rendering']) ? $value : 'disable';
});

add_filter('elementor/frontend/the_content', function ($content) {
    unset($GLOBALS['sta_dyn_rendering']);
    return $content;
});

function sta_dyn_classes($el) {
    $s = $el['settings'] ?? array();
    $c = trim(($s['css_classes'] ?? '') . ' ' . ($s['_css_classes'] ?? ''));
    return $c === '' ? array() : preg_split('/\s+/', $c);
}

function sta_dyn_class_args($classes, $prefix) {
    $out = array();
    foreach ($classes as $c) {
        if (strpos($c, $prefix) === 0) {
            $out[] = substr($c, strlen($prefix));
        }
    }
    return $out;
}

function sta_dyn_walk_list($elements, $ctx) {
    $out = array();
    foreach ((array) $elements as $el) {
        $r = sta_dyn_walk_el($el, $ctx);
        if ($r !== null) {
            $out[] = $r;
        }
    }
    return $out;
}

function sta_dyn_walk_el($el, $ctx) {
    if (!is_array($el)) {
        return $el;
    }
    $classes = sta_dyn_classes($el);
    foreach (sta_dyn_class_args($classes, 'sta-if-') as $field) {
        if (sta_dyn_empty(sta_dyn_lookup($field, $ctx))) {
            return null;
        }
    }
    foreach (sta_dyn_class_args($classes, 'sta-ifnot-') as $field) {
        if (!sta_dyn_empty(sta_dyn_lookup($field, $ctx))) {
            return null;
        }
    }
    if (in_array('sta-loop', $classes, true)) {
        return sta_dyn_loop($el, $classes, $ctx);
    }
    if (in_array('sta-html', $classes, true)) {
        $src = sta_dyn_class_args($classes, 'sta-src-')[0] ?? '';
        $arg = sta_dyn_class_args($classes, 'sta-arg-')[0] ?? '';
        $el['settings']['editor'] = sta_dyn_html($src, $arg, sta_dyn_class_args($classes, 'sta-opt-'), $ctx);
    }
    if (!empty($el['settings'])) {
        $el['settings'] = sta_dyn_subst($el['settings'], $ctx);
    }
    foreach (sta_dyn_class_args($classes, 'sta-img-') as $field) {
        $img = sta_dyn_lookup($field, $ctx);
        if (is_array($img) && (!empty($img['id']) || !empty($img['url']))) {
            $el['settings']['image'] = array_merge((array) ($el['settings']['image'] ?? array()), array(
                'id'     => (int) ($img['id'] ?? 0),
                'url'    => (string) ($img['url'] ?? ''),
                'alt'    => (string) ($img['alt'] ?? ''),
                'source' => 'library',
            ));
        }
    }
    if (!empty($el['elements'])) {
        $el['elements'] = sta_dyn_walk_list($el['elements'], $ctx);
    }
    if (in_array('sta-hide-empty', $classes, true) && !sta_dyn_contains_loop($el['elements'] ?? array())) {
        return null;
    }
    return $el;
}

function sta_dyn_loop($el, $classes, $ctx) {
    $src   = sta_dyn_class_args($classes, 'sta-src-')[0] ?? '';
    $arg   = sta_dyn_class_args($classes, 'sta-arg-')[0] ?? '';
    $limit = (int) (sta_dyn_class_args($classes, 'sta-limit-')[0] ?? 0);
    $items = sta_dyn_items($src, $arg, $ctx);
    if ($limit > 0) {
        $items = array_slice($items, 0, $limit);
    }
    if (!$items || empty($el['elements'])) {
        return null;
    }
    $default  = null;
    $variants = array();
    foreach ($el['elements'] as $child) {
        $variant = sta_dyn_class_args(sta_dyn_classes($child), 'sta-tpl-')[0] ?? '';
        if ($variant !== '') {
            $variants[$variant] = $child;
        } elseif ($default === null) {
            $default = $child;
        }
    }
    $default = $default ?? reset($el['elements']);
    $children = array();
    foreach ($items as $item) {
        $tpl = $variants[$item['_variant'] ?? ''] ?? $default;
        $r   = sta_dyn_walk_el($tpl, array_merge(array($item), $ctx));
        if ($r !== null) {
            $children[] = $r;
        }
    }
    if (!$children) {
        return null;
    }
    $el['elements'] = $children;
    $el['settings'] = sta_dyn_subst($el['settings'] ?? array(), $ctx);
    return $el;
}

function sta_dyn_contains_loop($elements) {
    foreach ((array) $elements as $el) {
        if (in_array('sta-loop', sta_dyn_classes($el), true) || sta_dyn_contains_loop($el['elements'] ?? array())) {
            return true;
        }
    }
    return false;
}

function sta_dyn_empty($v) {
    if (is_array($v)) {
        return empty($v['id']) && empty($v['url']) && !array_filter($v);
    }
    return trim((string) $v) === '' || $v === '0';
}

function sta_dyn_lookup($field, $ctx) {
    foreach ($ctx as $frame) {
        if (array_key_exists($field, $frame)) {
            return $frame[$field];
        }
    }
    $globals = sta_dyn_globals();
    return $globals[$field] ?? '';
}

/**
 * Replaces {{field}} in all settings. Values are escaped for HTML (widgets print their settings
 * as HTML); fields ending in «_html» are already sanitised HTML; link URLs are inserted as URLs.
 */
function sta_dyn_subst($settings, $ctx, $key = '') {
    if (is_array($settings)) {
        foreach ($settings as $k => $v) {
            $settings[$k] = sta_dyn_subst($v, $ctx, (string) $k);
        }
        return $settings;
    }
    if (!is_string($settings) || strpos($settings, '{{') === false) {
        return $settings;
    }
    return preg_replace_callback('/\{\{\s*([a-z0-9_]+)\s*\}\}/', function ($m) use ($ctx, $key) {
        $value = sta_dyn_lookup($m[1], $ctx);
        if (is_array($value)) {
            $value = $value['url'] ?? '';
        }
        $value = (string) $value;
        if ($key === 'url') {
            return esc_url_raw($value, array('http', 'https', 'mailto', 'tel'));
        }
        if (substr($m[1], -5) === '_html') {
            return $value;
        }
        return esc_html($value);
    }, $settings);
}

/* ------------------------------------------------------------------ Language helpers */

function sta_dyn_lang() {
    return function_exists('sta_get_current_language') ? sta_get_current_language() : 'no';
}

function sta_dyn_t($no, $en, $nn = null) {
    $lang = sta_dyn_lang();
    if ($lang === 'en') {
        return $en;
    }
    if ($lang === 'nn' && $nn !== null) {
        return $nn;
    }
    return $no;
}

/** Norwegian value, or the English field on English pages when it is filled in. */
function sta_dyn_meta($post_id, $base) {
    if (sta_dyn_lang() === 'en') {
        $en = trim((string) get_post_meta($post_id, $base . '_en', true));
        if ($en !== '') {
            return $en;
        }
    }
    return trim((string) get_post_meta($post_id, $base . '_no', true));
}

function sta_dyn_title($post) {
    if (sta_dyn_lang() === 'en') {
        $en = trim((string) get_post_meta($post->ID, '_sta_title_en', true));
        if ($en !== '') {
            return $en;
        }
    }
    return get_the_title($post);
}

function sta_dyn_term_name($term) {
    if (sta_dyn_lang() === 'en') {
        $en = sta_cms_term_name_en($term);
        if ($en !== '') {
            return $en;
        }
    }
    return $term->name;
}

function sta_dyn_globals() {
    static $g = null;
    if ($g === null) {
        $c = sta_cms_contact_info();
        $g = array(
            'site_email'      => $c['email'] ?? 'sta@uia.no',
            'site_phone'      => $c['phone'] ?? '',
            'site_org_number' => $c['org_number'] ?? '',
            'site_address_krs'  => $c['address_krs'] ?? '',
            'site_address_grim' => $c['address_grim'] ?? '',
        );
    }
    return $g;
}

function sta_dyn_posts($type, $args = array()) {
    return get_posts(array_merge(array(
        'post_type'        => $type,
        'post_status'      => 'publish',
        'posts_per_page'   => -1,
        'orderby'          => array('menu_order' => 'ASC', 'title' => 'ASC'),
        'suppress_filters' => false,
    ), $args));
}

function sta_dyn_image($post_id, $fallback_meta = '') {
    $id = (int) get_post_thumbnail_id($post_id);
    if ($id) {
        return array('id' => $id, 'url' => (string) wp_get_attachment_image_url($id, 'full'), 'alt' => get_the_title($post_id));
    }
    $url = $fallback_meta ? (string) get_post_meta($post_id, $fallback_meta, true) : '';
    return $url ? array('id' => attachment_url_to_postid($url), 'url' => $url, 'alt' => get_the_title($post_id)) : '';
}

/** Plain text with blank lines between paragraphs → <p> elements. */
function sta_dyn_paragraphs($text) {
    $parts = array_filter(array_map('trim', preg_split('/\R\s*\R/u', (string) $text)), 'strlen');
    return implode("\n", array_map(function ($p) {
        return '<p>' . nl2br(esc_html($p)) . '</p>';
    }, $parts));
}

/** Image value from a stored URL (media library ID when the URL belongs to an attachment). */
function sta_dyn_image_url($url, $alt = '') {
    return $url === '' ? '' : array('id' => (int) attachment_url_to_postid($url), 'url' => $url, 'alt' => $alt);
}

function sta_dyn_is_internal($url) {
    $host = wp_parse_url($url, PHP_URL_HOST);
    return !$host || strtolower($host) === strtolower((string) wp_parse_url(home_url(), PHP_URL_HOST));
}

/* ------------------------------------------------------------------ Sources */

function sta_dyn_items($src, $arg, $ctx) {
    switch ($src) {
        case 'group':
            $group = get_page_by_path($arg, OBJECT, 'sta_group');
            return ($group && $group->post_status === 'publish') ? sta_dyn_people((array) get_post_meta($group->ID, '_sta_members', true)) : array();
        case 'members':
            $rows = sta_dyn_lookup('_members', $ctx);
            return is_array($rows) ? sta_dyn_people($rows) : array();
        case 'faculties':
            return sta_dyn_faculties($arg);
        case 'reps':
            return sta_dyn_reps($arg, $ctx);
        case 'committee-groups':
            return sta_dyn_term_items('sta_committee_group', 'sta_position', 'group');
        case 'positions':
            return sta_dyn_positions(sta_dyn_term_id('sta_committee_group', $arg, $ctx));
        case 'vacancy-categories':
            return sta_dyn_term_items('sta_vacancy_category', 'sta_vacancy', 'category');
        case 'vacancies':
            return sta_dyn_vacancies(sta_dyn_term_id('sta_vacancy_category', $arg, $ctx));
        case 'documents':
            return sta_dyn_documents($arg !== '' ? $arg : (string) sta_dyn_lookup('_cat', $ctx), sta_dyn_lookup('_year', $ctx));
        case 'document-years':
            return sta_dyn_document_years($arg);
        case 'partners':
            return array_map(function ($p) {
                $url = (string) get_post_meta($p->ID, '_sta_website', true);
                return array('name' => sta_dyn_title($p), 'logo' => sta_dyn_image_url((string) get_post_meta($p->ID, '_sta_logo_url', true), get_the_title($p)), 'url' => $url);
            }, sta_dyn_posts('sta_partner'));
    }
    return array();
}

function sta_dyn_term_id($taxonomy, $arg, $ctx) {
    if ($arg !== '') {
        $term = get_term_by('slug', $arg, $taxonomy);
        return $term ? (int) $term->term_id : -1;
    }
    return (int) sta_dyn_lookup('_term_id', $ctx);
}

/** People in a group or committee, in the group's order. */
function sta_dyn_people($rows) {
    $items = array();
    $en    = sta_dyn_lang() === 'en';
    foreach ($rows as $row) {
        $person = get_post((int) ($row['person'] ?? 0));
        if (!$person || $person->post_type !== 'sta_board_member' || $person->post_status !== 'publish') {
            continue;
        }
        $row_no = trim((string) ($row['role_no'] ?? ''));
        $row_en = trim((string) ($row['role_en'] ?? ''));
        $p_no   = trim((string) get_post_meta($person->ID, '_sta_role_no', true));
        $p_en   = trim((string) get_post_meta($person->ID, '_sta_role_en', true));
        $role   = $en ? ($row_en ?: ($row_no === '' ? $p_en : '') ?: $row_no ?: $p_no) : ($row_no ?: $p_no);
        $resp   = array_filter(array_map('trim', preg_split('/\r?\n/', sta_dyn_meta($person->ID, '_sta_responsibilities'))));
        $email  = sanitize_email(get_post_meta($person->ID, '_sta_email', true));
        $phone  = trim((string) get_post_meta($person->ID, '_sta_phone', true));
        $items[] = array(
            'name'                => get_the_title($person),
            'role'                => $role,
            'responsibilities'    => implode("\n", $resp),
            'responsibilities_html' => $resp ? '<ul>' . implode('', array_map(function ($l) { return '<li>' . esc_html($l) . '</li>'; }, $resp)) . '</ul>' : '',
            'email'               => $email,
            'email_link'          => $email ? 'mailto:' . $email : '',
            'phone'               => $phone,
            'phone_link'          => $phone ? 'tel:' . preg_replace('/[^0-9+]/', '', $phone) : '',
            'image'               => sta_dyn_image($person->ID, '_sta_image_url'),
            '_post_id'            => $person->ID,
        );
    }
    return $items;
}

function sta_dyn_current_period() {
    $id = (int) get_option('sta_current_sta_period');
    if ($id && get_term($id, 'sta_period') instanceof WP_Term) {
        return $id;
    }
    $terms = get_terms(array('taxonomy' => 'sta_period', 'hide_empty' => false, 'orderby' => 'name', 'order' => 'DESC', 'number' => 1));
    return (!is_wp_error($terms) && $terms) ? (int) $terms[0]->term_id : 0;
}

function sta_dyn_parliament_reps($period_id) {
    static $cache = array();
    if (!isset($cache[$period_id])) {
        $cache[$period_id] = $period_id ? sta_dyn_posts('sta_parliament_rep', array(
            'tax_query' => array(array('taxonomy' => 'sta_period', 'field' => 'term_id', 'terms' => $period_id)),
        )) : array();
    }
    return $cache[$period_id];
}

function sta_dyn_faculties($period_slug) {
    $period_id = $period_slug !== '' ? (int) (get_term_by('slug', $period_slug, 'sta_period')->term_id ?? 0) : sta_dyn_current_period();
    $reps      = sta_dyn_parliament_reps($period_id);
    $items     = array();
    foreach (sta_cms_ordered_terms('sta_faculty') as $term) {
        $mine = array_filter($reps, function ($r) use ($term) {
            return has_term($term->term_id, 'sta_faculty', $r);
        });
        if (!$mine) {
            continue;
        }
        $fast = count(array_filter($mine, function ($r) { return get_post_meta($r->ID, '_sta_rep_type', true) !== 'substitute'; }));
        $vara = count($mine) - $fast;
        $items[] = array(
            'faculty'     => sta_dyn_term_name($term),
            'count_fast'  => (string) $fast,
            'count_vara'  => (string) $vara,
            'label_fast'  => sprintf(sta_dyn_t('Faste representanter (%d)', 'Permanent representatives (%d)', 'Faste representantar (%d)'), $fast),
            'label_vara'  => sprintf(sta_dyn_t('Vararepresentanter (%d)', 'Deputy representatives (%d)', 'Vararepresentantar (%d)'), $vara),
            '_faculty_id' => (int) $term->term_id,
            '_period_id'  => $period_id,
        );
    }
    return $items;
}

function sta_dyn_ordinal_en($n) {
    $n = (int) $n;
    $suffix = 'th';
    if ($n % 100 < 11 || $n % 100 > 13) {
        $suffix = array(1 => 'st', 2 => 'nd', 3 => 'rd')[$n % 10] ?? 'th';
    }
    return $n . $suffix;
}

function sta_dyn_reps($kind, $ctx) {
    $faculty = (int) sta_dyn_lookup('_faculty_id', $ctx);
    $reps    = array_values(array_filter(sta_dyn_parliament_reps((int) sta_dyn_lookup('_period_id', $ctx)), function ($r) use ($faculty, $kind) {
        $is_vara = get_post_meta($r->ID, '_sta_rep_type', true) === 'substitute';
        return has_term($faculty, 'sta_faculty', $r) && ($kind === 'vara' ? $is_vara : !$is_vara);
    }));
    if ($kind === 'vara') {
        usort($reps, function ($a, $b) {
            $na = (int) get_post_meta($a->ID, '_sta_vara_number', true);
            $nb = (int) get_post_meta($b->ID, '_sta_vara_number', true);
            return $na === $nb ? $a->menu_order - $b->menu_order : $na - $nb;
        });
    }
    $vacant_label = sta_dyn_t('Ledig verv – kontakt sta@uia.no', 'Vacant position – contact sta@uia.no');
    $items = array();
    foreach ($reps as $r) {
        $vacant = get_post_meta($r->ID, '_sta_is_vacant', true) === '1';
        $name   = get_the_title($r);
        $num    = (int) get_post_meta($r->ID, '_sta_vara_number', true);
        if ($vacant) {
            $label = $vacant_label;
        } elseif ($kind === 'vara' && $num > 0) {
            $label = sta_dyn_lang() === 'en' ? sta_dyn_ordinal_en($num) . ' deputy: ' . $name : $num . '. vara: ' . $name;
        } else {
            $label = $name;
        }
        $items[] = array('name' => $vacant ? '' : $name, 'label' => $label, 'vacant' => $vacant ? '1' : '', '_variant' => $vacant ? 'vacant' : '');
    }
    return $items;
}

/** One item per term (with at least one published entry), in «Rekkefølge» order. */
function sta_dyn_term_items($taxonomy, $post_type, $field) {
    $items = array();
    foreach (sta_cms_ordered_terms($taxonomy) as $term) {
        if (!sta_dyn_posts($post_type, array('fields' => 'ids', 'tax_query' => array(array('taxonomy' => $taxonomy, 'field' => 'term_id', 'terms' => $term->term_id))))) {
            continue;
        }
        $items[] = array($field => sta_dyn_term_name($term), '_term_id' => (int) $term->term_id);
    }
    return $items;
}

function sta_dyn_positions($term_id) {
    if ($term_id <= 0) {
        return array();
    }
    $items = array();
    foreach (sta_dyn_posts('sta_position', array('tax_query' => array(array('taxonomy' => 'sta_committee_group', 'field' => 'term_id', 'terms' => $term_id)))) as $p) {
        $long = sta_dyn_meta($p->ID, '_sta_long');
        $url  = (string) get_post_meta($p->ID, '_sta_link_url', true);
        $items[] = array(
            'title'      => sta_dyn_title($p),
            'short'      => sta_dyn_meta($p->ID, '_sta_desc'),
            'short_html' => sta_dyn_paragraphs(sta_dyn_meta($p->ID, '_sta_desc')),
            'long'       => wp_strip_all_tags($long),
            // Text from the wp-admin editor has no <p> tags (WordPress adds them); text moved from
            // Elementor already has them and must be left as it is.
            'long_html'  => $long === '' ? '' : (stripos($long, '<p') === false ? wpautop(wp_kses_post($long)) : wp_kses_post($long)),
            'link_url'   => $url,
            'link_label' => sta_dyn_meta($p->ID, '_sta_link_label') ?: ($url ? sta_dyn_t('Les mer →', 'Read more →', 'Les meir →') : ''),
            '_members'   => (array) get_post_meta($p->ID, '_sta_members', true),
            '_post_id'   => $p->ID,
        );
    }
    return $items;
}

function sta_dyn_vacancies($term_id) {
    if ($term_id <= 0) {
        return array();
    }
    $items = array();
    foreach (sta_dyn_posts('sta_vacancy', array('tax_query' => array(array('taxonomy' => 'sta_vacancy_category', 'field' => 'term_id', 'terms' => $term_id)))) as $p) {
        $url   = (string) get_post_meta($p->ID, '_sta_link_url', true);
        $label = sta_dyn_meta($p->ID, '_sta_link_label');
        $internal = $url !== '' && sta_dyn_is_internal($url);
        $items[] = array(
            'title'         => sta_dyn_title($p),
            'seats'         => sta_dyn_meta($p->ID, '_sta_seats'),
            'organ'         => sta_dyn_meta($p->ID, '_sta_organ'),
            'eligibility'   => sta_dyn_meta($p->ID, '_sta_eligibility'),
            'link_url'      => $url,
            'link_label'    => $label ?: $url,
            'link_internal' => $internal ? '1' : '',
            'link_external' => ($url !== '' && !$internal) ? '1' : '',
            '_post_id'      => $p->ID,
        );
    }
    return $items;
}

function sta_dyn_document_posts($category) {
    static $cache = array();
    if (!isset($cache[$category])) {
        $posts = sta_dyn_posts('sta_document', array(
            'tax_query' => array(array('taxonomy' => 'sta_document_category', 'field' => 'slug', 'terms' => $category)),
        ));
        // «Rekkefølge» first (used for the fixed cards on Dokumenter), then newest date first.
        usort($posts, function ($a, $b) {
            if ($a->menu_order !== $b->menu_order) {
                return $a->menu_order - $b->menu_order;
            }
            $da = (string) get_post_meta($a->ID, '_sta_doc_date', true);
            $db = (string) get_post_meta($b->ID, '_sta_doc_date', true);
            return $da !== $db ? strcmp($db, $da) : strcasecmp($a->post_title, $b->post_title);
        });
        $cache[$category] = $posts;
    }
    return $cache[$category];
}

function sta_dyn_documents($category, $year = '') {
    $items = array();
    foreach (sta_dyn_document_posts($category) as $p) {
        $date = (string) get_post_meta($p->ID, '_sta_doc_date', true);
        if ($year !== '' && $year !== null && substr($date, 0, 4) !== (string) $year) {
            continue;
        }
        $url    = (string) get_post_meta($p->ID, '_sta_file_url', true);
        $ext    = strtoupper((string) pathinfo((string) wp_parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
        $title  = sta_dyn_title($p);
        $items[] = array(
            'title'          => $title,
            'title_format'   => $ext ? $title . ' (' . $ext . ')' : $title,
            'desc'           => sta_dyn_meta($p->ID, '_sta_desc'),
            'note'           => sta_dyn_meta($p->ID, '_sta_note'),
            'url'            => $url,
            'format'         => $ext,
            'download_label' => $ext ? sprintf(sta_dyn_t('Last ned (%s)', 'Download (%s)'), $ext) : sta_dyn_t('Last ned', 'Download'),
            'date'           => $date,
            '_post_id'       => $p->ID,
        );
    }
    return $items;
}

function sta_dyn_document_years($category) {
    $years = array();
    foreach (sta_dyn_document_posts($category) as $p) {
        $y = substr((string) get_post_meta($p->ID, '_sta_doc_date', true), 0, 4);
        if ($y !== '' && !isset($years[$y])) {
            $years[$y] = array('year' => $y, '_year' => $y, '_cat' => $category);
        }
    }
    krsort($years);
    return array_values($years);
}

/** Generated HTML for Text editor widgets marked «sta-html». */
function sta_dyn_html($src, $arg, $opts, $ctx) {
    if ($src !== 'doclist') {
        return '';
    }
    $html = '';
    foreach (sta_dyn_document_years($arg) as $year) {
        $html .= in_array('strongyear', $opts, true)
            ? '<p><strong>' . esc_html($year['year']) . '</strong></p>' . "\n"
            : '<h2>' . esc_html($year['year']) . '</h2>' . "\n";
        foreach (sta_dyn_documents($arg, $year['year']) as $doc) {
            $note  = $doc['note'] !== '' ? ' ' . esc_html($doc['note']) : '';
            $html .= $doc['url'] !== ''
                ? '<p><a href="' . esc_url($doc['url']) . '">' . esc_html($doc['title']) . '</a>' . $note . '</p>' . "\n"
                : '<p><em>' . esc_html($doc['title']) . '</em>' . $note . '</p>' . "\n";
        }
    }
    return $html;
}
