<?php
/**
 * STA Theme Functions & Definitions
 */

if (!defined('ABSPATH')) {
    exit;
}

define('STA_THEME_VERSION', '1.0.0');
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
    if (is_page_template(array('page-linjeforeninger.php', 'page-studentaktiviteter.php')) || is_page(array('linjeforeninger', 'studentaktiviteter'))) {
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
