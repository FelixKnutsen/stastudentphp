<?php
/**
 * One-time import of the linjeforening and studentaktivitet lists from the STA theme's
 * bundled JSON files (sta-theme/data/associations.json and activities.json).
 *
 * The directory pages show these JSON lists only while the post types are empty. As soon as
 * one entry exists in wp-admin, only the wp-admin entries are shown, so the lists must be
 * imported before editors start adding entries. A button on the list screen does this; it is
 * shown only while the list is empty and skips entries whose slug already exists.
 */

if (!defined('ABSPATH')) {
    exit;
}

function sta_import_sources() {
    return array(
        'sta_association' => array('file' => 'associations.json', 'taxonomy' => 'sta_faculty', 'term_key' => 'faculty', 'term_en_key' => 'facultyEn', 'label' => 'linjeforeninger'),
        'sta_activity'    => array('file' => 'activities.json', 'taxonomy' => 'sta_activity_category', 'term_key' => 'category', 'term_en_key' => 'categoryEn', 'label' => 'studentaktiviteter'),
    );
}

function sta_import_read_json($file) {
    $path = trailingslashit(get_theme_root()) . 'sta-theme/data/' . $file;
    if (!file_exists($path)) {
        return array();
    }
    $data = json_decode(file_get_contents($path), true);
    return is_array($data) ? $data : array();
}

add_action('admin_notices', function () {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    $sources = sta_import_sources();
    if (!$screen || $screen->base !== 'edit' || !isset($sources[$screen->post_type]) || !current_user_can('manage_options')) {
        return;
    }
    $counts = wp_count_posts($screen->post_type);
    if ((int) $counts->publish + (int) $counts->draft + (int) $counts->pending > 0) {
        return;
    }
    $entries = sta_import_read_json($sources[$screen->post_type]['file']);
    if (!$entries) {
        return;
    }
    $url = wp_nonce_url(admin_url('admin-post.php?action=sta_import_directory&post_type=' . $screen->post_type), 'sta_import_directory');
    printf(
        '<div class="notice notice-info"><p>%s</p><p><a class="button button-primary" href="%s">%s</a></p></div>',
        esc_html(sprintf('Listen er tom. Nettsiden viser derfor de %d %s som følger med temaet. Importer dem hit først, så kan du redigere dem her.', count($entries), $sources[$screen->post_type]['label'])),
        esc_url($url),
        esc_html(sprintf('Importer %d %s', count($entries), $sources[$screen->post_type]['label']))
    );
});

add_action('admin_post_sta_import_directory', function () {
    if (!current_user_can('manage_options') || !check_admin_referer('sta_import_directory')) {
        wp_die(esc_html__('Ingen tilgang.', 'sta-core'));
    }
    $post_type = isset($_GET['post_type']) ? sanitize_key(wp_unslash($_GET['post_type'])) : '';
    $sources = sta_import_sources();
    if (!isset($sources[$post_type])) {
        wp_die('Ukjent liste.');
    }
    $source = $sources[$post_type];
    $created = 0;
    foreach (sta_import_read_json($source['file']) as $index => $entry) {
        $slug = sanitize_title($entry['id'] ?? ($entry['name'] ?? ''));
        if ($slug === '' || get_page_by_path($slug, OBJECT, $post_type)) {
            continue;
        }
        $post_id = wp_insert_post(array(
            'post_type'   => $post_type,
            'post_status' => 'publish',
            'post_title'  => sanitize_text_field($entry['name'] ?? $slug),
            'post_name'   => $slug,
            // Keep the order of the bundled list (editors can change «Rekkefølge»).
            'menu_order'  => ($index + 1) * 10,
        ), true);
        if (is_wp_error($post_id)) {
            continue;
        }
        $meta = array(
            '_sta_desc_no'       => sanitize_textarea_field($entry['description'] ?? ''),
            '_sta_desc_en'       => sanitize_textarea_field($entry['descriptionEn'] ?? ''),
            '_sta_email'         => sanitize_email($entry['email'] ?? ''),
            '_sta_website'       => esc_url_raw($entry['websiteUrl'] ?? ''),
            '_sta_facebook'      => esc_url_raw($entry['facebookUrl'] ?? ''),
            '_sta_instagram'     => esc_url_raw($entry['instagramUrl'] ?? ''),
            '_sta_discord'       => esc_url_raw($entry['discordUrl'] ?? ''),
            '_sta_logo_url'      => esc_url_raw($entry['logoUrl'] ?? ''),
            '_sta_image_url'     => esc_url_raw($entry['imageUrl'] ?? ''),
            '_sta_campus'        => sanitize_text_field($entry['campus'] ?? ''),
            '_sta_study_program' => sanitize_text_field($entry['studyProgram'] ?? ''),
        );
        foreach ($meta as $key => $value) {
            if ($value !== '') {
                update_post_meta($post_id, $key, $value);
            }
        }
        $term = sanitize_text_field($entry[$source['term_key']] ?? '');
        if ($term !== '') {
            $set = wp_set_object_terms($post_id, $term, $source['taxonomy']);
            // The English category name is kept in the term description (shown on English pages).
            $term_en = sanitize_text_field($entry[$source['term_en_key']] ?? '');
            if (!is_wp_error($set) && $set && $term_en !== '') {
                $term_obj = get_term((int) $set[0], $source['taxonomy']);
                if ($term_obj && !is_wp_error($term_obj) && $term_obj->description === '') {
                    wp_update_term($term_obj->term_id, $source['taxonomy'], array('description' => $term_en));
                }
            }
        }
        $created++;
    }
    wp_safe_redirect(add_query_arg(array('post_type' => $post_type, 'sta_imported' => $created), admin_url('edit.php')));
    exit;
});

add_action('admin_notices', function () {
    if (isset($_GET['sta_imported'])) {
        printf('<div class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html(sprintf('Importerte %d oppføringer.', (int) $_GET['sta_imported'])));
    }
});
