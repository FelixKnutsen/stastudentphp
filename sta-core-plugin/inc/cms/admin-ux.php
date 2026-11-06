<?php
/**
 * Admin experience for STA content: one «STA-innhold» menu with an overview page, Norwegian
 * confirmation messages, drag-and-drop ordering, term fields (order, English name, current
 * period) and automatic cache clearing so changes show on the website straight away.
 */

if (!defined('ABSPATH')) {
    exit;
}

/* ---------------------------------------------------------------- Menu */

add_action('admin_menu', function () {
    add_menu_page('STA-innhold', 'STA-innhold', 'edit_posts', STA_CMS_MENU, 'sta_cms_render_overview', 'dashicons-groups', 3);
    add_submenu_page(STA_CMS_MENU, 'STA-innhold – oversikt', 'Oversikt', 'edit_posts', STA_CMS_MENU, 'sta_cms_render_overview');
    add_submenu_page(STA_CMS_MENU, 'Oversettelser', 'Oversettelser', 'edit_pages', 'sta-oversettelser', 'sta_tr_render_page');
    add_submenu_page(STA_CMS_MENU, 'Nyheter', 'Nyheter', 'edit_posts', 'edit.php');
    add_submenu_page(STA_CMS_MENU, 'Nyhetskategorier', '↳ Nyhetskategorier', 'manage_categories', 'edit-tags.php?taxonomy=category');
    foreach (sta_cms_taxonomies() as $tax => $def) {
        $first = reset($def['objects']);
        add_submenu_page(STA_CMS_MENU, $def['plural'], '↳ ' . ($def['menu'] ?? $def['plural']), 'manage_categories', 'edit-tags.php?taxonomy=' . $tax . '&post_type=' . $first);
    }
}, 9);

/** News lives under STA-innhold, so the separate «Innlegg» menu is not needed. */
add_action('admin_menu', function () {
    remove_menu_page('edit.php');
}, 999);

/** Keep «STA-innhold» highlighted on taxonomy screens. */
add_filter('parent_file', function ($parent) {
    $screen = get_current_screen();
    if ($screen && !empty($screen->taxonomy) && isset(sta_cms_taxonomies()[$screen->taxonomy])) {
        return STA_CMS_MENU;
    }
    if ($screen && $screen->post_type === 'post') {
        return STA_CMS_MENU;
    }
    return $parent;
});

function sta_cms_render_overview() {
    $sections = array(
        'Nyheter'             => array('post'),
        'Personer og grupper' => array('sta_board_member', 'sta_group'),
        'Studentdemokrati'    => array('sta_parliament_rep', 'sta_position', 'sta_vacancy'),
        'Organisasjoner'      => array('sta_association', 'sta_activity'),
        'Dokumenter og forside' => array('sta_document', 'sta_partner'),
    );
    $types = sta_cms_form_types();
    echo '<div class="wrap sta-cms-overview"><h1>STA-innhold</h1>';
    echo '<p class="about-description">Her endrer du innholdet på nettsiden uten å redigere sidene. Velg hva du vil endre. Du skriver bokmål, nynorsk og engelsk i samme skjema, og ser alt som mangler under <a href="' . esc_url(admin_url('admin.php?page=sta-oversettelser')) . '">Oversettelser</a>. Kontaktinformasjon i bunnteksten endrer du under <a href="' . esc_url(admin_url('admin.php?page=sta-settings')) . '">STA Innstillinger</a>.</p>';
    foreach ($sections as $title => $list) {
        echo '<h2>' . esc_html($title) . '</h2><div class="sta-cms-cards">';
        foreach ($list as $type) {
            $def    = $types[$type];
            $counts = wp_count_posts($type);
            printf(
                '<div class="sta-cms-card"><h3><span class="dashicons %1$s"></span> %2$s</h3><p>%3$s</p><p class="sta-cms-count">%4$d publisert%5$s</p><p><a class="button button-primary" href="%6$s">Se og endre</a> <a class="button" href="%7$s">+ Legg til</a></p><p class="sta-cms-pages">Vises på: %8$s</p></div>',
                esc_attr($def['icon']),
                esc_html($def['plural']),
                esc_html($def['intro']),
                (int) $counts->publish,
                (int) $counts->draft ? ', ' . (int) $counts->draft . ' utkast' : '',
                esc_url(admin_url('edit.php?post_type=' . $type)),
                esc_url(admin_url('post-new.php?post_type=' . $type)),
                implode(', ', sta_cms_page_links($type)) // Escaped in sta_cms_page_links().
            );
        }
        echo '</div>';
    }
    echo '<h2>Sideinnhold som ikke er lister</h2><p>Overskrifter, ingresser og bilder på hver side endres i Elementor: åpne siden under <a href="' . esc_url(admin_url('edit.php?post_type=page')) . '">Sider</a> og velg «Rediger med Elementor». Kort som viser <code>{{…}}</code> i Elementor er maler som fylles med innholdet herfra – endre innholdet her, ikke i malen. Oversett tekst fra Elementor under <a href="' . esc_url(admin_url('admin.php?page=sta-oversettelser')) . '">Oversettelser</a>.</p>';
    echo '</div>';
}

