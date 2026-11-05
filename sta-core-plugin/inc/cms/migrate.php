<?php
/**
 * One-time move of hand-built Elementor content into STA-innhold.
 *
 * Each step reads a page's current Elementor content, creates the matching entries (skipping
 * entries that already exist, so it can safely run twice) and then turns the hand-built cards
 * into one template card filled from STA-innhold (see dynamic.php). Before a page is changed,
 * its Elementor data is copied to the post meta «_sta_cms_backup» so the change can be undone
 * with «Angre» – nothing is deleted.
 *
 * «Forhåndsvis» runs the same code without saving anything and lists what would happen.
 */

if (!defined('ABSPATH')) {
    exit;
}

function sta_mig_steps() {
    return array(
        'people'     => array('label' => 'Personer og grupper (STA-styret og Sekretariatet)', 'pages' => array('sta-styret')),
        'parliament' => array('label' => 'Studentparlamentet (ansvarlige, fakulteter og representanter)', 'pages' => array('studentparlamentet')),
        'positions'  => array('label' => 'Verv og utvalg (Råd, styrer og utvalg)', 'pages' => array('rad-styrer-og-utvalg')),
        'vacancies'  => array('label' => 'Ledige verv', 'pages' => array('ledige-verv')),
        'documents'  => array('label' => 'Dokumenter (Dokumenter, Sakspapirer, Referater, Resolusjoner)', 'pages' => array('dokumenter', 'dokumenter/sakspapirer', 'dokumenter/referat-fra-moter-i-studentparlamentet', 'dokumenter/resolusjoner')),
        'partners'   => array('label' => 'Samarbeidspartnere (forsiden)', 'pages' => array('')),
    );
}

/* ------------------------------------------------------------------ Admin screen */

add_action('admin_menu', function () {
    add_submenu_page(STA_CMS_MENU, 'Flytt innhold fra sidene', 'Flytt innhold (engangs)', 'manage_options', 'sta-cms-migrate', 'sta_mig_render_page');
}, 20);

function sta_mig_render_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    $log   = get_option('sta_cms_migration', array());
    $steps = sta_mig_steps();
    echo '<div class="wrap"><h1>Flytt innhold fra sidene til STA-innhold</h1>';
    echo '<p>Hvert steg leser innholdet som i dag ligger direkte på siden i Elementor, oppretter oppføringer under STA-innhold (uten duplikater) og gjør kortene på siden om til én mal som fylles automatisk. Sidens gamle innhold tas vare på og kan gjenopprettes med «Angre». Kjør «Forhåndsvis» først.</p>';
    $result = get_transient('sta_mig_result_' . get_current_user_id());
    if ($result) {
        delete_transient('sta_mig_result_' . get_current_user_id());
        echo '<div class="notice notice-info"><p><strong>' . esc_html($result['title']) . '</strong></p><pre style="white-space:pre-wrap;max-height:520px;overflow:auto;">' . esc_html(implode("\n", $result['lines'])) . '</pre></div>';
    }
    echo '<table class="widefat striped"><thead><tr><th>Steg</th><th>Status</th><th></th></tr></thead><tbody>';
    foreach ($steps as $key => $step) {
        $done = $log[$key] ?? null;
        $url  = function ($mode) use ($key) {
            return wp_nonce_url(admin_url('admin-post.php?action=sta_cms_migrate&step=' . $key . '&mode=' . $mode), 'sta_cms_migrate_' . $key);
        };
        printf(
            '<tr><td><strong>%s</strong></td><td>%s</td><td><a class="button" href="%s">Forhåndsvis</a> <a class="button button-primary" href="%s" onclick="return confirm(\'Kjøre steget nå? Siden endres, men det gamle innholdet tas vare på.\');">Kjør</a> <a class="button-link" href="%s" onclick="return confirm(\'Gjenopprette sidens innhold slik det var før steget? Oppføringene under STA-innhold blir liggende.\');">Angre sideendring</a></td></tr>',
            esc_html($step['label']),
            $done ? esc_html('Kjørt ' . $done['time'] . ' – ' . $done['summary']) : 'Ikke kjørt',
            esc_url($url('dry')),
            esc_url($url('run')),
            esc_url($url('undo'))
        );
    }
    echo '</tbody></table></div>';
}

add_action('admin_post_sta_cms_migrate', function () {
    $step = isset($_GET['step']) ? sanitize_key(wp_unslash($_GET['step'])) : '';
    $mode = isset($_GET['mode']) ? sanitize_key(wp_unslash($_GET['mode'])) : 'dry';
    if (!current_user_can('manage_options') || !isset(sta_mig_steps()[$step]) || !check_admin_referer('sta_cms_migrate_' . $step)) {
        wp_die('Ingen tilgang.');
    }
    $mig = new STA_Migration($mode === 'run');
    if ($mode === 'undo') {
        $lines = $mig->undo($step);
        $title = 'Angret sideendringer';
    } else {
        $mig->{'step_' . $step}();
        $lines = $mig->log;
        $title = $mode === 'run' ? 'Steget er kjørt' : 'Forhåndsvisning (ingenting er lagret)';
        if ($mode === 'run') {
            $log = get_option('sta_cms_migration', array());
            $log[$step] = array('time' => wp_date('j.n.Y H:i'), 'summary' => $mig->summary());
            update_option('sta_cms_migration', $log, false);
            sta_cms_purge_caches();
        }
    }
    set_transient('sta_mig_result_' . get_current_user_id(), array('title' => $title, 'lines' => $lines), 300);
    wp_safe_redirect(admin_url('admin.php?page=sta-cms-migrate'));
    exit;
});

/* ------------------------------------------------------------------ Migration */

class STA_Migration {
    public $log = array();
    private $run;
    private $created = 0;
    private $reused = 0;
    private $pages = 0;
    private $seen_files = array();

    public function __construct($run) {
        $this->run = $run;
    }

    public function summary() {
        return sprintf('%d opprettet, %d fantes fra før, %d side(r) endret', $this->created, $this->reused, $this->pages);
    }

    private function say($line) {
        $this->log[] = $line;
    }

    /* ---------- Elementor data helpers */

    private function page($path) {
        $id = $path === '' ? (int) get_option('page_on_front') : (int) (sta_cms_find_page($path)->ID ?? 0);
        if (!$id) {
            $this->say('! Fant ikke siden «' . ($path === '' ? 'forsiden' : $path) . '».');
        }
        return $id;
    }

    private function data($page_id) {
        $data = json_decode((string) get_post_meta($page_id, '_elementor_data', true), true);
        return is_array($data) ? $data : array();
    }

