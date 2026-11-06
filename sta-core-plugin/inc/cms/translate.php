<?php
/**
 * Translation layer for the whole site.
 *
 * Text reaches visitors in other languages in two ways, and both stay as they are:
 * - Fields on an entry (`_en` / `_nn` meta), written in the language tabs of the edit form.
 * - Text on pages, looked up by its exact Bokmål wording: English in the TranslatePress tables,
 *   Nynorsk in the theme's dictionary (option sta_nn_translations).
 *
 * This file adds what was missing: a language bar on every edit form, status per language, one
 * «Oversettelser» screen for page text, and carrying a translation over when a text is edited in
 * Elementor, so a small edit no longer disconnects the text from its translation. Translations
 * are only ever added or updated here; nothing is deleted.
 */

if (!defined('ABSPATH')) {
    exit;
}

const STA_TR_LANGS = array('nn' => 'Nynorsk', 'en' => 'English');

/** News older than this keeps its one-language form and is not counted as missing a translation. */
const STA_TR_NEWS_FROM = '2026-11-06';

/* ------------------------------------------------------------------ Stores */

/** Lookup key: entities decoded, whitespace collapsed (same as the Nynorsk dictionary). */
function sta_tr_key($text) {
    return trim(preg_replace('/\s+/u', ' ', html_entity_decode((string) $text, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
}

/** Bokmål text => translation for a language. Normalised keys; loaded once per request. */
function sta_tr_map($lang, $fresh = false) {
    static $maps = array();
    if ($fresh) {
        unset($maps[$lang]);
    }
    if (isset($maps[$lang])) {
        return $maps[$lang];
    }
    $map = array();
    if ($lang === 'nn') {
        foreach ((array) get_option('sta_nn_translations', array()) as $key => $value) {
            if ((string) $value !== '') {
                $map[sta_tr_key($key)] = (string) $value;
            }
        }
    } elseif ($lang === 'en') {
        foreach (sta_tr_en_rows() as $row) {
            if ((string) $row['translated'] !== '' && (int) $row['status'] > 0) {
                $map[sta_tr_key($row['original'])] = html_entity_decode($row['translated'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
        }
    }
    $maps[$lang] = $map;
    return $map;
}

function sta_tr_en_query() {
    if (!class_exists('TRP_Translate_Press') || !function_exists('sta_trp_english_code') || !sta_trp_english_code()) {
        return null;
    }
    return TRP_Translate_Press::get_trp_instance()->get_component('query');
}

function sta_tr_en_rows() {
    global $wpdb;
    $query = sta_tr_en_query();
    if (!$query) {
        return array();
    }
    $table = $query->get_table_name(sta_trp_english_code());
    // The table name comes from TranslatePress, not from input.
    // Only translated rows: the table also holds thousands of untranslated strings.
    return (array) $wpdb->get_results("SELECT original, translated, status FROM `{$table}` WHERE status > 0 AND translated <> ''", ARRAY_A); // phpcs:ignore WordPress.DB
}

/** Save page-text translations: array(Bokmål text => translation). An empty value clears it. */
function sta_tr_store($lang, $pairs) {
    if (!$pairs) {
        return 0;
    }
    if ($lang === 'nn') {
        $dict = (array) get_option('sta_nn_translations', array());
        foreach ($pairs as $text => $translated) {
            $dict[sta_tr_key($text)] = trim((string) $translated);
        }
        update_option('sta_nn_translations', $dict, false);
        sta_tr_map('nn', true);
        return count($pairs);
    }
    $query = sta_tr_en_query();
    if (!$query) {
        return 0;
    }
    $code = sta_trp_english_code();
    // TranslatePress stores text as it appears in the HTML, so cover the row(s) already there
    // and the escaped spelling of a new text.
    $raw = array();
    $by_key = array();
    foreach (sta_tr_en_rows() as $row) {
        $by_key[sta_tr_key($row['original'])][] = $row['original'];
    }
    foreach ($pairs as $text => $translated) {
        $key = sta_tr_key($text);
        $forms = $by_key[$key] ?? array($key, trim(wp_specialchars_decode($key) === $key ? esc_html($key) : $key));
        foreach (array_unique($forms) as $form) {
            $raw[$form] = trim((string) $translated);
        }
    }
    $existing = $query->get_string_ids(array_keys($raw), $code);
    $missing  = array_values(array_diff(array_keys($raw), array_keys($existing)));
    if ($missing) {
        $query->insert_strings($missing, $code);
        $existing = $query->get_string_ids(array_keys($raw), $code);
    }
    $update = array();
    foreach ($raw as $original => $translated) {
        if (!isset($existing[$original])) {
            continue;
        }
        $row = $existing[$original];
        $update[] = array(
            'id'          => $row->id,
            'original'    => $original,
            'translated'  => $translated,
            'status'      => $translated === '' ? 0 : 2,
            'block_type'  => 0,
            'original_id' => $row->original_id ?? null,
        );
    }
    if ($update) {
        $query->update_strings($update, $code, array('id', 'translated', 'status'));
    }
    sta_tr_map('en', true);
    return count($pairs);
}

/** Texts whose translation was carried over and should be checked: lang => new text => old text. */
function sta_tr_outdated() {
    $value = get_option('sta_tr_outdated', array());
    return is_array($value) ? $value : array();
}

/** Text nodes of a value, split the same way the page output is. */
function sta_tr_nodes($value, $split_lines = false) {
    $value = (string) $value;
    $parts = $split_lines ? preg_split('/\r?\n/', $value) : array($value);
    $nodes = array();
    foreach ($parts as $part) {
        if (preg_match_all('#>([^<]+)<#u', '<x>' . $part . '</x>', $m)) {
            foreach ($m[1] as $text) {
                $key = sta_tr_key($text);
                if ($key !== '' && preg_match('/\p{L}/u', $key)) {
                    $nodes[] = $key;
                }
            }
        }
    }
    return $nodes;
}

/* ------------------------------------------------------------------ Elementor: keep translations */

/** All text-like settings of an Elementor tree: "element id/setting path" => value. */
function sta_tr_elementor_texts($elements) {
    $out = array();
    $walk = function ($value, $path) use (&$walk, &$out) {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                if (is_string($key) && strpos($key, '_') === 0 && $key !== '_id') {
                    continue;
                }
                $walk($item, $path . '/' . (is_array($item) && isset($item['_id']) ? $item['_id'] : $key));
            }
            return;
        }
        if (is_string($value) && strpos($value, '{{') === false && preg_match('/\p{L}{2}/u', $value)) {
            $out[$path] = $value;
        }
    };
    foreach ((array) $elements as $element) {
        if (!is_array($element)) {
            continue;
        }
        if (!empty($element['id']) && !empty($element['settings']) && ($element['elType'] ?? '') === 'widget') {
            $walk($element['settings'], $element['id']);
        }
        if (!empty($element['elements'])) {
            $out += sta_tr_elementor_texts($element['elements']);
        }
    }
    return $out;
}

/**
 * Pairs of (old text, new text) for one setting. Unchanged texts at the start and end are matched
 * first; what is left is only paired when the same number of texts remain, so a split or added
 * paragraph is reported as missing instead of guessed.
 */
function sta_tr_pair_nodes($old, $new) {
    while ($old && $new && $old[0] === $new[0]) {
        array_shift($old);
        array_shift($new);
    }
    while ($old && $new && end($old) === end($new)) {
        array_pop($old);
        array_pop($new);
    }
    if (!$old || count($old) !== count($new)) {
        return array();
    }
    return array_combine($new, $old);
}

/** Copies translations from each old text to its new wording and marks them for checking. */
function sta_tr_carry_over($old_texts, $new_texts) {
    $pairs = array();
    foreach ($new_texts as $path => $value) {
        if (!isset($old_texts[$path]) || $old_texts[$path] === $value) {
            continue;
        }
        $pairs += sta_tr_pair_nodes(sta_tr_nodes($old_texts[$path]), sta_tr_nodes($value));
    }
    if (!$pairs) {
        return 0;
    }
    $outdated = sta_tr_outdated();
    $carried  = 0;
    foreach (array_keys(STA_TR_LANGS) as $lang) {
        $map  = sta_tr_map($lang);
        $save = array();
        foreach ($pairs as $new => $old) {
            if (isset($map[$old]) && !isset($map[$new])) {
                $save[$new] = $map[$old];
                $outdated[$lang][$new] = $old;
            }
        }
        $carried += sta_tr_store($lang, $save);
    }
    if ($carried) {
        update_option('sta_tr_outdated', $outdated, false);
    }
    return $carried;
}

add_action('elementor/document/before_save', function ($document, $data) {
    if (empty($data['elements']) || !is_array($data['elements']) || $document->get_id() !== $document->get_main_id()) {
        return; // Autosaves and revisions do not reach visitors.
    }
    $old = json_decode((string) get_post_meta($document->get_main_id(), '_elementor_data', true), true);
    if (is_array($old)) {
        sta_tr_carry_over(sta_tr_elementor_texts($old), sta_tr_elementor_texts($data['elements']));
    }
    delete_transient('sta_tr_managed');
}, 10, 2);

/**
 * On production something rendering the page during an Elementor save prints the page's CSS in
 * front of the JSON answer. The save succeeds, but the editor cannot read the answer, shows an
 * error and keeps the page marked as unsaved. Remove that CSS from the answer.
 */
add_action('admin_init', function () {
    if (wp_doing_ajax() && ($_REQUEST['action'] ?? '') === 'elementor_ajax') { // phpcs:ignore WordPress.Security.NonceVerification
        ob_start(function ($output) {
            return preg_replace('#^\s*(?:<style id="elementor-post-\d+">.*?</style>\s*)+(?=\{)#s', '', $output);
        });
    }
}, 1);

/** After saving in Elementor: a message with a button to the translations of this page. */
add_action('elementor/editor/after_enqueue_scripts', function () {
    $post_id = get_the_ID();
    if (!$post_id || !current_user_can('edit_pages')) {
        return;
    }
    wp_add_inline_script('elementor-editor', 'jQuery(window).on("elementor:init",function(){var url=' . wp_json_encode(sta_tr_page_url($post_id)) . ';var show=function(){if(!window.elementor||!elementor.notifications){return;}elementor.notifications.showToast({message:"Lagret. Husk nynorsk og engelsk for tekst du har endret.",buttons:[{name:"sta_translate",text:"Oversett",callback:function(){window.open(url,"_blank");}}]});};if(window.$e&&$e.commands){$e.commands.on("run:after",function(c,cmd){if(cmd==="document/save/update"||cmd==="document/save/publish"){show();}});}});');
});

function sta_tr_page_url($post_id) {
    return admin_url('admin.php?page=sta-oversettelser&side=' . (int) $post_id);
}

/** The address of a page in each language, the same ones visitors use. */
function sta_tr_page_urls($page) {
    $home = rtrim((string) get_option('home'), '/');
    if (!get_option('permalink_structure')) {
        $base = add_query_arg('page_id', $page->ID, $home . '/');
        return array('no' => add_query_arg('lang', 'no', $base), 'nn' => add_query_arg('lang', 'nn', $base), 'en' => add_query_arg('lang', 'en', $base));
    }
    $path = (int) $page->ID === (int) get_option('page_on_front') ? '/' : '/' . trim(get_page_uri($page), '/') . '/';
    return array('no' => $home . $path, 'nn' => $home . '/nn' . $path, 'en' => $home . '/en' . $path);
}

function sta_tr_page_data_attrs($page) {
    $urls = sta_tr_page_urls($page);
    return sprintf('data-page="%d" data-url="%s" data-url-nn="%s" data-url-en="%s"', (int) $page->ID, esc_url($urls['no']), esc_url($urls['nn']), esc_url($urls['en']));
}

/* ------------------------------------------------------------------ Status of entry fields */

function sta_tr_field_source($post, $field) {
    $source = $field['source'] ?? '';
    if (in_array($source, array('post_title', 'post_excerpt', 'post_content'), true)) {
        return (string) $post->{$source};
    }
    return (string) get_post_meta($post->ID, $source, true);
}

/** What visitors get from the page-text stores when the field itself is empty. */
function sta_tr_field_fallback($post, $field) {
    $nodes = sta_tr_nodes(sta_tr_field_source($post, $field), ($field['type'] ?? '') === 'textarea');
    if (!$nodes) {
        return '';
    }
    $map = sta_tr_map($field['lang']);
    $out = array();
    foreach ($nodes as $node) {
        if (!isset($map[$node])) {
            return '';
        }
        $out[] = $map[$node];
    }
    return implode("\n", $out);
}

/** ok, outdated, missing or empty (nothing to translate) for one language field. */
function sta_tr_field_state($post, $field) {
    $source = sta_tr_field_source($post, $field);
    $text   = sta_tr_key(wp_strip_all_tags($source));
    if ($text === '') {
        return 'empty';
    }
    $value = trim((string) get_post_meta($post->ID, $field['key'], true));
    if ($value !== '') {
        $hashes = get_post_meta($post->ID, '_sta_tr_hash', true);
        $hash   = is_array($hashes) ? ($hashes[$field['key']] ?? '') : '';
        return ($hash !== '' && $hash !== md5($text)) ? 'outdated' : 'ok';
    }
    if (sta_tr_field_fallback($post, $field) !== '') {
        return 'ok';
    }
    return !empty($field['optional']) ? 'empty' : 'missing';
}

/** array(lang => array('missing' => n, 'outdated' => n)) for an entry. */
function sta_tr_entry_status($post) {
    $def    = sta_cms_form_def($post->post_type);
    $status = array('nn' => array('missing' => 0, 'outdated' => 0), 'en' => array('missing' => 0, 'outdated' => 0));
    $older_news = $post->post_type === 'post' && $post->post_date < STA_TR_NEWS_FROM;
    foreach ($def['fields'] ?? array() as $field) {
        if (empty($field['lang'])) {
            continue;
        }
        $state = sta_tr_field_state($post, $field);
        if ($older_news && $state === 'missing') {
            continue;
        }
        if (isset($status[$field['lang']][$state])) {
            $status[$field['lang']][$state]++;
        }
    }
    return $status;
}

function sta_tr_status_label($counts) {
    $parts = array();
    if ($counts['missing']) {
        $parts[] = $counts['missing'] . ' mangler';
    }
    if ($counts['outdated']) {
        $parts[] = $counts['outdated'] . ' utdatert';
    }
    return implode(', ', $parts);
}

/** Before saving: the texts and translations as they are now. */
function sta_tr_snapshot($post, $fields) {
    $out = array();
    $old = sta_tr_previous_post($post);
    foreach ($fields as $field) {
        if (!empty($field['lang'])) {
            $out[$field['key']] = array(
                'source' => sta_tr_field_source($old, $field),
                'value'  => (string) get_post_meta($post->ID, $field['key'], true),
            );
        }
    }
    return $out;
}

/** After saving: a translation that was edited (or confirmed) now matches the current source. */
function sta_tr_update_hashes($post, $fields, $before) {
    $hashes = get_post_meta($post->ID, '_sta_tr_hash', true);
    $hashes = is_array($hashes) ? $hashes : array();
    // phpcs:ignore WordPress.Security.NonceVerification -- verified by the caller.
    $confirmed = isset($_POST['sta_tr_ok']) ? array_map('sanitize_key', (array) wp_unslash($_POST['sta_tr_ok'])) : array();
    $fresh = get_post($post->ID);
    foreach ($fields as $field) {
        if (empty($field['lang'])) {
            continue;
        }
        $key   = $field['key'];
        $value = (string) get_post_meta($post->ID, $key, true);
        if (trim($value) === '') {
            unset($hashes[$key]);
            continue;
        }
        $changed = $value !== ($before[$key]['value'] ?? '');
        if ($changed || in_array(sanitize_key($key), $confirmed, true)) {
            $hashes[$key] = md5(sta_tr_key(wp_strip_all_tags(sta_tr_field_source($fresh, $field))));
        } elseif (!isset($hashes[$key])) {
            $hashes[$key] = md5(sta_tr_key(wp_strip_all_tags($before[$key]['source'] ?? '')));
        }
    }
    update_post_meta($post->ID, '_sta_tr_hash', $hashes);
}

/** The post as it was before this save (WordPress has already written the new title and text). */
function sta_tr_previous_post($post) {
    return $GLOBALS['sta_tr_post_before'][$post->ID] ?? $post;
}
add_action('post_updated', function ($post_id, $after, $before) {
    $GLOBALS['sta_tr_post_before'][$post_id] = $before;
}, 10, 3);

/** Shown above a translation field: the status, and the Bokmål text being translated. */
function sta_tr_render_field_status($post, $field) {
    $state = sta_tr_field_state($post, $field);
    if ($state === 'outdated') {
        printf(
            '<p class="sta-tr-note is-outdated">Bokmålsteksten er endret etter at dette ble oversatt. Oppdater oversettelsen, eller <label><input type="checkbox" name="sta_tr_ok[]" value="%s"> den er fortsatt riktig</label>.</p>',
            esc_attr(sanitize_key($field['key']))
        );
    }
    if ($state === 'ok' && trim((string) get_post_meta($post->ID, $field['key'], true)) === '') {
        echo '<p class="sta-tr-note">Vises nå med oversettelsen fra Oversettelser. Skriv her bare hvis denne oppføringen skal ha en egen tekst.</p>';
    }
    printf('<div class="sta-tr-source" data-source="%s"><strong>Bokmål:</strong> <span></span></div>', esc_attr($field['source'] ?? ''));
}

/** Greyed-out text in an empty field: what visitors see now. */
function sta_tr_placeholder_attr($post, $field, $value) {
    if (trim((string) $value) !== '') {
        return '';
    }
    $fallback = sta_tr_field_fallback($post, $field);
    return $fallback !== '' ? ' placeholder="' . esc_attr($fallback) . '"' : '';
}

function sta_tr_render_list_status($post_id) {
    $post = get_post($post_id);
    $status = sta_tr_entry_status($post);
    foreach (array('nn' => 'NN', 'en' => 'EN') as $lang => $short) {
        $label = sta_tr_status_label($status[$lang]);
        printf(
            '<span class="sta-cms-pill %1$s" title="%2$s">%3$s %4$s</span> ',
            $label === '' ? 'is-on' : 'is-warn',
            esc_attr($label === '' ? 'Oversatt' : $label),
            esc_html($short),
            $label === '' ? '✓' : '!'
        );
    }
}

add_filter('manage_post_posts_columns', function ($columns) {
    $columns['sta_lang'] = 'Språk';
    return $columns;
});
add_action('manage_post_posts_custom_column', function ($column, $post_id) {
    if ($column === 'sta_lang') {
        sta_tr_render_list_status($post_id);
    }
}, 10, 2);

/* ------------------------------------------------------------------ Language bar on edit forms */

add_action('edit_form_top', function ($post) {
    $def = sta_cms_form_def($post->post_type);
    if (!$def || !array_filter(wp_list_pluck($def['fields'], 'lang'))) {
        return;
    }
    $status = sta_tr_entry_status($post);
    echo '<div class="sta-lang-bar" role="tablist" aria-label="Språk">';
    echo '<button type="button" role="tab" class="sta-lang-tab is-active" data-lang="no" aria-selected="true">Bokmål</button>';
    foreach (STA_TR_LANGS as $lang => $name) {
        $label = sta_tr_status_label($status[$lang]);
        printf(
            '<button type="button" role="tab" class="sta-lang-tab" data-lang="%1$s" aria-selected="false">%2$s%3$s</button>',
            esc_attr($lang),
            esc_html($name),
            $label !== '' ? ' <span class="sta-lang-badge">' . esc_html($label) . '</span>' : ''
        );
    }
    echo '</div>';
});

/** News: tick «Nyheter» on a new post, since only that category shows under Siste nytt. */
add_filter('wp_terms_checklist_args', function ($args, $post_id) {
    if (($args['taxonomy'] ?? '') === 'category' && get_post_status($post_id) === 'auto-draft') {
        $term = get_term_by('slug', 'nyheter', 'category');
        if ($term) {
            $args['selected_cats'] = array((int) $term->term_id);
        }
    }
    return $args;
}, 10, 2);

/* ------------------------------------------------------------------ Frontend: news in other languages */

function sta_tr_post_field($post_id, $base) {
    if (!function_exists('sta_get_current_language')) {
        return '';
    }
    $lang = sta_get_current_language();
    if ($lang !== 'en' && $lang !== 'nn') {
        return '';
    }
    return trim((string) get_post_meta($post_id, $base . '_' . $lang, true));
}

if (!is_admin()) {
    add_filter('the_title', function ($title, $post_id = 0) {
        $post_id = (int) $post_id;
        if (!$post_id || !sta_cms_form_def(get_post_type($post_id))) {
            return $title;
        }
        $value = sta_tr_post_field($post_id, '_sta_title');
        return $value !== '' ? $value : $title;
    }, 10, 2);
    add_filter('single_post_title', function ($title, $post) {
        $value = ($post instanceof WP_Post && $post->post_type === 'post') ? sta_tr_post_field($post->ID, '_sta_title') : '';
        return $value !== '' ? $value : $title;
    }, 10, 2);
    add_filter('the_content', function ($content) {
        $post = get_post();
        if (!$post || $post->post_type !== 'post') {
            return $content;
        }
        $value = sta_tr_post_field($post->ID, '_sta_content');
        return $value !== '' ? $value : $content;
    }, 1);
    add_filter('get_the_excerpt', function ($excerpt, $post = null) {
        $post = get_post($post);
        if (!$post || $post->post_type !== 'post') {
            return $excerpt;
        }
        $value = sta_tr_post_field($post->ID, '_sta_excerpt');
        if ($value !== '') {
            return $value;
        }
        $body = sta_tr_post_field($post->ID, '_sta_content');
        return $body !== '' ? wp_trim_words(wp_strip_all_tags(strip_shortcodes($body)), 55, '…') : $excerpt;
    }, 20, 2);
    /* Yoast builds the browser title and share title from the stored Bokmål title. */
    $sta_tr_seo_title = function ($title) {
        if (!is_singular('post')) {
            return $title;
        }
        $post  = get_queried_object();
        $value = sta_tr_post_field($post->ID, '_sta_title');
        return $value !== '' ? str_replace($post->post_title, $value, $title) : $title;
    };
    add_filter('wpseo_title', $sta_tr_seo_title);
    add_filter('wpseo_opengraph_title', $sta_tr_seo_title);
    add_filter('wpseo_metadesc', function ($description) {
        if (!is_singular('post')) {
            return $description;
        }
        $value = sta_tr_post_field(get_queried_object_id(), '_sta_excerpt');
        return $value !== '' ? wp_html_excerpt($value, 155, '…') : $description;
    }, 20);
}

/* ------------------------------------------------------------------ Links to the screen */

add_filter('page_row_actions', function ($actions, $post) {
    if (current_user_can('edit_pages')) {
        $actions['sta_translate'] = '<a href="' . esc_url(sta_tr_page_url($post->ID)) . '">Oversett</a>';
    }
    return $actions;
}, 10, 2);

add_action('add_meta_boxes_page', function ($post) {
    add_meta_box('sta_tr_page', 'Oversettelser', function ($post) {
        printf('<p>Skriv nynorsk og engelsk for teksten på denne siden.</p><p><a class="button" href="%s">Oversett siden</a></p>', esc_url(sta_tr_page_url($post->ID)));
    }, 'page', 'side', 'default');
});

/** The admin bar's «Oversett side» opens the shared screen for the current page. */
add_action('admin_bar_menu', function ($bar) {
    if (is_admin() || !current_user_can('edit_pages')) {
        return;
    }
    $node = $bar->get_node('trp_edit_translation');
    if ($node) {
        $bar->remove_node('trp_edit_translation');
    }
    $target = is_singular('page') ? sta_tr_page_url(get_queried_object_id()) : admin_url('admin.php?page=sta-oversettelser');
    if (is_singular('post') && current_user_can('edit_post', get_queried_object_id())) {
        $target = get_edit_post_link(get_queried_object_id(), 'raw');
    }
    $bar->add_node(array('id' => 'sta_translate', 'title' => 'Oversett side', 'href' => $target));
}, 999);

/* ------------------------------------------------------------------ Texts managed elsewhere */

/**
 * Texts that come from entries or terms: normalised text => edit URL. They are translated in the
 * entry's own form, so the page screen links there instead of offering a second place to do it.
 */
function sta_tr_managed_texts() {
    $cached = get_transient('sta_tr_managed');
    if (is_array($cached)) {
        return $cached;
    }
    $out = array();
    foreach (sta_cms_form_types() as $type => $def) {
        $posts = get_posts(array('post_type' => $type, 'post_status' => 'publish', 'posts_per_page' => -1));
        foreach ($posts as $post) {
            $url = admin_url('post.php?action=edit&post=' . $post->ID);
            $out[sta_tr_key($post->post_title)] = $url;
            foreach ($def['fields'] as $field) {
                if (empty($field['lang'])) {
                    continue;
                }
                foreach (sta_tr_nodes(sta_tr_field_source($post, $field), ($field['type'] ?? '') === 'textarea') as $node) {
                    $out[$node] = $url;
                }
            }
        }
    }
    foreach (array_keys(sta_cms_taxonomies()) as $tax) {
        $terms = get_terms(array('taxonomy' => $tax, 'hide_empty' => false));
        foreach (is_wp_error($terms) ? array() : $terms as $term) {
            $out[sta_tr_key($term->name)] = admin_url('term.php?taxonomy=' . $tax . '&tag_ID=' . $term->term_id);
        }
    }
    set_transient('sta_tr_managed', $out, HOUR_IN_SECONDS);
    return $out;
}

add_action('save_post', function ($post_id) {
    if (!wp_is_post_revision($post_id)) {
        delete_transient('sta_tr_managed');
    }
});

/* ------------------------------------------------------------------ Screen: Oversettelser */

function sta_tr_page_list() {
    return get_pages(array('post_status' => array('publish'), 'sort_column' => 'post_title'));
}

function sta_tr_render_page() {
    if (!current_user_can('edit_pages')) {
        return;
    }
    $page_id = isset($_GET['side']) ? absint($_GET['side']) : 0; // phpcs:ignore WordPress.Security.NonceVerification
    $page    = $page_id ? get_post($page_id) : null;
    if ($page && $page->post_type === 'page') {
        sta_tr_render_single_page($page);
        return;
    }
    $stats = (array) get_option('sta_tr_stats', array());
    echo '<div class="wrap sta-tr-wrap"><h1>Oversettelser</h1>';
    echo '<p class="about-description">Her ser du hva som mangler på nynorsk og engelsk. Tekst som ikke er oversatt, vises på bokmål.</p>';
    echo '<h2>Sider</h2><p>Tekst du skriver i Elementor. <button type="button" class="button" id="sta-tr-scan">Sjekk alle sider</button> <span id="sta-tr-scan-status"></span></p>';
    echo '<table class="widefat striped sta-tr-overview"><thead><tr><th>Side</th><th>Nynorsk</th><th>Engelsk</th><th></th></tr></thead><tbody>';
    foreach (sta_tr_page_list() as $item) {
        $s = $stats[$item->ID] ?? null;
        printf(
            '<tr %2$s><td>%3$s</td><td class="sta-tr-nn">%4$s</td><td class="sta-tr-en">%5$s</td><td><a class="button" href="%6$s">Oversett</a></td></tr>',
            0,
            sta_tr_page_data_attrs($item), // Escaped in the helper.
            esc_html(get_the_title($item)),
            esc_html($s ? sta_tr_status_label($s['nn']) ?: '✓' : '—'),
            esc_html($s ? sta_tr_status_label($s['en']) ?: '✓' : '—'),
            esc_url(sta_tr_page_url($item->ID))
        );
    }
    echo '</tbody></table>';
    echo '<h2>Innhold</h2><p>Oppføringer oversettes i sitt eget skjema, med språkvalget øverst. Navn regnes ikke som manglende.</p>';
    echo '<table class="widefat striped"><thead><tr><th>Type</th><th>Nynorsk</th><th>Engelsk</th></tr></thead><tbody>';
    foreach (sta_cms_form_types() as $type => $def) {
        if (!array_filter(wp_list_pluck($def['fields'], 'lang'))) {
            continue;
        }
        $total = array('nn' => array('missing' => 0, 'outdated' => 0), 'en' => array('missing' => 0, 'outdated' => 0));
        foreach (get_posts(array('post_type' => $type, 'post_status' => 'publish', 'posts_per_page' => -1)) as $post) {
            $status = sta_tr_entry_status($post);
            foreach ($total as $lang => $counts) {
                $total[$lang]['missing']  += $status[$lang]['missing'];
                $total[$lang]['outdated'] += $status[$lang]['outdated'];
            }
        }
        printf(
            '<tr><td><a href="%1$s">%2$s</a></td><td>%3$s</td><td>%4$s</td></tr>',
            esc_url(admin_url('edit.php?post_type=' . $type)),
            esc_html($def['plural']),
            esc_html(sta_tr_status_label($total['nn']) ?: '✓'),
            esc_html(sta_tr_status_label($total['en']) ?: '✓')
        );
    }
    echo '</tbody></table>';
    printf('<p class="description">Sikkerhetskopi av nynorsk finner du under <a href="%s">Nynorsk-tekst</a>.</p>', esc_url(admin_url('options-general.php?page=sta-nynorsk')));
    echo '</div>';
}

function sta_tr_render_single_page($page) {
    $urls = sta_tr_page_urls($page);
    echo '<div class="wrap sta-tr-wrap">';
    printf('<p><a href="%s">← Alle oversettelser</a></p>', esc_url(admin_url('admin.php?page=sta-oversettelser')));
    printf('<h1>Oversett: %s</h1>', esc_html(get_the_title($page)));
    printf(
        '<p>Se siden på <a href="%1$s" target="_blank" rel="noopener">bokmål ↗</a>, <a href="%2$s" target="_blank" rel="noopener">nynorsk ↗</a> eller <a href="%3$s" target="_blank" rel="noopener">engelsk ↗</a>. Er teksten lik på et språk (f.eks. et navn), trykk «Lik bokmål».</p>',
        esc_url($urls['no']),
        esc_url($urls['nn']),
        esc_url($urls['en'])
    );
    echo '<div id="sta-tr-page" ' . sta_tr_page_data_attrs($page) . '></div>'; // Escaped in the helper.
    echo '</div>';
}

add_action('admin_enqueue_scripts', function () {
    $screen = get_current_screen();
    if (!$screen || substr($screen->id, -strlen('sta-oversettelser')) !== 'sta-oversettelser') {
        return;
    }
    wp_enqueue_style('sta-cms-admin', STA_CORE_URL . 'assets/admin.css', array(), STA_CORE_VERSION);
    wp_enqueue_script('sta-tr', STA_CORE_URL . 'assets/translate.js', array(), STA_CORE_VERSION, true);
    wp_localize_script('sta-tr', 'staTr', array(
        'ajax'  => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('sta_tr'),
    ));
});

/**
 * Status of texts collected from a page. Body: nonce, page_id, texts (JSON array) and same_nn /
 * same_en: the texts still in Bokmål on that language's version of the page. A text with no stored
 * translation that is not in that list is translated by the theme, so it is not missing.
 */
add_action('wp_ajax_sta_tr_lookup', function () {
    check_ajax_referer('sta_tr', 'nonce');
    if (!current_user_can('edit_pages')) {
        wp_send_json_error('Ingen tilgang.');
    }
    $texts    = json_decode(wp_unslash($_POST['texts'] ?? '[]'), true);
    $nn       = sta_tr_map('nn');
    $en       = sta_tr_map('en');
    $outdated = sta_tr_outdated();
    $managed  = sta_tr_managed_texts();
    $same     = array(
        'nn' => array_flip(array_map('sta_tr_key', (array) json_decode(wp_unslash($_POST['same_nn'] ?? '[]'), true))),
        'en' => array_flip(array_map('sta_tr_key', (array) json_decode(wp_unslash($_POST['same_en'] ?? '[]'), true))),
    );
    $rows     = array();
    $stat     = array('nn' => array('missing' => 0, 'outdated' => 0), 'en' => array('missing' => 0, 'outdated' => 0));
    foreach (array_unique(array_map('sta_tr_key', (array) $texts)) as $key) {
        if ($key === '') {
            continue;
        }
        $row = array(
            'text'        => $key,
            'nn'          => $nn[$key] ?? '',
            'en'          => $en[$key] ?? '',
            'nn_outdated' => $outdated['nn'][$key] ?? '',
            'en_outdated' => $outdated['en'][$key] ?? '',
            'managed'     => $managed[$key] ?? '',
            'nn_missing'  => !isset($nn[$key]) && isset($same['nn'][$key]),
            'en_missing'  => !isset($en[$key]) && isset($same['en'][$key]),
        );
        if ($row['managed'] === '') {
            foreach (array('nn', 'en') as $lang) {
                if ($row[$lang . '_missing']) {
                    $stat[$lang]['missing']++;
                } elseif ($row[$lang . '_outdated'] !== '') {
                    $stat[$lang]['outdated']++;
                }
            }
        }
        $rows[] = $row;
    }
    $page = absint($_POST['page_id'] ?? 0);
    if ($page) {
        $stats = (array) get_option('sta_tr_stats', array());
        $stats[$page] = $stat;
        update_option('sta_tr_stats', $stats, false);
    }
    wp_send_json_success(array('rows' => $rows, 'stat' => $stat, 'label' => array('nn' => sta_tr_status_label($stat['nn']) ?: '✓', 'en' => sta_tr_status_label($stat['en']) ?: '✓')));
});

/** Saves changed rows. Body: nonce, items (JSON array of {text, nn?, en?}). */
add_action('wp_ajax_sta_tr_save', function () {
    check_ajax_referer('sta_tr', 'nonce');
    if (!current_user_can('edit_pages')) {
        wp_send_json_error('Ingen tilgang.');
    }
    $items    = json_decode(wp_unslash($_POST['items'] ?? '[]'), true);
    $outdated = sta_tr_outdated();
    $pairs    = array('nn' => array(), 'en' => array());
    foreach ((array) $items as $item) {
        $key = sta_tr_key($item['text'] ?? '');
        if ($key === '') {
            continue;
        }
        foreach (array('nn', 'en') as $lang) {
            if (array_key_exists($lang, $item)) {
                $pairs[$lang][$key] = sanitize_textarea_field((string) $item[$lang]);
                unset($outdated[$lang][$key]);
            } elseif (!empty($item[$lang . '_ok'])) {
                unset($outdated[$lang][$key]);
            }
        }
    }
    $saved = sta_tr_store('nn', $pairs['nn']) + sta_tr_store('en', $pairs['en']);
    update_option('sta_tr_outdated', $outdated, false);
    sta_cms_schedule_purge();
    wp_send_json_success(array('saved' => $saved));
});
