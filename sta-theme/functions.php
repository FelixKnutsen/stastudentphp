<?php
/**
 * STA Theme Functions & Definitions
 */

if (!defined('ABSPATH')) {
    exit;
}

define('STA_THEME_VERSION', '1.1.2');
define('STA_THEME_PATH', get_template_directory());
define('STA_THEME_URL', get_template_directory_uri());

// Include helper modules
require_once STA_THEME_PATH . '/inc/translatepress-compat.php';
require_once STA_THEME_PATH . '/inc/template-tags.php';

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function sta_theme_setup() {
    load_theme_textdomain('sta-theme', STA_THEME_PATH . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Custom image sizes for fast delivery
    add_image_size('sta-card', 600, 400, true);
    add_image_size('sta-hero', 1200, 600, true);
    add_image_size('sta-board', 1024, 683, true);

    // Register primary navigation menus
    register_nav_menus(array(
        'primary-menu'      => __('Hovedmeny (Desktop)', 'sta-theme'),
        'mobile-menu'       => __('Mobilmeny', 'sta-theme'),
        'footer-org'        => __('Bunntekst: Organisasjon', 'sta-theme'),
        'footer-resources'  => __('Bunntekst: Ressurser', 'sta-theme'),
        'footer-guidelines' => __('Bunntekst: Retningslinjer', 'sta-theme'),
    ));
}
add_action('after_setup_theme', 'sta_theme_setup');

/**
 * Enqueue scripts and styles.
 */
function sta_theme_scripts() {
    // Main compiled design stylesheet
    wp_enqueue_style('sta-main-style', STA_THEME_URL . '/assets/css/main.css', array(), STA_THEME_VERSION);

    // Accessible navigation script
    wp_enqueue_script('sta-navigation', STA_THEME_URL . '/assets/js/navigation.js', array(), STA_THEME_VERSION, true);

    // Directory live filter script (for associations and activities)
    $sta_page = isset($_GET['sta_page']) ? sanitize_title(wp_unslash($_GET['sta_page'])) : '';
    if (is_page_template(array('page-linjeforeninger.php', 'page-studentaktiviteter.php')) || is_page(array('linjeforeninger', 'studentaktiviteter')) || in_array($sta_page, array('linjeforeninger', 'studentaktiviteter'), true)) {
        wp_enqueue_script('sta-directory-filter', STA_THEME_URL . '/assets/js/directory-filter.js', array(), STA_THEME_VERSION, true);
    }
}
add_action('wp_enqueue_scripts', 'sta_theme_scripts');

/**
 * Set news archive post query to 10 articles per page (matching reference 19 pages)
 */
function sta_adjust_news_archive_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        if ($query->is_home() || $query->is_category('nyheter') || $query->is_category(3)) {
            $query->set('posts_per_page', 10);
        }
    }
}
add_action('pre_get_posts', 'sta_adjust_news_archive_query');

/**
 * Automatically ensure all STA pages and custom templates exist in WordPress
 * so the site works out-of-the-box without manual page recreation.
 */