/* ---------------------------------------------------------------- Messages */

add_filter('post_updated_messages', function ($messages) {
    foreach (sta_cms_form_types() as $type => $def) {
        $name  = ucfirst($def['singular']);
        $links = sta_cms_page_links($type);
        $see   = $links ? ' Se: ' . implode(', ', $links) : '';
        $messages[$type] = array(
            0  => '',
            1  => $name . ' er oppdatert. Endringene vises nå på nettsiden.' . $see,
            2  => 'Feltet er oppdatert.',
            3  => 'Feltet er slettet.',
            4  => $name . ' er oppdatert.',
            5  => 'Gjenopprettet fra en tidligere versjon.',
            6  => $name . ' er publisert og vises nå på nettsiden.' . $see,
            7  => $name . ' er lagret.',
            8  => $name . ' er sendt til godkjenning.',
            9  => $name . ' er planlagt publisert.',
            10 => 'Utkastet er lagret. Det vises ikke på nettsiden før du trykker «Publiser».',
        );
    }
    return $messages;
});

add_filter('bulk_post_updated_messages', function ($messages, $counts) {
    foreach (sta_cms_form_types() as $type => $def) {
        $messages[$type] = array(
            'updated'   => _n('%s oppføring oppdatert.', '%s oppføringer oppdatert.', $counts['updated']),
            'locked'    => _n('%s oppføring ble ikke endret fordi noen andre redigerer den.', '%s oppføringer ble ikke endret fordi noen andre redigerer dem.', $counts['locked']),
            'deleted'   => _n('%s oppføring slettet permanent.', '%s oppføringer slettet permanent.', $counts['deleted']),
            'trashed'   => _n('%s oppføring flyttet til papirkurven og fjernet fra nettsiden. Den kan gjenopprettes fra Papirkurv.', '%s oppføringer flyttet til papirkurven og fjernet fra nettsiden. De kan gjenopprettes fra Papirkurv.', $counts['trashed']),
            'untrashed' => _n('%s oppføring gjenopprettet (som utkast – publiser for å vise den igjen).', '%s oppføringer gjenopprettet (som utkast – publiser for å vise dem igjen).', $counts['untrashed']),
        );
    }
    return $messages;
}, 10, 2);

/* ---------------------------------------------------------------- List screens */

/** Default list order = website order (Rekkefølge, then title). */
add_action('pre_get_posts', function ($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    $type = $query->get('post_type');
    if (is_string($type) && isset(sta_cms_types()[$type]) && !$query->get('orderby')) {
        if ($type === 'sta_document') {
            $query->set('meta_key', '_sta_doc_date');
            $query->set('orderby', array('menu_order' => 'ASC', 'meta_value' => 'DESC', 'title' => 'ASC'));
        } else {
            $query->set('orderby', array('menu_order' => 'ASC', 'title' => 'ASC'));
        }
    }
});

foreach (array_keys(sta_cms_types()) as $sta_cms_type) {
    add_filter('edit_' . $sta_cms_type . '_per_page', function ($per_page) {
        return max((int) $per_page, 100);
    });
    add_filter('manage_' . $sta_cms_type . '_posts_columns', function ($columns) use ($sta_cms_type) {
        $new = array();
        foreach ($columns as $key => $label) {
            if ($key === 'cb') {
                $new[$key] = $label;
                $new['sta_order'] = '<span title="Dra og slipp for å endre rekkefølgen">☰</span>';
                continue;
            }
            if ($key === 'date') {
                continue;
            }
            $new[$key] = $label;
            if ($key === 'title') {
                $new = array_merge($new, sta_cms_extra_columns($sta_cms_type));
            }
        }
        $new['sta_status'] = 'Synlig på nettsiden';
        $new['sta_lang']   = 'Språk';
        return $new;
    });
    add_action('manage_' . $sta_cms_type . '_posts_custom_column', function ($column, $post_id) {
        sta_cms_render_column($column, $post_id);
    }, 10, 2);
}
unset($sta_cms_type);

