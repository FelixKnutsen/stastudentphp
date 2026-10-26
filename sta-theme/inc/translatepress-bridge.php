<?php
/**
 * TranslatePress bridge for the theme's own language routing.
 *
 * TranslatePress decides its language from the URL while plugins load. The theme routes
 * English with ?lang=en, the sta_lang cookie or a /en/ prefix it strips itself, so
 * TranslatePress would never see English. When the theme language is English we set
 * TranslatePress' language before its output buffer starts, so page content is translated
 * from the TranslatePress dictionary (Settings → TranslatePress → Translate Site).
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * TranslatePress language code used for English (e.g. en_GB), or '' if not configured.
 */
function sta_trp_english_code() {
    $settings = get_option('trp_settings');
    $languages = isset($settings['translation-languages']) ? (array) $settings['translation-languages'] : array();
    foreach ($languages as $code) {
        if (stripos($code, 'en') === 0) {
            return $code;
        }
    }
    return '';
}

add_action('init', function () {
    if (is_admin() || !class_exists('TRP_Translate_Press')) {
        return;
    }
    global $TRP_LANGUAGE;
    $code = sta_trp_english_code();
    if (sta_is_en() && $code) {
        $TRP_LANGUAGE = $code;
        return;
    }
    // Keep TranslatePress on the default language whenever the theme is not English.
    // Otherwise it wraps titles and gettext strings in its markers (trp-post-container,
    // #!trpst#trp-gettext) that are only removed when it translates the page, so they
    // would leak into Norwegian pages and the admin bar.
    $settings = get_option('trp_settings');
    if (!empty($settings['default-language'])) {
        $TRP_LANGUAGE = $settings['default-language'];
    }
}, -10);

/**
 * Admin-only endpoint to read and save TranslatePress translations in bulk.
 * POST /wp-json/sta/v1/translations
 *   {"mode":"list","originals":["..."]}                       → existing rows
 *   {"mode":"save","pairs":[{"original":"...","translated":"..."}]} → saved as human reviewed
 */
add_action('rest_api_init', function () {
    register_rest_route('sta/v1', '/translations', array(
        'methods'             => 'POST',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
        'callback'            => 'sta_rest_translations',
    ));
});

function sta_rest_translations(WP_REST_Request $request) {
    global $wpdb;
    if (!class_exists('TRP_Translate_Press')) {
        return new WP_Error('sta_no_trp', 'TranslatePress is not active.', array('status' => 400));
    }
    $code = sta_trp_english_code();
    if (!$code) {
        return new WP_Error('sta_no_en', 'English is not a TranslatePress language.', array('status' => 400));
    }
    $query = TRP_Translate_Press::get_trp_instance()->get_component('query');
    $table = $query->get_table_name($code);

    if ($request->get_param('mode') === 'save') {
        $pairs = array();
        foreach ((array) $request->get_param('pairs') as $pair) {
            $original   = isset($pair['original']) ? trim((string) $pair['original']) : '';
            $translated = isset($pair['translated']) ? trim((string) $pair['translated']) : '';
            if ($original !== '' && $translated !== '') {
                $pairs[$original] = $translated;
            }
        }
        if (!$pairs) {
            return array('saved' => 0, 'language' => $code);
        }
        $existing = $query->get_string_ids(array_keys($pairs), $code);
        $missing  = array_values(array_diff(array_keys($pairs), array_keys($existing)));
        if ($missing) {
            $query->insert_strings($missing, $code);
            $existing = $query->get_string_ids(array_keys($pairs), $code);
        }
        $update = array();
        foreach ($pairs as $original => $translated) {
            if (!isset($existing[$original])) {
                continue;
            }
            $row = $existing[$original];
            $update[] = array(
                'id'          => $row->id,
                'original'    => $original,
                'translated'  => $translated,
                'status'      => 2,
                'block_type'  => 0,
                'original_id' => isset($row->original_id) ? $row->original_id : null,
            );
        }
        $query->update_strings($update, $code, array('id', 'translated', 'status'));
        return array('saved' => count($update), 'language' => $code);
    }

    if ($request->get_param('mode') === 'maxid') {
        return array('language' => $code, 'max_id' => (int) $wpdb->get_var("SELECT MAX(id) FROM `{$table}`"));
    }
    if ($request->get_param('mode') === 'recent') {
        $after = (int) $request->get_param('after_id');
        $found = $wpdb->get_results($wpdb->prepare("SELECT id, original, translated, status FROM `{$table}` WHERE id > %d ORDER BY id LIMIT 3000", $after), ARRAY_A);
        return array('language' => $code, 'rows' => $found);
    }

    $originals = array_values(array_filter(array_map('strval', (array) $request->get_param('originals'))));
    $rows = array();
    foreach (array_chunk($originals, 200) as $chunk) {
        $placeholders = implode(',', array_fill(0, count($chunk), '%s'));
        $found = $wpdb->get_results($wpdb->prepare("SELECT original, translated, status FROM `{$table}` WHERE original IN ({$placeholders})", $chunk), ARRAY_A);
        foreach ($found as $row) {
            $rows[$row['original']] = array('translated' => $row['translated'], 'status' => (int) $row['status']);
        }
    }
    return array('language' => $code, 'table' => $table, 'rows' => $rows);
}