    private function save_page($page_id, $data) {
        $this->pages++;
        if (!$this->run) {
            $this->say('  (forhåndsvisning) Siden «' . get_the_title($page_id) . '» ville blitt endret.');
            return;
        }
        if (get_post_meta($page_id, '_sta_cms_backup', true) === '') {
            add_post_meta($page_id, '_sta_cms_backup', wp_slash((string) get_post_meta($page_id, '_elementor_data', true)), true);
        }
        update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode($data)));
        delete_post_meta($page_id, '_elementor_css');
        delete_post_meta($page_id, '_elementor_element_cache');
        $this->say('  Siden «' . get_the_title($page_id) . '» er endret (gammelt innhold tatt vare på).');
    }

    public function undo($step) {
        foreach (sta_mig_steps()[$step]['pages'] as $path) {
            $id = $this->page($path);
            if (!$id) {
                continue;
            }
            $backup = get_post_meta($id, '_sta_cms_backup', true);
            if ($backup === '') {
                $this->say('Ingen sikkerhetskopi for «' . get_the_title($id) . '» – ingenting å angre.');
                continue;
            }
            update_post_meta($id, '_elementor_data', wp_slash($backup));
            delete_post_meta($id, '_sta_cms_backup');
            delete_post_meta($id, '_elementor_css');
            delete_post_meta($id, '_elementor_element_cache');
            $this->say('Gjenopprettet «' . get_the_title($id) . '».');
        }
        $log = get_option('sta_cms_migration', array());
        unset($log[$step]);
        update_option('sta_cms_migration', $log, false);
        sta_cms_purge_caches();
        return $this->log;
    }

    /** Reference to the element with this ID anywhere in the tree (null if missing). */
    private function &find(array &$els, $id) {
        $null = null;
        foreach ($els as &$el) {
            if (($el['id'] ?? '') === $id) {
                return $el;
            }
            if (!empty($el['elements'])) {
                $found = &$this->find($el['elements'], $id);
                if ($found !== null) {
                    return $found;
                }
            }
        }
        return $null;
    }

    /** Widgets in document order (optionally of one type). */
    private function widgets($el, $type = null) {
        $out = array();
        foreach ($el['elements'] ?? array() as $child) {
            if (($child['elType'] ?? '') === 'widget' && ($type === null || $child['widgetType'] === $type)) {
                $out[] = $child;
            }
            $out = array_merge($out, $this->widgets($child, $type));
        }
        return $out;
    }

    /** Modify every element in a subtree (callback gets the element by reference). */
    private function each(array &$el, $callback) {
        $callback($el);
        if (!empty($el['elements'])) {
            foreach ($el['elements'] as &$child) {
                $this->each($child, $callback);
            }
        }
    }

    /** ID of the element (in $root) whose direct children include $child_id. */
    private function parent_id($root, $child_id) {
        foreach ($root['elements'] ?? array() as $child) {
            if (($child['id'] ?? '') === $child_id) {
                return $root['id'] ?? '';
            }
            $p = $this->parent_id($child, $child_id);
            if ($p !== '') {
                return $p;
            }
        }
        return '';
    }

    private static function add_class(array &$el, $classes) {
        $key = ($el['elType'] ?? '') === 'widget' ? '_css_classes' : 'css_classes';
        $el['settings'][$key] = trim(($el['settings'][$key] ?? '') . ' ' . $classes);
    }

    private static function text($html) {
        return trim(preg_replace('/\s+/u', ' ', html_entity_decode(wp_strip_all_tags((string) $html), ENT_QUOTES, 'UTF-8')));
    }

    /** Text of each paragraph, separated by a blank line (keeps paragraphs in textareas). */
    private static function paragraphs($html) {
        $parts = preg_split('#</p>\s*#i', (string) $html);
        $parts = array_filter(array_map(array(__CLASS__, 'text'), $parts), 'strlen');
        return implode("\n\n", $parts);
    }

    private static function link_url($settings) {
        return (string) ($settings['link']['url'] ?? '');
    }

    /** First link URL anywhere in a subtree (settings «link» or href in HTML). */
    private function first_url($el, $pattern = '#.#') {
        $found = '';
        $this->each($el, function (&$e) use (&$found, $pattern) {
            if ($found !== '') {
                return;
            }
            $u = self::link_url($e['settings'] ?? array());
            if ($u !== '' && preg_match($pattern, $u)) {
                $found = $u;
            }
        });
        return $found;
    }

    /* ---------- Content helpers (find or create, never duplicate) */

    private function find_post($type, $title, $meta = array(), $tax = array()) {
        $args = array('post_type' => $type, 'post_status' => array('publish', 'draft', 'pending', 'private'), 'title' => $title, 'posts_per_page' => 1, 'fields' => 'ids', 'no_found_rows' => true);
        if ($meta) {
            $args['meta_query'] = array();
            foreach ($meta as $k => $v) {
                $args['meta_query'][] = array('key' => $k, 'value' => (string) $v);
            }
        }
        if ($tax) {
            $args['tax_query'] = array();
            foreach ($tax as $t => $term_id) {
                $args['tax_query'][] = array('taxonomy' => $t, 'field' => 'term_id', 'terms' => (int) $term_id);
            }
        }
        $ids = get_posts($args);
        return $ids ? (int) $ids[0] : 0;
    }

    private function create($type, $title, $meta, $order, $terms = array(), $thumb = 0, $slug = '') {
        $this->created++;
        if (!$this->run) {
            $this->say('  + ' . $title);
            return -1;
        }
        $args = array('post_type' => $type, 'post_status' => 'publish', 'post_title' => $title, 'menu_order' => $order);
        if ($slug !== '') {
            $args['post_name'] = $slug;
        }
        $id = wp_insert_post(wp_slash($args), true);
        if (is_wp_error($id)) {
            $this->say('! Kunne ikke opprette «' . $title . '»: ' . $id->get_error_message());
            return 0;
        }
        foreach ($meta as $k => $v) {
            if ($v !== '' && $v !== null) {
                update_post_meta($id, $k, is_string($v) ? wp_slash($v) : $v);
            }
        }
        foreach ($terms as $tax => $term_id) {
            if ($term_id > 0) {
                wp_set_object_terms($id, array((int) $term_id), $tax);
            }
        }
        if ($thumb > 0) {
            set_post_thumbnail($id, $thumb);
        }
        $this->say('  + ' . $title);
        return $id;
    }

    private function term($taxonomy, $name, $order) {
        // Match existing terms loosely («Fakultetet for X» = «Fakultet for x») to avoid near-duplicates.
        $norm = function ($n) {
            return preg_replace('/\s+/u', ' ', str_replace('fakultetet ', 'fakultet ', mb_strtolower(trim($n))));
        };
        foreach (get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false)) as $existing) {
            if ($norm($existing->name) === $norm($name)) {
                if ($existing->name !== $name) {
                    $this->say('  = «' . $name . '» bruker eksisterende «' . $existing->name . '»');
                }
                return (int) $existing->term_id;
            }
        }
        if (!$this->run) {
            $this->say('  + kategori «' . $name . '»');
            return -1;
        }
        $r = wp_insert_term($name, $taxonomy);
        if (is_wp_error($r)) {
            $this->say('! Kunne ikke opprette kategori «' . $name . '»: ' . $r->get_error_message());
            return 0;
        }
        update_term_meta($r['term_id'], 'sta_order', $order);
        $this->say('  + kategori «' . $name . '»');
        return (int) $r['term_id'];
    }

    private static function attachment($image) {
        $id = (int) ($image['id'] ?? 0);
        if ($id && get_post_type($id) === 'attachment') {
            return $id;
        }
        return !empty($image['url']) ? (int) attachment_url_to_postid($image['url']) : 0;
    }

    /* ---------- Shared template marking for people cards */

    private function mark_person_card(array &$card) {
        $name_id = $this->widgets($card, 'heading')[0]['id'] ?? '';
        $this->each($card, function (&$e) use ($name_id) {
            $type = $e['widgetType'] ?? '';
            if ($type === 'image') {
                self::add_class($e, 'sta-img-image');
            } elseif (($e['id'] ?? '') === $name_id) {
                $e['settings']['title'] = '{{name}}';
            } elseif ($type === 'button') {
                $url = self::link_url($e['settings']);
                if (stripos($url, 'mailto:') === 0) {
                    $e['settings']['text'] = '{{email}}';
                    $e['settings']['link']['url'] = '{{email_link}}';
                    self::add_class($e, 'sta-if-email');
                } elseif (stripos($url, 'tel:') === 0) {
                    $e['settings']['text'] = '{{phone}}';
                    $e['settings']['link']['url'] = '{{phone_link}}';
                    self::add_class($e, 'sta-if-phone');
                }
            }
        });
        // Text editors: first = role; one with a list (or after the «Hovedansvar» label) = responsibilities.
        $editors = $this->widgets($card, 'text-editor');
        $role_id = $editors[0]['id'] ?? '';
        $resp_id = '';
        foreach ($editors as $i => $ed) {
            if (stripos($ed['settings']['editor'] ?? '', '<li') !== false || ($i > 0 && self::text($editors[$i - 1]['settings']['editor'] ?? '') === 'Hovedansvar')) {
                $resp_id = $ed['id'];
            }
        }
        $resp_box = $resp_id ? $this->parent_id($card, $resp_id) : '';
        $img      = $this->widgets($card, 'image');
        $img_box  = $img ? $this->parent_id($card, $img[0]['id']) : '';
        $this->each($card, function (&$e) use ($role_id, $resp_id, $resp_box, $img_box) {
            if (($e['id'] ?? '') === $role_id) {
                $e['settings']['editor'] = '<p>{{role}}</p>';
            } elseif (($e['id'] ?? '') === $resp_id) {
                $e['settings']['editor'] = '{{responsibilities_html}}';
            }
            if ($resp_box !== '' && ($e['id'] ?? '') === $resp_box) {
                self::add_class($e, 'sta-if-responsibilities');
            }
            if ($img_box !== '' && ($e['id'] ?? '') === $img_box && ($e['elType'] ?? '') === 'container') {
                self::add_class($e, 'sta-if-image');
            }
        });
    }

    /** Reads a people card: name, role, responsibilities, email, phone, image. */
    private function read_person_card($card) {
        $out = array('name' => '', 'role' => '', 'resp' => array(), 'email' => '', 'phone' => '', 'image' => array());
        $h = $this->widgets($card, 'heading');
        $out['name'] = self::text($h[0]['settings']['title'] ?? '');
        $editors = $this->widgets($card, 'text-editor');
        $out['role'] = self::text($editors[0]['settings']['editor'] ?? '');
        foreach ($editors as $ed) {
            if (preg_match_all('#<li[^>]*>(.*?)</li>#is', $ed['settings']['editor'] ?? '', $m)) {
                $out['resp'] = array_map(array(__CLASS__, 'text'), $m[1]);
            }
        }
        foreach ($this->widgets($card, 'button') as $b) {
            $url = self::link_url($b['settings']);
            if (stripos($url, 'mailto:') === 0) {
                $out['email'] = sanitize_email(substr($url, 7));
            } elseif (stripos($url, 'tel:') === 0) {
                $out['phone'] = self::text($b['settings']['text'] ?? '');
            }
        }
        $img = $this->widgets($card, 'image');
        $out['image'] = $img ? (array) ($img[0]['settings']['image'] ?? array()) : array();
        return $out;
    }

    private function person($p, $order) {
        $id = $this->find_post('sta_board_member', $p['name']);
        if ($id) {
            $this->reused++;
            $this->say('  = ' . $p['name'] . ' (finnes)');
            return $id;
        }
        return $this->create('sta_board_member', $p['name'], array(
            '_sta_role_no'             => $p['role'],
            '_sta_responsibilities_no' => implode("\n", $p['resp']),
            '_sta_email'               => $p['email'],
            '_sta_phone'               => $p['phone'],
            '_sta_image_url'           => (string) ($p['image']['url'] ?? ''),
        ), $order, array(), self::attachment($p['image']));
    }

    private function group($slug, $title, $rows) {
        $group = get_page_by_path($slug, OBJECT, 'sta_group');
        if ($group) {
            $this->reused++;
            $this->say('  = gruppe «' . $title . '» (finnes – medlemmene endres ikke)');
            return;
        }
        $id = $this->create('sta_group', $title, array(), 0, array(), 0, $slug);
        if ($id > 0) {
            sta_cms_store_members($id, $rows);
        }
        $this->say('    medlemmer: ' . count($rows));
    }

    /* ------------------------------------------------------------------ Steps */

    public function step_people() {
        $page = $this->page('sta-styret');
        if (!$page) {
            return;
        }
        $data = $this->data($page);
        $grid = &$this->find($data, 'a695b8e');
        if ($grid === null || empty($grid['elements'])) {
            $this->say('! Fant ikke kortene på STA-styret (a695b8e) – allerede flyttet?');
            return;
        }
        if (strpos(wp_json_encode($grid), 'sta-loop') !== false) {
            $this->say('Kortene er allerede gjort om til en mal. Ingenting å gjøre.');
            return;
        }
        $this->say('STA-styret og Sekretariatet: ' . count($grid['elements']) . ' kort');
        $rows = array();
        $template = null;
        foreach ($grid['elements'] as $i => $card) {
            $p = $this->read_person_card($card);
            if ($p['name'] === '') {
                continue;
            }
            $this->say(sprintf('  %s – %s%s%s', $p['name'], $p['role'], $p['email'] ? ' – ' . $p['email'] : '', $p['phone'] ? ' – ' . $p['phone'] : ''));
            $id = $this->person($p, ($i + 1) * 10);
            $rows[] = array('person' => $id, 'role_no' => '', 'role_en' => '');
            if ($template === null && $p['phone'] !== '') {
                $template = $card;
            }
        }
        $this->group('sta-styret', 'STA-styret og Sekretariatet', $rows);
        $template = $template ?? $grid['elements'][0];
        $this->mark_person_card($template);
        $grid['elements'] = array($template);
        self::add_class($grid, 'sta-loop sta-src-group sta-arg-sta-styret');

        // Shared contact block: use STA Innstillinger values when they match the page text.
        $contact = sta_cms_contact_info();
        $block = &$this->find($data, 'f2f29af');
        if ($block !== null) {
            $map = array(
                'address_krs'  => '{{site_address_krs}}',
                'address_grim' => '{{site_address_grim}}',
                'email'        => '{{site_email}}',
                'phone'        => '{{site_phone}}',
                'org_number'   => '{{site_org_number}}',
            );
            $replaced = array();
            $this->each($block, function (&$e) use ($contact, $map, &$replaced) {
                foreach (array('editor') as $k) {
                    if (isset($e['settings'][$k])) {
                        foreach ($map as $field => $token) {
                            if (!empty($contact[$field]) && strpos($e['settings'][$k], $contact[$field]) !== false) {
                                $e['settings'][$k] = str_replace($contact[$field], $token, $e['settings'][$k]);
                                $replaced[] = $field;
                            }
                        }
                    }
                }
                foreach ($e['settings']['icon_list'] ?? array() as $n => $item) {
                    foreach ($map as $field => $token) {
                        if (!empty($contact[$field]) && strpos((string) $item['text'], $contact[$field]) !== false) {
                            $e['settings']['icon_list'][$n]['text'] = str_replace($contact[$field], $token, $item['text']);
                            $replaced[] = $field;
                        }
                    }
                }
            });
            $this->say('Kontaktboksen bruker nå STA Innstillinger for: ' . ($replaced ? implode(', ', array_unique($replaced)) : '(ingen felt matchet – uendret)'));
        }
        unset($grid, $block);
        $this->save_page($page, $data);
    }

    public function step_parliament() {
        $page = $this->page('studentparlamentet');
        if (!$page) {
            return;
        }
        $data = $this->data($page);
        if (strpos(wp_json_encode($data), 'sta-loop') !== false) {
            $this->say('Siden er allerede flyttet. Ingenting å gjøre.');
            return;
        }

        // 1. Ansvarlige for Studentparlamentet (people who are usually also on STA-styret).
        $grid = &$this->find($data, '996e865');
        if ($grid !== null && !empty($grid['elements'])) {
            $this->say('Ansvarlige for Studentparlamentet: ' . count($grid['elements']) . ' kort');
            $rows = array();
            foreach ($grid['elements'] as $i => $card) {
                $p = $this->read_person_card($card);
                if ($p['name'] === '') {
                    continue;
                }
                $this->say('  ' . $p['name'] . ' – ' . $p['role']);
                $id = $this->person($p, 100 + $i * 10);
                $person_role = $id > 0 ? (string) get_post_meta($id, '_sta_role_no', true) : $p['role'];
                $rows[] = array('person' => $id, 'role_no' => $p['role'] !== $person_role ? $p['role'] : '', 'role_en' => '');
            }
            $this->group('ansvarlige-studentparlamentet', 'Ansvarlige for Studentparlamentet', $rows);
            $template = $grid['elements'][0];
            $this->mark_person_card($template);
            $grid['elements'] = array($template);
            self::add_class($grid, 'sta-loop sta-src-group sta-arg-ansvarlige-studentparlamentet');
        } else {
            $this->say('! Fant ikke «Ansvarlige for Studentparlamentet» (996e865).');
        }
        unset($grid);

        // 2. Period from the page title («Studentparlamentet 2026/2027»).
        $period_name = '';
        foreach ($this->widgets(array('elements' => $data), 'heading') as $h) {
            if (preg_match('#(\d{4})\s*[/–-]\s*(\d{4})#u', self::text($h['settings']['title'] ?? ''), $m)) {
                $period_name = $m[1] . '/' . $m[2];
                break;
            }
        }
        $period_name = $period_name ?: wp_date('Y') . '/' . ((int) wp_date('Y') + 1);
        $period = $this->term('sta_period', $period_name, 0);
        $this->say('Periode: ' . $period_name);
        if ($this->run && $period > 0 && !get_option('sta_current_sta_period')) {
            update_option('sta_current_sta_period', $period);
        }

        // 3. Faculties with representatives.
        $grid = &$this->find($data, '4d23161');
        if ($grid === null || empty($grid['elements'])) {
            $this->say('! Fant ikke fakultetskortene (4d23161).');
            $this->save_page($page, $data);
            return;
        }
        $vacant_tpl = array('fast' => null, 'vara' => null);
        foreach ($grid['elements'] as $fi => $card) {
            $list = $this->widgets($card, 'icon-list');
            $faculty_name = self::text($list[0]['settings']['icon_list'][0]['text'] ?? '');
            if ($faculty_name === '') {
                continue;
            }
            $faculty = $this->term('sta_faculty', $faculty_name, ($fi + 1) * 10);
            if ($this->run && $faculty > 0) {
                update_term_meta($faculty, 'sta_order', ($fi + 1) * 10);
            }
            $cols = $this->rep_columns($card);
            $this->say($faculty_name . ': ' . count($cols['fast']['items'] ?? array()) . ' faste, ' . count($cols['vara']['items'] ?? array()) . ' vara');
            foreach (array('fast', 'vara') as $kind) {
                foreach ($cols[$kind]['items'] ?? array() as $ii => $item) {
                    $label  = self::text($this->widgets($item, 'heading')[0]['settings']['title'] ?? '');
                    $vacant = (bool) preg_match('/^Ledig/iu', $label);
                    $num    = $kind === 'vara' ? $ii + 1 : 0;
                    $name   = $label;
                    if (preg_match('/^(\d+)\.\s*vara:\s*(.+)$/iu', $label, $m)) {
                        $num  = (int) $m[1];
                        $name = trim($m[2]);
                    }
                    if ($vacant) {
                        $name = 'Ledig plass';
                        if ($vacant_tpl[$kind] === null) {
                            $vacant_tpl[$kind] = $item;
                        }
                    }
                    $meta = array('_sta_rep_type' => $kind === 'vara' ? 'substitute' : 'representative', '_sta_vara_number' => $kind === 'vara' ? (string) $num : '', '_sta_is_vacant' => $vacant ? '1' : '0');
                    $match = array('_sta_rep_type' => $meta['_sta_rep_type']);
                    if ($kind === 'vara') {
                        $match['_sta_vara_number'] = $meta['_sta_vara_number'];
                    }
                    $existing = $faculty > 0 && $period > 0 ? $this->find_post('sta_parliament_rep', $name, $match, array('sta_faculty' => $faculty, 'sta_period' => $period)) : 0;
                    if ($existing) {
                        $this->reused++;
                        continue;
                    }
                    $this->create('sta_parliament_rep', $name, $meta, ($ii + 1) * 10, array('sta_faculty' => $faculty, 'sta_period' => $period));
                }
            }
        }
        // Template = first faculty card; its lists become loops (with a «vacant» variant if one exists).
        $template = $grid['elements'][0];
        $cols = $this->rep_columns($template);
        $list = $this->widgets($template, 'icon-list');
        $faculty_name = self::text($list[0]['settings']['icon_list'][0]['text'] ?? '');
        $this->each($template, function (&$e) use ($cols, $faculty_name, $vacant_tpl) {
            if (($e['widgetType'] ?? '') === 'icon-list' && isset($e['settings']['icon_list'][0]['text'])) {
                $e['settings']['icon_list'][0]['text'] = str_replace($faculty_name, '{{faculty}}', $e['settings']['icon_list'][0]['text']);
            }
            foreach (array('fast', 'vara') as $kind) {
                if (empty($cols[$kind])) {
                    continue;
                }
                if (($e['id'] ?? '') === $cols[$kind]['heading']) {
                    $e['settings']['title'] = '{{label_' . $kind . '}}';
                }
                if (($e['id'] ?? '') === $cols[$kind]['column']) {
                    self::add_class($e, 'sta-hide-empty');
                }
                if (($e['id'] ?? '') === $cols[$kind]['list']) {
                    $items = $cols[$kind]['items'];
                    $normal = null;
                    foreach ($items as $it) {
                        if (!preg_match('/^Ledig/iu', self::text($this->widgets($it, 'heading')[0]['settings']['title'] ?? ''))) {
                            $normal = $it;
                            break;
                        }
                    }
                    $children = array($normal ?? $items[0]);
                    if ($vacant_tpl[$kind] !== null) {
                        $v = $vacant_tpl[$kind];
                        self::add_class($v, 'sta-tpl-vacant');
                        $children[] = $v;
                    }
                    foreach ($children as &$child) {
                        $this->each($child, function (&$c) {
                            if (($c['widgetType'] ?? '') === 'heading') {
                                $c['settings']['title'] = '{{label}}';
                            }
                        });
                    }
                    unset($child);
                    $e['elements'] = $children;
                    self::add_class($e, 'sta-loop sta-src-reps sta-arg-' . $kind);
                }
            }
        });
        $grid['elements'] = array($template);
        self::add_class($grid, 'sta-loop sta-src-faculties');
        unset($grid);
        $this->save_page($page, $data);
    }

    /** In a faculty card: the «Faste» and «Vara» columns (heading, list container and items). */
    private function rep_columns($card) {
        $cols = array();
        $grid = null;
        $this->each($card, function (&$e) use (&$grid) {
            if ($grid === null && ($e['elType'] ?? '') === 'container' && ($e['settings']['container_type'] ?? '') === 'grid') {
                $grid = $e;
            }
        });
        if ($grid === null) {
            return $cols;
        }
        foreach ($grid['elements'] as $col) {
            $heading = $this->widgets($col, 'heading')[0] ?? null;
            $title   = self::text($heading['settings']['title'] ?? '');
            $kind    = stripos($title, 'vara') !== false ? 'vara' : 'fast';
            $list    = null;
            foreach ($col['elements'] as $child) {
                if (($child['elType'] ?? '') === 'container') {
                    $list = $child;
                }
            }
            $cols[$kind] = array('column' => $col['id'], 'heading' => $heading['id'] ?? '', 'list' => $list['id'] ?? '', 'items' => $list['elements'] ?? array());
        }
        return $cols;
    }

    /**
     * Sections of cards (heading + grid) on Råd, styrer og utvalg and Ledige verv.
     * $grids = grid element IDs in page order. Returns [ ['grid'=>id, 'heading'=>text, 'section'=>id], … ].
     */
    private function sections($data, $grids) {
        $root = array('elements' => $data);
        $out  = array();
        foreach ($grids as $gid) {
            $section_id = $this->parent_id($root, $gid);
            $section = $section_id !== '' ? $this->find($root['elements'], $section_id) : null;
            $heading = '';
            foreach ($section['elements'] ?? array() as $child) {
                if (($child['widgetType'] ?? '') === 'heading') {
                    $heading = self::text($child['settings']['title'] ?? '');
                    break;
                }
            }
            $out[] = array('grid' => $gid, 'heading' => $heading, 'section' => $section_id);
        }
        return $out;
    }

    public function step_positions() {
        $page = $this->page('rad-styrer-og-utvalg');
        if (!$page) {
            return;
        }
        $data = $this->data($page);
        if (strpos(wp_json_encode($data), 'sta-loop') !== false) {
            $this->say('Siden er allerede flyttet. Ingenting å gjøre.');
            return;
        }
        $link_tpl = null;
        foreach ($this->sections($data, array('bb4a444', 'f8002a6', 'f2a37f4', '255ac87')) as $si => $sec) {
            $grid = &$this->find($data, $sec['grid']);
            if ($grid === null) {
                $this->say('! Fant ikke rutenettet ' . $sec['grid']);
                unset($grid);
                continue;
            }
            $term = $this->term('sta_committee_group', $sec['heading'], ($si + 1) * 10);
            if ($this->run && $term > 0) {
                update_term_meta($term, 'sta_order', ($si + 1) * 10);
            }
            $this->say($sec['heading'] . ': ' . count($grid['elements']) . ' kort');
            $template = null;
            foreach ($grid['elements'] as $ci => $card) {
                $headings = $this->widgets($card, 'heading');
                $title    = self::text($headings[0]['settings']['title'] ?? '');
                $short    = self::paragraphs($this->widgets($card, 'text-editor')[0]['settings']['editor'] ?? '');
                $toggle   = $this->widgets($card, 'toggle')[0] ?? null;
                $long     = $toggle ? (string) ($toggle['settings']['tabs'][0]['tab_content'] ?? '') : '';
                $link_url = '';
                $link_txt = '';
                foreach (array_slice($headings, 1) as $h) {
                    if (self::link_url($h['settings']) !== '') {
                        $link_url = self::link_url($h['settings']);
                        $link_txt = self::text($h['settings']['title'] ?? '');
                        if ($link_tpl === null) {
                            $link_tpl = $h;
                        }
                    }
                }
                if ($template === null && $toggle) {
                    $template = $card;
                }
                $this->say('  ' . $title . ($long !== '' ? ' (+ Les mer)' : '') . ($link_url !== '' ? ' (+ lenke)' : ''));
                $existing = $term > 0 ? $this->find_post('sta_position', $title, array(), array('sta_committee_group' => $term)) : 0;
                if ($existing) {
                    $this->reused++;
                    if ($this->run && get_post_meta($existing, '_sta_desc_no', true) !== $short) {
                        update_post_meta($existing, '_sta_desc_no', wp_slash($short));
                        $this->say('    (kort beskrivelse oppdatert fra siden)');
                    }
                    continue;
                }
                $this->create('sta_position', $title, array(
                    '_sta_desc_no'       => $short,
                    '_sta_long_no'       => wp_kses_post($long),
                    '_sta_link_url'      => $link_url,
                    '_sta_link_label_no' => $link_txt,
                ), ($ci + 1) * 10, array('sta_committee_group' => $term));
            }
            $template = $template ?? $grid['elements'][0];
            $this->mark_position_card($template, $link_tpl);
            $grid['elements'] = array($template);
            $slug = $term > 0 ? get_term($term)->slug : sanitize_title($sec['heading']);
            self::add_class($grid, 'sta-loop sta-src-positions sta-arg-' . $slug);
            unset($grid);
            if ($sec['section'] !== '') {
                $section = &$this->find($data, $sec['section']);
                self::add_class($section, 'sta-hide-empty');
                unset($section);
            }
        }
        $this->save_page($page, $data);
    }

    private function mark_position_card(array &$card, $link_tpl) {
        $headings = $this->widgets($card, 'heading');
        $title_id = $headings[0]['id'] ?? '';
        $short_id = $this->widgets($card, 'text-editor')[0]['id'] ?? '';
        $has_link = false;
        $this->each($card, function (&$e) use ($title_id, $short_id, &$has_link) {
            $id = $e['id'] ?? '';
            if ($id === $title_id) {
                $e['settings']['title'] = '{{title}}';
            } elseif ($id === $short_id) {
                $e['settings']['editor'] = '{{short_html}}';
            } elseif (($e['widgetType'] ?? '') === 'toggle') {
                $e['settings']['tabs'][0]['tab_content'] = '{{long_html}}';
                $e['settings']['tabs'] = array($e['settings']['tabs'][0]);
                self::add_class($e, 'sta-if-long');
            } elseif (($e['widgetType'] ?? '') === 'heading' && self::link_url($e['settings']) !== '') {
                $e['settings']['title'] = '{{link_label}}';
                $e['settings']['link']['url'] = '{{link_url}}';
                self::add_class($e, 'sta-if-link_url');
                $has_link = true;
            }
        });
        if (!$has_link && $link_tpl !== null) {
            $link = $link_tpl;
            $link['settings']['title'] = '{{link_label}}';
            $link['settings']['link']['url'] = '{{link_url}}';
            self::add_class($link, 'sta-if-link_url');
            $card['elements'][] = $link;
        }
    }

    public function step_vacancies() {
        $page = $this->page('ledige-verv');
        if (!$page) {
            return;
        }
        $data = $this->data($page);
        if (strpos(wp_json_encode($data), 'sta-loop') !== false) {
            $this->say('Siden er allerede flyttet. Ingenting å gjøre.');
            return;
        }
        $footers = array('internal' => null, 'external' => null);
        $parsed  = array();
        foreach ($this->sections($data, array('31efc54', 'f90fb5c', 'eabcdfa', '9baad71')) as $si => $sec) {
            $grid = $this->find($data, $sec['grid']);
            if ($grid === null) {
                $this->say('! Fant ikke rutenettet ' . $sec['grid']);
                continue;
            }
            $term = $this->term('sta_vacancy_category', $sec['heading'], ($si + 1) * 10);
            if ($this->run && $term > 0) {
                update_term_meta($term, 'sta_order', ($si + 1) * 10);
            }
            $this->say($sec['heading'] . ': ' . count($grid['elements']) . ' kort');
            foreach ($grid['elements'] as $ci => $card) {
                $title   = self::text($this->widgets($card, 'heading')[0]['settings']['title'] ?? '');
                $editors = $this->widgets($card, 'text-editor');
                $seats   = self::text($editors[0]['settings']['editor'] ?? '');
                $elig    = '';
                foreach ($editors as $ed) {
                    $t = self::text($ed['settings']['editor'] ?? '');
                    if (stripos($t, 'Kvalifikasjonskrav') === 0) {
                        $elig = trim(preg_replace('/^Kvalifikasjonskrav:?\s*/iu', '', $t));
                    }
                }
                $organ = self::text($this->widgets($card, 'icon-list')[0]['settings']['icon_list'][0]['text'] ?? '');
                $url   = '';
                $label = '';
                foreach (array_slice($card['elements'], 1) as $child) {
                    $button = $this->widgets($child, 'button')[0] ?? null;
                    if ($button) {
                        $url   = self::link_url($button['settings']);
                        $label = self::text($button['settings']['text'] ?? '');
                        $footers['internal'] = $footers['internal'] ?? $child;
                    } elseif (($u = $this->first_url($child)) !== '') {
                        $url   = $u;
                        $label = self::text($this->widgets($child, 'heading')[0]['settings']['title'] ?? '');
                        $footers['external'] = $footers['external'] ?? $child;
                    }
                }
                $this->say('  ' . $title . ' – ' . $seats . ($url ? ' – ' . $label . ' (' . $url . ')' : ''));
                $existing = $term > 0 ? $this->find_post('sta_vacancy', $title, array(), array('sta_vacancy_category' => $term)) : 0;
                if ($existing) {
                    $this->reused++;
                    continue;
                }
                $this->create('sta_vacancy', $title, array(
                    '_sta_seats_no'       => $seats,
                    '_sta_organ_no'       => $organ,
                    '_sta_eligibility_no' => $elig,
                    '_sta_link_url'       => $url,
                    '_sta_link_label_no'  => $label,
                ), ($ci + 1) * 10, array('sta_vacancy_category' => $term));
            }
            $parsed[] = array('sec' => $sec, 'term' => $term);
        }
        foreach ($parsed as $p) {
            $grid = &$this->find($data, $p['sec']['grid']);
            $template = $grid['elements'][0];
            $this->mark_vacancy_card($template, $footers);
            $grid['elements'] = array($template);
            $slug = $p['term'] > 0 ? get_term($p['term'])->slug : sanitize_title($p['sec']['heading']);
            self::add_class($grid, 'sta-loop sta-src-vacancies sta-arg-' . $slug);
            unset($grid);
            if ($p['sec']['section'] !== '') {
                $section = &$this->find($data, $p['sec']['section']);
                self::add_class($section, 'sta-hide-empty');
                unset($section);
            }
        }
        $this->save_page($page, $data);
    }

    /** Card body fields become placeholders; the footer gets both link styles (one is shown). */
    private function mark_vacancy_card(array &$card, $footers) {
        $title_id = $this->widgets($card, 'heading')[0]['id'] ?? '';
        $editors  = $this->widgets($card, 'text-editor');
        $seats_id = $editors[0]['id'] ?? '';
        $body = $card['elements'][0];
        $this->each($body, function (&$e) use ($title_id, $seats_id) {
            $id = $e['id'] ?? '';
            $type = $e['widgetType'] ?? '';
            if ($id === $title_id) {
                $e['settings']['title'] = '{{title}}';
            } elseif ($id === $seats_id) {
                $e['settings']['editor'] = '<p>{{seats}}</p>';
            } elseif ($type === 'text-editor' && stripos(self::text($e['settings']['editor'] ?? ''), 'Kvalifikasjonskrav') === 0) {
                $e['settings']['editor'] = preg_match('#^(.*?</strong>)#is', $e['settings']['editor'], $m) ? $m[1] . ' {{eligibility}}</p>' : '<p><strong>Kvalifikasjonskrav:</strong> {{eligibility}}</p>';
            } elseif ($type === 'icon-list' && isset($e['settings']['icon_list'][0])) {
                $e['settings']['icon_list'] = array($e['settings']['icon_list'][0]);
                $e['settings']['icon_list'][0]['text'] = '{{organ}}';
            }
        });
        $internal = null;
        $external = null;
        foreach (array_slice($card['elements'], 1) as $child) {
            if ($this->widgets($child, 'button')) {
                $internal = $child;
            } elseif ($this->first_url($child) !== '') {
                $external = $child;
            }
        }
        $internal = $internal ?? $footers['internal'];
        $external = $external ?? $footers['external'];
        $card['elements'] = array($body);
        if ($internal !== null) {
            $this->each($internal, function (&$e) {
                if (($e['widgetType'] ?? '') === 'button') {
                    $e['settings']['text'] = '{{link_label}}';
                    $e['settings']['link']['url'] = '{{link_url}}';
                }
            });
            self::add_class($internal, 'sta-if-link_internal');
            $card['elements'][] = $internal;
        }
        if ($external !== null) {
            $this->each($external, function (&$e) {
                if (self::link_url($e['settings'] ?? array()) !== '') {
                    $e['settings']['link']['url'] = '{{link_url}}';
                }
                if (($e['widgetType'] ?? '') === 'heading') {
                    $e['settings']['title'] = '{{link_label}}';
                }
            });
            self::add_class($external, 'sta-if-link_external');
            $card['elements'][] = $external;
        }
    }

    /**
     * In $root, turns the list holding the first link button into a loop: the list keeps one
     * item (the button, or the wrapper container around it) with placeholders.
     */
    private function mark_button_list(array &$root, $text_token, $classes) {
        $buttons = $this->widgets($root, 'button');
        if (!$buttons) {
            return;
        }
        $button_id = $buttons[0]['id'];
        $parent_id = $this->parent_id($root, $button_id);
        $parent    = $parent_id === ($root['id'] ?? '') ? $root : $this->find($root['elements'], $parent_id);
        $list_id   = $parent_id;
        $item_id   = $button_id;
        if ($parent && count($parent['elements']) === 1) {
            $list_id = $this->parent_id($root, $parent_id);
            $item_id = $parent_id;
        }
        $mark = function (&$list) use ($item_id, $text_token, $classes) {
            foreach ($list['elements'] as $child) {
                if (($child['id'] ?? '') === $item_id) {
                    $this->each($child, function (&$e) use ($text_token) {
                        if (($e['widgetType'] ?? '') === 'button') {
                            $e['settings']['text'] = $text_token;
                            $e['settings']['link']['url'] = '{{url}}';
                        }
                    });
                    $list['elements'] = array($child);
                    break;
                }
            }
            self::add_class($list, $classes);
        };
        if ($list_id === ($root['id'] ?? '')) {
            $mark($root);
            return;
        }
        $this->each($root, function (&$e) use ($list_id, $mark) {
            if (($e['id'] ?? '') === $list_id) {
                $mark($e);
            }
        });
    }

    /* ---------- Documents */

    private static function doc_date($title, $url, $year = '') {
        if (preg_match('/(\d{1,2})\.(\d{1,2})\.(\d{2,4})/', $title, $m)) {
            $y = strlen($m[3]) === 2 ? 2000 + (int) $m[3] : (int) $m[3];
            if (checkdate((int) $m[2], (int) $m[1], $y)) {
                return sprintf('%04d-%02d-%02d', $y, $m[2], $m[1]);
            }
        }
        if ($year !== '') {
            return $year . '-01-01';
        }
        if (preg_match('#/uploads/(\d{4})/(\d{2})/#', $url, $m)) {
            return $m[1] . '-' . $m[2] . '-01';
        }
        return wp_date('Y-m-d');
    }

    private function document($title, $url, $cat_term, $order, $desc = '', $year = '', $note = '') {
        $title = trim(preg_replace('/\s*\((PDF|DOCX?|XLSX?|PPTX?)\)\s*$/i', '', $title));
        if ($title === '') {
            return;
        }
        $date = self::doc_date($title, $url, $year);
        if ($year !== '' && substr($date, 0, 4) !== $year) {
            $this->say('  ! «' . $title . '» står under ' . $year . ', men datoen i tittelen er ' . $date . ' – bruker ' . $year . '-12-31.');
            $date = $year . '-12-31';
        }
        // Same file + same title = same document (links may be absolute or relative, staging or
        // live; titles may differ only in the kind of dash).
        $key  = preg_match('#(/wp-content/uploads/.+)$#', $url, $m) ? $m[1] : $url;
        $norm = function ($t) {
            // Same letters may be stored differently («å» as one character or «a» + ring).
            if (class_exists('Normalizer')) {
                $t = Normalizer::normalize($t, Normalizer::FORM_C);
            }
            $t = str_replace(array("a\u{030A}", "A\u{030A}"), array('å', 'Å'), $t);
            return mb_strtolower(preg_replace('/\s+/u', ' ', str_replace(array('–', '—', '&#8211;', '&nbsp;', "\u{00A0}"), array('-', '-', '-', ' ', ' '), $t)));
        };
        $seen = $key . '|' . $norm($title);
        if (isset($this->seen_files[$seen])) {
            $this->reused++;
            return;
        }
        $this->seen_files[$seen] = true;
        $existing = 0;
        $candidates = get_posts(array('post_type' => 'sta_document', 'post_status' => array('publish', 'draft', 'pending', 'private'), 'posts_per_page' => -1, 'tax_query' => array(array('taxonomy' => 'sta_document_category', 'field' => 'term_id', 'terms' => max(0, (int) $cat_term)))));
        foreach ($candidates as $c) {
            $file = (string) get_post_meta($c->ID, '_sta_file_url', true);
            if ($norm($c->post_title) === $norm($title) && ($key === '' ? $file === '' : strpos($file, $key) !== false)) {
                $existing = $c->ID;
                break;
            }
        }
        if ($existing) {
            $this->reused++;
            if ($this->run && $note !== '' && get_post_meta($existing, '_sta_note_no', true) !== $note) {
                update_post_meta($existing, '_sta_note_no', wp_slash($note));
                $this->say('    (merknad lagt til: ' . $title . ' ' . $note . ')');
            }
            if ($this->run && $order > 0 && (int) get_post_field('menu_order', $existing) !== (int) $order) {
                wp_update_post(array('ID' => $existing, 'menu_order' => (int) $order));
            }
            return;
        }
        $this->create('sta_document', $title, array(
            '_sta_file_url' => $url !== '' ? esc_url_raw($url) : '',
            '_sta_file_id'  => $url !== '' ? (string) attachment_url_to_postid($url) : '',
            '_sta_doc_date' => $date,
            '_sta_desc_no'  => $desc,
            '_sta_note_no'  => $note,
        ), $order, array('sta_document_category' => $cat_term));
    }

    /**
     * Documents from a text editor's HTML (Sakspapirer, Referater, Resolusjoner): year headings,
     * links (with any note after the link, e.g. «(digitalt Zoom-møte)») and lines without a link.
     */
    private function parse_doclist($html) {
        $out  = array();
        $year = '';
        $doc  = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8"?><div id="sta-root">' . $html . '</div>');
        libxml_clear_errors();
        $xp = new DOMXPath($doc);
        $text = function ($node) {
            return trim(preg_replace('/\s+/u', ' ', $node->textContent));
        };
        foreach ($xp->query('//h1|//h2|//h3|//h4|//h5|//h6|//p|//div[not(@id="sta-root")]|//li') as $node) {
            $name = strtolower($node->nodeName);
            if ($name[0] === 'h' && strlen($name) === 2) {
                if (preg_match('/\b(\d{4})\b/', $text($node), $y)) {
                    $year = $y[1];
                }
                continue;
            }
            // Only innermost blocks hold items; wrappers are skipped.
            if ($xp->query('.//p|.//div|.//li|.//h1|.//h2|.//h3|.//h4|.//h5|.//h6', $node)->length > 0) {
                continue;
            }
            $links = $xp->query('.//a[@href]', $node);
            if ($links->length === 0) {
                $line = $text($node);
                if ($line === '') {
                    continue;
                }
                if (preg_match('/^\d{4}$/', $line)) {
                    $year = $line;
                    continue;
                }
                $out[] = array('title' => $line, 'url' => '', 'year' => $year, 'note' => '');
                continue;
            }
            foreach ($links as $a) {
                // Note = text right after the link, up to the next link or line break.
                $note = '';
                for ($sib = $a->nextSibling; $sib; $sib = $sib->nextSibling) {
                    if ($sib->nodeType === XML_ELEMENT_NODE && in_array(strtolower($sib->nodeName), array('a', 'br'), true)) {
                        break;
                    }
                    $note .= $sib->textContent;
                }
                $out[] = array('title' => $text($a), 'url' => html_entity_decode($a->getAttribute('href')), 'year' => $year, 'note' => trim(preg_replace('/\s+/u', ' ', $note)));
            }
        }
        return $out;
    }

    public function step_documents() {
        $cats = array(
            'styringsdokumenter'   => 'Øvrige styringsdokumenter',
            'politiske-dokumenter' => 'Politiske dokumenter',
            'resolusjoner'         => 'Resolusjoner',
            'reglementer'          => 'Andre reglementer og retningslinjer',
            'sakspapirer'          => 'Sakspapirer',
            'referater'            => 'Referater fra Studentparlamentet',
        );
        $terms = array();
        $i = 0;
        foreach ($cats as $slug => $name) {
            $i++;
            $term = get_term_by('slug', $slug, 'sta_document_category');
            if ($term) {
                $terms[$slug] = (int) $term->term_id;
                continue;
            }
            if (!$this->run) {
                $terms[$slug] = -1;
                $this->say('+ kategori «' . $name . '» (' . $slug . ')');
                continue;
            }
            $r = wp_insert_term($name, 'sta_document_category', array('slug' => $slug));
            $terms[$slug] = is_wp_error($r) ? 0 : (int) $r['term_id'];
            if (!is_wp_error($r)) {
                update_term_meta($r['term_id'], 'sta_order', $i * 10);
                $this->say('+ kategori «' . $name . '»');
            }
        }

        // 1. Sakspapirer, Referater and Resolusjoner pages: one text editor with year headings + links.
        foreach (array('dokumenter/sakspapirer' => array('sakspapirer', false), 'dokumenter/referat-fra-moter-i-studentparlamentet' => array('referater', false), 'dokumenter/resolusjoner' => array('resolusjoner', true)) as $path => $conf) {
            list($slug, $strong) = $conf;
            $page = $this->page($path);
            if (!$page) {
                continue;
            }
            $data = $this->data($page);
            if (strpos(wp_json_encode($data), 'sta-html') !== false) {
                $this->say(get_the_title($page) . ': allerede flyttet.');
                continue;
            }
            $editor_id = '';
            $docs = array();
            foreach ($this->widgets(array('elements' => $data), 'text-editor') as $ed) {
                $list = $this->parse_doclist($ed['settings']['editor'] ?? '');
                if (count($list) > count($docs)) {
                    $docs = $list;
                    $editor_id = $ed['id'];
                }
            }
            $this->say(get_the_title($page) . ': ' . count($docs) . ' dokumenter');
            foreach ($docs as $n => $d) {
                $this->document($d['title'], $d['url'], $terms[$slug], $n + 1, '', $d['year'], $d['note']);
            }
            if ($editor_id !== '' && count($docs) > 2) {
                $ed = &$this->find($data, $editor_id);
                self::add_class($ed, 'sta-html sta-src-doclist sta-arg-' . $slug . ($strong ? ' sta-opt-strongyear' : ''));
                $ed['settings']['editor'] = '<p>Listen hentes automatisk fra STA-innhold → Dokumenter (kategori «' . $cats[$slug] . '»).</p>';
                unset($ed);
                $this->save_page($page, $data);
            }
        }

        // 2. Dokumenter page: two card grids, the latest resolutions and the list of regulations.
        $page = $this->page('dokumenter');
        if (!$page) {
            return;
        }
        $data = $this->data($page);
        if (strpos(wp_json_encode($data), 'sta-loop') !== false) {
            $this->say('Dokumenter: allerede flyttet.');
            return;
        }
        foreach (array('4fd2390' => 'styringsdokumenter', 'ef14141' => 'politiske-dokumenter') as $gid => $slug) {
            $grid = &$this->find($data, $gid);
            if ($grid === null) {
                $this->say('! Fant ikke kortene ' . $gid);
                unset($grid);
                continue;
            }
            $this->say($cats[$slug] . ': ' . count($grid['elements']) . ' kort');
            foreach ($grid['elements'] as $n => $card) {
                $title = self::text($this->widgets($card, 'heading')[0]['settings']['title'] ?? '');
                $desc  = self::text($this->widgets($card, 'text-editor')[0]['settings']['editor'] ?? '');
                $url   = $this->first_url($card, '#/uploads/#');
                $this->say('  ' . $title . ' – ' . wp_basename($url));
                $this->document($title, $url, $terms[$slug], ($n + 1) * 10, $desc);
            }
            $template = $grid['elements'][0];
            $headings = $this->widgets($template, 'heading');
            $title_id = $headings[0]['id'] ?? '';
            $desc_id  = $this->widgets($template, 'text-editor')[0]['id'] ?? '';
            $this->each($template, function (&$e) use ($title_id, $desc_id) {
                $id = $e['id'] ?? '';
                if ($id === $title_id) {
                    $e['settings']['title'] = '{{title}}';
                } elseif ($id === $desc_id) {
                    $e['settings']['editor'] = '<p>{{desc}}</p>';
                    self::add_class($e, 'sta-if-desc');
                } elseif (($e['widgetType'] ?? '') === 'heading' && stripos(self::text($e['settings']['title'] ?? ''), 'Last ned') === 0) {
                    $e['settings']['title'] = '{{download_label}}';
                }
                if (self::link_url($e['settings'] ?? array()) !== '') {
                    $e['settings']['link']['url'] = '{{url}}';
                }
            });
            $grid['elements'] = array($template);
            self::add_class($grid, 'sta-loop sta-src-documents sta-arg-' . $slug);
            unset($grid);
        }

        // Latest resolutions (year groups with buttons) and regulations (buttons).
        $root = array('elements' => $data);
        $year_boxes = array();
        $this->each($root, function (&$e) use (&$year_boxes) {
            if (($e['elType'] ?? '') !== 'container') {
                return;
            }
            $first = $e['elements'][0] ?? null;
            if ($first && ($first['widgetType'] ?? '') === 'heading' && preg_match('/^\d{4}$/', self::text($first['settings']['title'] ?? '')) && $this->widgets($e, 'button')) {
                $year_boxes[] = $e['id'];
            }
        });
        if ($year_boxes) {
            $years_parent_id = $this->parent_id($root, $year_boxes[0]);
            $years_parent = &$this->find($data, $years_parent_id);
            $count = 0;
            foreach ($year_boxes as $yb) {
                $box = $this->find($data, $yb);
                $year = self::text($box['elements'][0]['settings']['title']);
                foreach ($this->widgets($box, 'button') as $b) {
                    $this->document(self::text($b['settings']['text'] ?? ''), self::link_url($b['settings']), $terms['resolusjoner'], 0, '', $year);
                    $count++;
                }
            }
            $this->say('Siste resolusjoner på Dokumenter: ' . count($year_boxes) . ' år, ' . $count . ' lenker');
            $tpl = $this->find($data, $year_boxes[0]);
            $tpl['elements'][0]['settings']['title'] = '{{year}}';
            $this->mark_button_list($tpl, '{{title_format}}', 'sta-loop sta-src-documents');
            // Keep the other children of the parent (e.g. headings) and replace the year boxes with one template.
            $kept = array();
            $placed = false;
            foreach ($years_parent['elements'] as $child) {
                if (in_array($child['id'] ?? '', $year_boxes, true)) {
                    if (!$placed) {
                        $kept[] = '__LOOP__';
                        $placed = true;
                    }
                    continue;
                }
                $kept[] = $child;
            }
            if (count($kept) === 1) {
                $years_parent['elements'] = array($tpl);
                self::add_class($years_parent, 'sta-loop sta-src-document-years sta-arg-resolusjoner sta-limit-' . count($year_boxes));
            } else {
                // Wrap the year template in its own loop container so the siblings stay in place.
                $wrapper = array('id' => substr(md5('sta-years' . $years_parent_id), 0, 7), 'elType' => 'container', 'isInner' => true, 'settings' => array('content_width' => 'full', 'flex_direction' => 'column', 'padding' => array('unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true), 'css_classes' => 'sta-loop sta-src-document-years sta-arg-resolusjoner sta-limit-' . count($year_boxes)), 'elements' => array($tpl));
                $years_parent['elements'] = array_map(function ($c) use ($wrapper) {
                    return $c === '__LOOP__' ? $wrapper : $c;
                }, $kept);
                $this->say('  (årene er lagt i en egen beholder – sjekk avstanden visuelt)');
            }
            unset($years_parent);
        } else {
            $this->say('! Fant ikke årsgruppene med resolusjoner på Dokumenter.');
        }

        // Regulations: the buttons in the section with the heading «Andre reglementer og retningslinjer».
        $root = array('elements' => $data);
        $reg_section = '';
        foreach ($this->widgets($root, 'heading') as $h) {
            if (stripos(self::text($h['settings']['title'] ?? ''), 'Andre reglementer') === 0) {
                $reg_section = $this->parent_id($root, $h['id']);
            }
        }
        $section = $reg_section !== '' ? $this->find($data, $reg_section) : null;
        $buttons = $section ? $this->widgets($section, 'button') : array();
        if ($buttons) {
            $this->say('Andre reglementer og retningslinjer: ' . count($buttons) . ' lenker');
            foreach ($buttons as $n => $b) {
                $this->document(self::text($b['settings']['text'] ?? ''), self::link_url($b['settings']), $terms['reglementer'], ($n + 1) * 10);
            }
            $sec = &$this->find($data, $reg_section);
            $this->mark_button_list($sec, '{{title_format}}', 'sta-loop sta-src-documents sta-arg-reglementer');
            unset($sec);
        } else {
            $this->say('! Fant ikke listen «Andre reglementer og retningslinjer».');
        }
        $this->save_page($page, $data);
    }

    public function step_partners() {
        $page = $this->page('');
        if (!$page) {
            return;
        }
        $data = $this->data($page);
        $grid = &$this->find($data, 'part_grid_b2jssq6');
        if ($grid === null || empty($grid['elements'])) {
            $this->say('! Fant ikke samarbeidspartnerne på forsiden.');
            return;
        }
        if (strpos(wp_json_encode($grid), 'sta-loop') !== false) {
            $this->say('Allerede flyttet.');
            return;
        }
        $option = (array) get_option('sta_partner_logos', array());
        $this->say('Samarbeidspartnere: ' . count($grid['elements']) . ' kort');
        foreach ($grid['elements'] as $n => $card) {
            $name  = self::text($this->widgets($card, 'heading')[0]['settings']['title'] ?? '');
            $image = (array) ($this->widgets($card, 'image')[0]['settings']['image'] ?? array());
            // The logo and name widgets carry the link (the card's own link setting is not shown).
            $url   = self::link_url($this->widgets($card, 'heading')[0]['settings'] ?? array())
                ?: self::link_url($this->widgets($card, 'image')[0]['settings'] ?? array());
            $en    = '';
            foreach ($option as $p) {
                if (($p['name'] ?? '') === $name) {
                    $en  = (string) ($p['name_en'] ?? '');
                    $url = $url ?: (string) ($p['url'] ?? '');
                }
            }
            $logo  = (string) ($image['url'] ?? '');
            $this->say('  ' . $name . ' – logo: ' . wp_basename($logo) . ($url ? ' – ' . $url : ''));
            $existing = $this->find_post('sta_partner', $name);
            if ($existing) {
                $this->reused++;
                if ($this->run && $logo !== '' && get_post_meta($existing, '_sta_logo_url', true) === '') {
                    update_post_meta($existing, '_sta_logo_url', esc_url_raw($logo));
                }
                if ($this->run && $url !== '' && get_post_meta($existing, '_sta_website', true) === '') {
                    update_post_meta($existing, '_sta_website', esc_url_raw($url));
                }
                continue;
            }
            $this->create('sta_partner', $name, array('_sta_title_en' => $en, '_sta_website' => $url, '_sta_logo_url' => esc_url_raw($logo)), ($n + 1) * 10);
        }
        $template = $grid['elements'][0];
        $this->each($template, function (&$e) {
            if (($e['widgetType'] ?? '') === 'heading') {
                $e['settings']['title'] = '{{name}}';
            } elseif (($e['widgetType'] ?? '') === 'image') {
                self::add_class($e, 'sta-img-logo');
            }
            if (self::link_url($e['settings'] ?? array()) !== '') {
                $e['settings']['link']['url'] = '{{url}}';
            }
        });
        $grid['elements'] = array($template);
        self::add_class($grid, 'sta-loop sta-src-partners');
        unset($grid);
        $this->save_page($page, $data);
    }
}
