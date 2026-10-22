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
 * Helper: Get WordPress home path prefix (e.g. '' on root domain, or '/staging' on WP Staging)
 */
function sta_get_home_base_path() {
    $home_path = parse_url(home_url(), PHP_URL_PATH);
    if (!$home_path || $home_path === '/') {
        return '';
    }
    return '/' . trim($home_path, '/');
}

/**
 * Early URL Router for /en/ and /nn/ on Apache/Nginx & WP Staging subdirectories.
 * Rewrites $_SERVER['REQUEST_URI'] before WordPress WP::parse_request() runs so
 * /en/... and /nn/... route cleanly to the underlying WordPress pages without 404s.
 */
function sta_bootstrap_language_routing() {
    if (is_admin() || !isset($_SERVER['REQUEST_URI'])) {
        return;
    }

    $raw_uri = wp_unslash($_SERVER['REQUEST_URI']);
    $GLOBALS['sta_original_request_uri'] = $raw_uri;

    $req_path  = parse_url($raw_uri, PHP_URL_PATH) ?: '/';
    $req_query = parse_url($raw_uri, PHP_URL_QUERY);
    $base_path = sta_get_home_base_path();

    $rel_path = $req_path;
    if ($base_path !== '' && strpos($rel_path, $base_path) === 0) {
        $rel_path = substr($rel_path, strlen($base_path));
        if ($rel_path === '' || $rel_path[0] !== '/') {
            $rel_path = '/' . $rel_path;
        }
    }

    if (preg_match('#^/(en|nn)(?:/(.*))?$#i', $rel_path, $matches)) {
        $lang = strtolower($matches[1]);
        $GLOBALS['sta_detected_lang'] = $lang;
        $_GET['lang'] = $lang;
        $_COOKIE['sta_lang'] = $lang;

        $subpath = '/' . (isset($matches[2]) ? $matches[2] : '');
        $new_uri = $base_path . $subpath . ($req_query ? '?' . $req_query : '');
        $_SERVER['REQUEST_URI'] = $new_uri;
        $_SERVER['PATH_INFO']   = $subpath;
        unset($_SERVER['ORIG_PATH_INFO']);
    }
}
sta_bootstrap_language_routing();

/**
 * Detect current active language ('no', 'en', or 'nn')
 * Default is ALWAYS 'no' (Norsk bokmål) unless English or Nynorsk is explicitly requested.
 */