function sta_cms_extra_columns($type) {
    switch ($type) {
        case 'sta_board_member':
            return array('sta_role' => 'Rolle', 'sta_groups' => 'Grupper');
        case 'sta_group':
            return array('sta_member_count' => 'Medlemmer');
        case 'sta_parliament_rep':
            return array('sta_rep' => 'Type');
        case 'sta_document':
            return array('sta_doc_date' => 'Dato', 'sta_file' => 'Fil');
    }
    return array();
}

function sta_cms_render_column($column, $post_id) {
    switch ($column) {
        case 'sta_lang':
            sta_tr_render_list_status($post_id);
            break;
        case 'sta_order':
            echo '<span class="sta-cms-drag" title="Dra for å endre rekkefølgen">☰</span><span class="screen-reader-text">' . (int) get_post_field('menu_order', $post_id) . '</span>';
            break;
        case 'sta_status':
            $status = get_post_status($post_id);
            echo $status === 'publish' ? '<span class="sta-cms-pill is-on">Ja</span>' : '<span class="sta-cms-pill">Nei (' . esc_html(get_post_status_object($status)->label ?? $status) . ')</span>';
            break;
        case 'sta_role':
            echo esc_html(get_post_meta($post_id, '_sta_role_no', true));
            break;
        case 'sta_groups':
            $groups = get_posts(array('post_type' => array('sta_group', 'sta_position'), 'post_status' => 'any', 'posts_per_page' => -1, 'meta_key' => '_sta_member_ids', 'meta_value' => (string) $post_id, 'fields' => 'ids'));
            echo esc_html(implode(', ', array_map('get_the_title', $groups)) ?: '—');
            break;
        case 'sta_member_count':
            echo (int) count((array) get_post_meta($post_id, '_sta_members', true));
            break;
        case 'sta_rep':
            $type = get_post_meta($post_id, '_sta_rep_type', true) === 'substitute' ? (int) get_post_meta($post_id, '_sta_vara_number', true) . '. vara' : 'Fast';
            echo esc_html($type . (get_post_meta($post_id, '_sta_is_vacant', true) === '1' ? ' (ledig)' : ''));
            break;
        case 'sta_doc_date':
            $date = get_post_meta($post_id, '_sta_doc_date', true);
            echo esc_html($date ? date_i18n('j. M Y', strtotime($date)) : '—');
            break;
        case 'sta_file':
            $url = get_post_meta($post_id, '_sta_file_url', true);
            echo $url ? '<a href="' . esc_url($url) . '" target="_blank" rel="noopener">' . esc_html(wp_basename($url)) . '</a>' : '—';
            break;
    }
}

/** Drag-and-drop ordering on list screens (saves «Rekkefølge»). */
add_action('wp_ajax_sta_cms_reorder', function () {
    check_ajax_referer('sta_cms_reorder', 'nonce');
    $ids = array_map('absint', (array) ($_POST['ids'] ?? array()));
    $ids = array_values(array_filter($ids));
    if (!$ids) {
        wp_send_json_error('Ingen rader.');
    }
    $type = get_post_type($ids[0]);
    if (!isset(sta_cms_types()[$type])) {
        wp_send_json_error('Ukjent type.');
    }
    $orders = array();
    foreach ($ids as $id) {
        if (get_post_type($id) !== $type || !current_user_can('edit_post', $id)) {
            wp_send_json_error('Ingen tilgang.');
        }
        $orders[] = (int) get_post_field('menu_order', $id);
    }
    sort($orders);
    // Reuse the existing numbers so rows on other pages keep their place; renumber if they collide.
    if (count(array_unique($orders)) !== count($orders) || $orders[0] === 0) {
        $start  = max(0, (int) ($_POST['offset'] ?? 0));
        $orders = array();
        foreach ($ids as $i => $id) {
            $orders[] = ($start + $i + 1) * 10;
        }
    }
    foreach ($ids as $i => $id) {
        wp_update_post(array('ID' => $id, 'menu_order' => $orders[$i]));
    }
    wp_send_json_success(array('saved' => count($ids)));
});