function sta_auto_ensure_pages() {
    if (get_option('sta_theme_pages_seeded_v7') === 'yes') {
        return;
    }

    $pages_to_ensure = array(
        'forside'                  => array('title' => 'Forside',                  'template' => 'front-page.php',                  'is_front' => true),
        'sta-styret'               => array('title' => 'STA-styret',               'template' => 'page-sta-styret.php'),
        'studentparlamentet'       => array('title' => 'Studentparlamentet',       'template' => 'page-studentparlamentet.php'),
        'ledige-verv'              => array('title' => 'Ledige verv',              'template' => 'page-ledige-verv.php'),
        'linjeforeninger'          => array('title' => 'Linjeforeninger',          'template' => 'page-linjeforeninger.php'),
        'studentaktiviteter'       => array('title' => 'Studentaktiviteter',       'template' => 'page-studentaktiviteter.php'),
        'lokaler'                  => array('title' => 'Lokaler',                  'template' => 'page-lokaler.php'),
        'dokumenter'               => array('title' => 'Dokumenter',               'template' => 'page-dokumenter.php'),
        'sakspapirer'              => array('title' => 'Sakspapirer til møter i studentparlamentet', 'template' => 'page-sakspapirer.php'),
        'referat-fra-moter-i-studentparlamentet' => array('title' => 'Protokoller fra møter i Studentparlamentet', 'template' => 'page-referat-fra-moter-i-studentparlamentet.php'),
        'for-tillitsvalgte'        => array('title' => 'For tillitsvalgte',        'template' => 'page-for-tillitsvalgte.php'),
        'rad-styrer-og-utvalg'     => array('title' => 'Råd, styrer og utvalg',    'template' => 'page-rad-styrer-og-utvalg.php'),
        'valgkomiteen'             => array('title' => 'Valgkomitéen',             'template' => 'page-valgkomiteen.php'),
        'trygg-organisasjon'       => array('title' => 'Trygg Organisasjon',       'template' => 'page-trygg-organisasjon.php'),
        'irene-bredals-hederspris' => array('title' => 'Irene Bredals Hederspris', 'template' => 'page-irene-bredals-hederspris.php'),
        'prinsipprogram'           => array('title' => 'Prinsipprogram',           'template' => 'page-prinsipprogram.php'),
        'personvernserklaering'    => array('title' => 'Personvernserklæring',     'template' => 'page-personvernserklaering.php'),
        'om-sta'                   => array('title' => 'Om STA',                   'template' => 'page.php'),
        'engasjer-deg'             => array('title' => 'Engasjer deg',             'template' => 'page.php'),
        'for-foreninger'           => array('title' => 'For foreninger',           'template' => 'page.php'),
    );

    foreach ($pages_to_ensure as $slug => $data) {
        $existing = get_page_by_path($slug, OBJECT, 'page');
        if (!$existing) {
            $posts = get_posts(array(
                'name'        => $slug,
                'post_type'   => 'page',
                'post_status' => 'publish',
                'numberposts' => 1,
            ));
            if (!empty($posts)) {
                $existing = $posts[0];
                if ($existing->post_parent != 0) {
                    wp_update_post(array('ID' => $existing->ID, 'post_parent' => 0));
                }
            }
        }

        if ($existing) {
            $page_id = $existing->ID;
        } else {
            $page_id = wp_insert_post(array(
                'post_name'    => $slug,
                'post_title'   => $data['title'],
                'post_type'    => 'page',
                'post_status'  => 'publish',
                'post_content' => '',
                'post_parent'  => 0,
            ));
        }

        if ($page_id && !is_wp_error($page_id) && !empty($data['template'])) {
            update_post_meta($page_id, '_wp_page_template', $data['template']);
        }

        if ($page_id && !is_wp_error($page_id) && !empty($data['is_front'])) {
            if (get_option('show_on_front') !== 'page' || !get_option('page_on_front')) {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $page_id);
            }
        }
    }

    // Remove the square OpenGraph logo (11946 / 11945) from page _thumbnail_id so it never renders as a giant banner in Elementor
    $all_pages = get_pages(array('post_status' => 'any'));
    foreach ($all_pages as $p) {
        $thumb_id = (int) get_post_thumbnail_id($p->ID);
        if ($thumb_id === 11946 || $thumb_id === 11945) {
            delete_post_thumbnail($p->ID);
        }
    }

    update_option('sta_theme_pages_seeded_v7', 'yes');
    flush_rewrite_rules();
}
add_action('init', 'sta_auto_ensure_pages', 20);
add_action('after_switch_theme', function () {
    delete_option('sta_theme_pages_seeded_v6');
    sta_auto_ensure_pages();
});

/**
 * Force STA Theme templates at highest priority (9999999) so page builders
 * (such as Elementor's header-footer template) on existing sites never replace
 * our custom PHP templates with old page-builder content.
 */
function sta_force_theme_templates($template) {
    // 0. If Elementor preview is open, always render page.php (which calls the_content() without any featured-image banner)
    if (!empty($_GET['elementor-preview'])) {
        $default_page = STA_THEME_PATH . '/page.php';
        if (file_exists($default_page)) {
            return $default_page;
        }
    }

    // 1. Handle ?sta_page=... routing (used on WP Staging subdirectories without Nginx/Apache rewrite rules)
    if (!empty($_GET['sta_page'])) {
        $raw_sta_page = trim(wp_unslash($_GET['sta_page']), '/');
        $slug = sanitize_title(basename($raw_sta_page));
        status_header(200);
        global $wp_query;
        if ($wp_query) {
            $wp_query->is_404 = false;
        }

        if ($slug === 'forside' || $slug === 'studentorganisasjonen-i-agder-2') {
            $front = STA_THEME_PATH . '/front-page.php';
            if (file_exists($front)) {
                return $front;
            }
        }

        if ($slug === 'nyheter' || $slug === 'siste-nytt' || $slug === 'aktuelt') {
            $index_tpl = STA_THEME_PATH . '/index.php';
            if (file_exists($index_tpl)) {
                return $index_tpl;
            }
        }

        if ($slug === 'resolusjoner') {
            $docs_tpl = STA_THEME_PATH . '/page-dokumenter.php';
            if (file_exists($docs_tpl)) {
                return $docs_tpl;
            }
        }

        $slug_template = STA_THEME_PATH . '/page-' . $slug . '.php';
        if (file_exists($slug_template)) {
            return $slug_template;
        }

        $default_page = STA_THEME_PATH . '/page.php';
        if (file_exists($default_page)) {
            return $default_page;
        }
    }

    if (is_singular('post')) {
        $single = STA_THEME_PATH . '/single.php';
        if (file_exists($single)) {
            return $single;
        }
    }

    if (is_search()) {
        $search = STA_THEME_PATH . '/search.php';
        if (file_exists($search)) {
            return $search;
        }
    }

    if (is_front_page()) {
        $front = STA_THEME_PATH . '/front-page.php';
        if (file_exists($front)) {
            return $front;
        }
    }

    if (is_page()) {
        $post_id   = get_queried_object_id();
        $post_slug = $post_id ? get_post_field('post_name', $post_id) : '';
        if ($post_slug) {
            $slug_template = STA_THEME_PATH . '/page-' . $post_slug . '.php';
            if (file_exists($slug_template)) {
                return $slug_template;
            }
        }
        $default_page = STA_THEME_PATH . '/page.php';
        if (file_exists($default_page)) {
            return $default_page;
        }
    }

    return $template;
}
add_filter('template_include', 'sta_force_theme_templates', 9999999);