function sta_get_current_language() {
    // 1. Check early-detected URL prefix (/en/ or /nn/)
    if (!empty($GLOBALS['sta_detected_lang'])) {
        return $GLOBALS['sta_detected_lang'];
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

    // 3. Check URL path for /en/ or /nn/ (accounting for any staging subdirectory)
    $raw_uri = isset($GLOBALS['sta_original_request_uri'])
        ? $GLOBALS['sta_original_request_uri']
        : (isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '/');
    $req_path = parse_url($raw_uri, PHP_URL_PATH) ?: '/';
    $base_path = sta_get_home_base_path();
    if ($base_path !== '' && strpos($req_path, $base_path) === 0) {
        $req_path = substr($req_path, strlen($base_path)) ?: '/';
    }
    if (preg_match('#^/en(/|\?|$)#i', $req_path)) {
        return 'en';
    }
    if (preg_match('#^/nn(/|\?|$)#i', $req_path)) {
        return 'nn';
    }

    // 4. Check cookie
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

    // 5. Fallback to TranslatePress if active and none of the above were set
    if (function_exists('trp_get_current_language')) {
        $trp_lang = strtolower(trp_get_current_language());
        if (strpos($trp_lang, 'en') === 0) {
            return 'en';
        }
        if (strpos($trp_lang, 'nn') === 0) {
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
 * Determine whether to use query-string (?lang=nn/en/no) language routing.
 * Enabled automatically on WP Staging subdirectories (e.g. /reliant/) or when
 * TranslatePress is active on the server so Apache/LiteSpeed/TranslatePress
 * never 404s or hijacks /nn/ and /en/ routes.
 */
function sta_use_query_lang_routing() {
    if (sta_get_home_base_path() !== '') {
        return true;
    }
    if (function_exists('trp_get_current_language') || class_exists('TRP_Translate_Press')) {
        return true;
    }
    return false;
}

/**
 * Helper to build language-aware internal URL.
 * When running on WP Staging subdirectories (e.g. /reliant/) where Nginx/Apache
 * rewrite rules are not configured for the subfolder, all subpages route via
 * home_url('/?sta_page=...') so every link hits /reliant/index.php directly.
 */
function sta_get_localized_url($path = '/', $target_lang = null) {
    $clean_slug = trim($path, '/');
    $path = '/' . $clean_slug;
    $lang = $target_lang ?: sta_get_current_language();

    if (sta_use_query_lang_routing()) {
        $base_url = home_url('/');
        if ($clean_slug !== '') {
            if ($clean_slug === 'nyheter' || $clean_slug === 'category/nyheter') {
                $base_url = add_query_arg('sta_page', 'nyheter', $base_url);
            } elseif (strpos($clean_slug, 'nyheter/') === 0) {
                $post_slug = substr($clean_slug, strlen('nyheter/'));
                $base_url = add_query_arg('name', $post_slug, $base_url);
            } else {
                $base_url = add_query_arg('sta_page', $clean_slug, $base_url);
            }
        }
        if ($lang === 'en' || $lang === 'nn') {
            return add_query_arg('lang', $lang, $base_url);
        }
        return $base_url;
    }

    if ($lang === 'en') {
        return home_url('/en' . ($path === '/' ? '/' : $path));
    }
    if ($lang === 'nn') {
        return home_url('/nn' . ($path === '/' ? '/' : $path));
    }
    return home_url($path);
}

/**
 * Get target language URL for switcher (retains current subpath & query args)
 */
function sta_get_language_url($target_lang = 'no') {
    $current_uri = isset($GLOBALS['sta_original_request_uri'])
        ? $GLOBALS['sta_original_request_uri']
        : (isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '/');
    $clean_uri = remove_query_arg('lang', $current_uri);

    $path = parse_url($clean_uri, PHP_URL_PATH);
    if (!$path) $path = '/';
    $query = parse_url($clean_uri, PHP_URL_QUERY);
    $query_str = $query ? '?' . $query : '';

    // Strip WP Staging subdirectory prefix if present
    $base_path = sta_get_home_base_path();
    if ($base_path !== '' && strpos($path, $base_path) === 0) {
        $path = substr($path, strlen($base_path));
        if ($path === '' || $path[0] !== '/') {
            $path = '/' . $path;
        }
    }

    // Strip any existing language prefix (/en or /nn)
    $clean_path = preg_replace('#^/(en|nn)(/|$)#i', '/', $path);
    if (!$clean_path) $clean_path = '/';

    if (sta_use_query_lang_routing()) {
        $base_url = home_url('/' . $query_str);
        if ($clean_path !== '/' && empty($_GET['sta_page'])) {
            $base_url = add_query_arg('sta_page', trim($clean_path, '/'), $base_url);
        }
        return add_query_arg('lang', $target_lang, $base_url);
    }

    if ($target_lang === 'en') {
        return home_url('/en' . ($clean_path === '/' ? '/' : ('/' . ltrim($clean_path, '/'))) . $query_str);
    }
    if ($target_lang === 'nn') {
        return home_url('/nn' . ($clean_path === '/' ? '/' : ('/' . ltrim($clean_path, '/'))) . $query_str);
    }
    return home_url($clean_path . $query_str);
}

/**
 * Ensure WordPress post and page permalinks also use query-based URLs on WP Staging
 * subdirectories so clicking news articles or search results never 404s.
 */
add_filter('post_link', function ($permalink, $post) {
    if (sta_use_query_lang_routing() && isset($post->ID)) {
        $url = add_query_arg('p', $post->ID, home_url('/'));
        $lang = sta_get_current_language();
        if ($lang === 'en' || $lang === 'nn') {
            $url = add_query_arg('lang', $lang, $url);
        }
        return $url;
    }
    return $permalink;
}, 9999, 2);

add_filter('page_link', function ($permalink, $post_id) {
    if (sta_use_query_lang_routing() && $post_id) {
        $slug = get_post_field('post_name', $post_id);
        if ($slug && $slug !== 'forside') {
            return sta_get_localized_url('/' . $slug);
        }
        return sta_get_localized_url('/');
    }
    return $permalink;
}, 9999, 2);

/**
 * Stop TranslatePress output buffer & floating language switcher from conflicting
 * with STA Theme's built-in 3-language (Bokmål, Nynorsk, English) architecture.
 */
add_filter('trp_stop_translating_page', '__return_true', 999999);

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
 * Prevent WordPress canonical redirect from hijacking /en/, /nn/, or ?sta_page= routes
 */
add_filter('redirect_canonical', function ($redirect_url, $requested_url) {
    if (sta_is_en() || sta_is_nn() || !empty($_GET['sta_page'])) {
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