/* ---------------------------------------------------------------- Term fields */

foreach (array_keys(sta_cms_taxonomies()) as $sta_cms_tax) {
    add_action($sta_cms_tax . '_add_form_fields', function () use ($sta_cms_tax) {
        wp_nonce_field('sta_cms_term', 'sta_cms_term_nonce');
        echo '<div class="form-field"><label for="sta_name_nn">Nynorsk navn</label><input type="text" id="sta_name_nn" name="sta_name_nn"><p>Vises på nynorske sider. Valgfritt.</p></div>';
        echo '<div class="form-field"><label for="sta_name_en">Engelsk navn</label><input type="text" id="sta_name_en" name="sta_name_en"><p>Vises på engelske sider. Valgfritt.</p></div>';
        echo '<div class="form-field"><label for="sta_order">Rekkefølge</label><input type="number" id="sta_order" name="sta_order" value="0" style="width:100px"><p>Lavere tall vises først på nettsiden.</p></div>';
        if (!empty(sta_cms_taxonomies()[$sta_cms_tax]['current_flag'])) {
            echo '<div class="form-field"><label><input type="checkbox" name="sta_current" value="1"> Vis på nettsiden</label><p>Perioden som vises på Studentparlamentet-siden.</p></div>';
        }
    });
    add_action($sta_cms_tax . '_edit_form_fields', function ($term) use ($sta_cms_tax) {
        wp_nonce_field('sta_cms_term', 'sta_cms_term_nonce');
        printf('<tr class="form-field"><th><label for="sta_name_nn">Nynorsk navn</label></th><td><input type="text" id="sta_name_nn" name="sta_name_nn" value="%s"><p class="description">Vises på nynorske sider. Valgfritt.</p></td></tr>', esc_attr(sta_cms_term_name_nn($term)));
        printf('<tr class="form-field"><th><label for="sta_name_en">Engelsk navn</label></th><td><input type="text" id="sta_name_en" name="sta_name_en" value="%s"><p class="description">Vises på engelske sider. Valgfritt.</p></td></tr>', esc_attr(sta_cms_term_name_en($term)));
        printf('<tr class="form-field"><th><label for="sta_order">Rekkefølge</label></th><td><input type="number" id="sta_order" name="sta_order" value="%d" style="width:100px"><p class="description">Lavere tall vises først på nettsiden.</p></td></tr>', (int) get_term_meta($term->term_id, 'sta_order', true));
        if (!empty(sta_cms_taxonomies()[$sta_cms_tax]['current_flag'])) {
            printf('<tr class="form-field"><th>Vis på nettsiden</th><td><label><input type="checkbox" name="sta_current" value="1" %s> Denne perioden vises på Studentparlamentet-siden</label></td></tr>', checked(get_option('sta_current_' . $sta_cms_tax), $term->term_id, false));
        }
    });
    $save = function ($term_id) use ($sta_cms_tax) {
        if (empty($_POST['sta_cms_term_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['sta_cms_term_nonce'])), 'sta_cms_term') || !current_user_can('manage_categories')) {
            return;
        }
        if (isset($_POST['sta_name_nn'])) {
            update_term_meta($term_id, 'sta_name_nn', sanitize_text_field(wp_unslash($_POST['sta_name_nn'])));
        }
        if (isset($_POST['sta_name_en'])) {
            update_term_meta($term_id, 'sta_name_en', sanitize_text_field(wp_unslash($_POST['sta_name_en'])));
        }
        if (isset($_POST['sta_order'])) {
            update_term_meta($term_id, 'sta_order', (int) $_POST['sta_order']);
        }
        if (!empty(sta_cms_taxonomies()[$sta_cms_tax]['current_flag'])) {
            if (!empty($_POST['sta_current'])) {
                update_option('sta_current_' . $sta_cms_tax, (int) $term_id);
            } elseif ((int) get_option('sta_current_' . $sta_cms_tax) === (int) $term_id) {
                delete_option('sta_current_' . $sta_cms_tax);
            }
        }
    };
    add_action('created_' . $sta_cms_tax, $save);
    add_action('edited_' . $sta_cms_tax, $save);
    add_filter('manage_edit-' . $sta_cms_tax . '_columns', function ($columns) use ($sta_cms_tax) {
        unset($columns['description']);
        $columns['sta_name_nn'] = 'Nynorsk navn';
        $columns['sta_name_en'] = 'Engelsk navn';
        $columns['sta_order']   = 'Rekkefølge';
        if (!empty(sta_cms_taxonomies()[$sta_cms_tax]['current_flag'])) {
            $columns['sta_current'] = 'Vises';
        }
        return $columns;
    });
    add_filter('manage_' . $sta_cms_tax . '_custom_column', function ($out, $column, $term_id) use ($sta_cms_tax) {
        if ($column === 'sta_name_nn') {
            return esc_html(sta_cms_term_name_nn(get_term($term_id)));
        }
        if ($column === 'sta_name_en') {
            return esc_html(sta_cms_term_name_en(get_term($term_id)));
        }
        if ($column === 'sta_order') {
            return (string) (int) get_term_meta($term_id, 'sta_order', true);
        }
        if ($column === 'sta_current') {
            return (int) get_option('sta_current_' . $sta_cms_tax) === (int) $term_id ? '<span class="sta-cms-pill is-on">Ja</span>' : '';
        }
        return $out;
    }, 10, 3);
}
unset($sta_cms_tax);

/* ---------------------------------------------------------------- Assets */

add_action('admin_enqueue_scripts', function ($hook) {
    $screen = get_current_screen();
    if (!$screen) {
        return;
    }
    $is_sta = sta_cms_form_def($screen->post_type) || (isset($screen->taxonomy) && isset(sta_cms_taxonomies()[$screen->taxonomy])) || $screen->id === 'toplevel_page_' . STA_CMS_MENU;
    if (!$is_sta) {
        return;
    }
    if ($screen->base === 'post') {
        wp_enqueue_media();
    }
    wp_enqueue_style('sta-cms-admin', STA_CORE_URL . 'assets/admin.css', array(), STA_CORE_VERSION);
    wp_enqueue_script('sta-cms-admin', STA_CORE_URL . 'assets/admin.js', array('jquery', 'jquery-ui-sortable'), STA_CORE_VERSION, true);
    wp_localize_script('sta-cms-admin', 'staCms', array(
        'ajax'     => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('sta_cms_reorder'),
        'screen'   => $screen->base,
        'sortable' => $screen->base === 'edit' && empty($_GET['orderby']) && empty($_GET['s']) && (empty($_GET['post_status']) || $_GET['post_status'] === 'all'), // phpcs:ignore WordPress.Security.NonceVerification
        'offset'   => max(0, ((int) ($_GET['paged'] ?? 1) - 1)) * 100, // phpcs:ignore WordPress.Security.NonceVerification
    ));
});

/* ---------------------------------------------------------------- Cache */

/**
 * Pages show STA content through Elementor and are cached (Elementor's element cache and the
 * WP-Optimize page cache). Clear both whenever STA content changes, once per request.
 */
function sta_cms_schedule_purge() {
    static $scheduled = false;
    if ($scheduled) {
        return;
    }
    $scheduled = true;
    add_action('shutdown', 'sta_cms_purge_caches');
}

function sta_cms_purge_caches() {
    delete_post_meta_by_key('_elementor_element_cache');
    if (function_exists('wpo_cache_flush')) {
        wpo_cache_flush();
    } elseif (function_exists('WP_Optimize') && is_callable(array(WP_Optimize(), 'get_page_cache'))) {
        WP_Optimize()->get_page_cache()->purge();
    }
    do_action('sta_cms_purged');
}

add_action('save_post', function ($post_id, $post) {
    if (sta_cms_form_def($post->post_type) && !wp_is_post_revision($post_id) && !(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)) {
        sta_cms_schedule_purge();
    }
}, 99, 2);
foreach (array('trashed_post', 'untrashed_post', 'before_delete_post') as $sta_cms_hook) {
    add_action($sta_cms_hook, function ($post_id) {
        if (sta_cms_form_def(get_post_type($post_id))) {
            sta_cms_schedule_purge();
        }
    });
}
foreach (array('created_term', 'edited_term', 'delete_term') as $sta_cms_hook) {
    add_action($sta_cms_hook, function ($term_id, $tt_id, $taxonomy) {
        if (isset(sta_cms_taxonomies()[$taxonomy])) {
            sta_cms_schedule_purge();
        }
    }, 10, 3);
}
unset($sta_cms_hook);
