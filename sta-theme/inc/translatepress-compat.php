<?php
/**
 * TranslatePress & Multilingual Compatibility Helpers
 * 
 * Default language: Norwegian ('no')
 * Secondary language: English ('en')
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Detect current active language ('no' or 'en')
 * Default is ALWAYS 'no' (Norwegian) unless English is explicitly requested.
 */
function sta_get_current_language() {
    // 1. Check TranslatePress if active
    if (function_exists('trp_get_current_language')) {
        $trp_lang = trp_get_current_language();
        if (strpos(strtolower($trp_lang), 'en') === 0) {
            return 'en';
        }
        return 'no';
    }

    // 2. Check query parameter ?lang=en or ?lang=no
    if (isset($_GET['lang'])) {
        $q_lang = strtolower(sanitize_text_field(wp_unslash($_GET['lang'])));
        if ($q_lang === 'en') {
            return 'en';
        }
        if ($q_lang === 'no' || $q_lang === 'nb') {
            return 'no';
        }
    }

    // 3. Check cookie
    if (isset($_COOKIE['sta_lang'])) {
        $cookie_lang = strtolower(sanitize_text_field(wp_unslash($_COOKIE['sta_lang'])));
        if ($cookie_lang === 'en') {
            return 'en';
        }
        if ($cookie_lang === 'no' || $cookie_lang === 'nb') {
            return 'no';
        }
    }

    // 4. Check URL path for /en/ or /en
    if (isset($_SERVER['REQUEST_URI'])) {
        $uri = sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI']));
        if (preg_match('#^/en(/|\?|$)#i', $uri)) {
            return 'en';
        }
    }

    // Default to Norwegian (Bokmål) - STA official language
    return 'no';
}

/**
 * Check if active language is English
 */
function sta_is_en() {
    return sta_get_current_language() === 'en';
}

/**
 * Return Norwegian or English text based on active language
 */
function sta_t($no_text, $en_text) {
    return sta_is_en() ? $en_text : $no_text;
}

/**
 * Helper to build language-aware internal URL
 */
function sta_get_localized_url($path = '/') {
    $path = '/' . ltrim($path, '/');
    if (sta_is_en()) {
        // Return URL with lang parameter or /en/ prefix
        return home_url($path . (strpos($path, '?') !== false ? '&lang=en' : '?lang=en'));
    }
    return home_url($path);
}

/**
 * Get opposite language URL for switcher button
 */
function sta_get_toggle_language_url() {
    $current_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '/';
    $target_lang = sta_is_en() ? 'no' : 'en';

    // Remove existing lang query param
    $clean_uri = remove_query_arg('lang', $current_uri);

    // If switching to Norwegian from /en/ path
    if ($target_lang === 'no') {
        $clean_uri = preg_replace('#^/en(/|$)#i', '/', $clean_uri);
        return add_query_arg('lang', 'no', $clean_uri);
    } else {
        return add_query_arg('lang', 'en', $clean_uri);
    }
}

/**
 * Ensure <html> tag always reflects active language
 */
add_filter('language_attributes', function ($output) {
    if (sta_is_en()) {
        return 'lang="en"';
    }
    return 'lang="nb-NO"';
});

/**
 * Filter WordPress locale to Norwegian by default or English when active
 */
add_filter('locale', function ($locale) {
    if (sta_is_en()) {
        return 'en_US';
    }
    return 'nb_NO';
});

/**
 * Persist language selection in cookie
 */
add_action('init', function () {
    if (isset($_GET['lang']) && !headers_sent()) {
        $q_lang = strtolower(sanitize_text_field(wp_unslash($_GET['lang'])));
        if ($q_lang === 'en' || $q_lang === 'no' || $q_lang === 'nb') {
            $val = ($q_lang === 'nb') ? 'no' : $q_lang;
            setcookie('sta_lang', $val, time() + (86400 * 30), defined('COOKIEPATH') ? COOKIEPATH : '/');
            $_COOKIE['sta_lang'] = $val;
        }
    }
});
