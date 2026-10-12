<?php
/**
 * TranslatePress & Multilingual Compatibility Helpers
 * 
 * Default language: Norsk bokmål ('no')
 * Secondary language: English ('en')
 * Third language: Norsk nynorsk ('nn')
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Detect current active language ('no', 'en', or 'nn')
 * Default is ALWAYS 'no' (Norsk bokmål) unless English or Nynorsk is explicitly requested.
 */
function sta_get_current_language() {
    // 1. Check TranslatePress if active
    if (function_exists('trp_get_current_language')) {
        $trp_lang = strtolower(trp_get_current_language());
        if (strpos($trp_lang, 'en') === 0) {
            return 'en';
        }
        if (strpos($trp_lang, 'nn') === 0) {
            return 'nn';
        }
        return 'no';
    }

    // 2. Check query parameter ?lang=en, ?lang=nn, or ?lang=no
    if (isset($_GET['lang'])) {
        $q_lang = strtolower(sanitize_text_field(wp_unslash($_GET['lang'])));
        if ($q_lang === 'en') {
            return 'en';
        }
        if ($q_lang === 'nn') {
            return 'nn';
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
        if ($cookie_lang === 'nn') {
            return 'nn';
        }
        if ($cookie_lang === 'no' || $cookie_lang === 'nb') {
            return 'no';
        }
    }

    // 4. Check URL path for /en/ or /nn/
    if (isset($_SERVER['REQUEST_URI'])) {
        $uri = sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI']));
        if (preg_match('#^/en(/|\?|$)#i', $uri)) {
            return 'en';
        }
        if (preg_match('#^/nn(/|\?|$)#i', $uri)) {
            return 'nn';
        }
    }

    // Default to Norwegian Bokmål ('no')
    return 'no';
}

/**
 * Check if active language is English
 */
function sta_is_en() {
    return sta_get_current_language() === 'en';
}

/**
 * Check if active language is Nynorsk
 */
function sta_is_nn() {
    return sta_get_current_language() === 'nn';
}

/**
 * Check if active language is Bokmål (default)
 */
function sta_is_no() {
    return sta_get_current_language() === 'no';
}

/**
 * Return Norwegian Bokmål, English, or Nynorsk text based on active language
 */
function sta_t($no_text, $en_text, $nn_text = null) {
    if (sta_is_nn()) {
        return ($nn_text !== null && $nn_text !== '') ? $nn_text : $no_text;
    }
    if (sta_is_en()) {
        return $en_text;
    }
    return $no_text;
}

/**
 * Helper to build language-aware internal URL
 */
function sta_get_localized_url($path = '/', $target_lang = null) {
    $path = '/' . ltrim($path, '/');
    $lang = $target_lang ?: sta_get_current_language();

    if ($lang === 'en') {
        return home_url('/en' . ($path === '/' ? '/' : $path));
    }
    if ($lang === 'nn') {
        return home_url('/nn' . ($path === '/' ? '/' : $path));
    }
    return home_url($path);
}

/**
 * Get target language URL for switcher (retains current subpath)
 */
function sta_get_language_url($target_lang = 'no') {
    $current_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '/';
    $clean_uri = remove_query_arg('lang', $current_uri);

    $path = parse_url($clean_uri, PHP_URL_PATH);
    if (!$path) $path = '/';
    $query = parse_url($clean_uri, PHP_URL_QUERY);
    $query_str = $query ? '?' . $query : '';

    // Strip any existing language prefix (/en or /nn)
    $clean_path = preg_replace('#^/(en|nn)(/|$)#i', '/', $path);
    if (!$clean_path) $clean_path = '/';

    if ($target_lang === 'en') {
        return home_url('/en' . ($clean_path === '/' ? '/' : ('/' . ltrim($clean_path, '/'))) . $query_str);
    }
    if ($target_lang === 'nn') {
        return home_url('/nn' . ($clean_path === '/' ? '/' : ('/' . ltrim($clean_path, '/'))) . $query_str);
    }
    return home_url($clean_path . $query_str);
}

/**
 * Backward-compatible toggle language url
 */
function sta_get_toggle_language_url() {
    if (sta_is_en()) {
        return sta_get_language_url('no');
    }
    return sta_get_language_url('en');
}

/**
 * Prevent WordPress canonical redirect from hijacking /en/ or /nn/ routes
 */
add_filter('redirect_canonical', function ($redirect_url, $requested_url) {
    if (sta_is_en() || sta_is_nn()) {
        return false;
    }
    return $redirect_url;
}, 10, 2);

/**
 * Ensure <html> tag always reflects active language
 */
add_filter('language_attributes', function ($output) {
    if (sta_is_en()) {
        return 'lang="en"';
    }
    if (sta_is_nn()) {
        return 'lang="nn-NO"';
    }
    return 'lang="nb-NO"';
});

/**
 * Filter WordPress locale
 */
add_filter('locale', function ($locale) {
    if (sta_is_en()) {
        return 'en_US';
    }
    if (sta_is_nn()) {
        return 'nn_NO';
    }
    return 'nb_NO';
});

/**
 * Persist language selection in cookie
 */
add_action('init', function () {
    if (isset($_GET['lang']) && !headers_sent()) {
        $q_lang = strtolower(sanitize_text_field(wp_unslash($_GET['lang'])));
        if ($q_lang === 'en' || $q_lang === 'nn' || $q_lang === 'no' || $q_lang === 'nb') {
            $val = ($q_lang === 'nb') ? 'no' : $q_lang;
            setcookie('sta_lang', $val, time() + (86400 * 30), defined('COOKIEPATH') ? COOKIEPATH : '/');
            $_COOKIE['sta_lang'] = $val;
        }
    }
});
