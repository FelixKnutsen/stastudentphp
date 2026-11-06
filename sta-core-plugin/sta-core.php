<?php
/**
 * Plugin Name: STA Core Plugin
 * Plugin URI: https://stastudent.no/
 * Description: Innholdsstyring for stastudent.no: personer og grupper, Studentparlamentet, verv og utvalg, ledige verv, dokumenter, samarbeidspartnere, linjeforeninger og studentaktiviteter – redigeres under «STA-innhold» og vises automatisk på nettsiden.
 * Version: 1.3.2
 * Author: Studentorganisasjonen i Agder (STA)
 * Author URI: https://stastudent.no/
 * Text Domain: sta-core
 * Domain Path: /languages
 * License: GPLv2 or later
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('STA_CORE_VERSION', '1.3.2');
define('STA_CORE_PATH', plugin_dir_path(__FILE__));
define('STA_CORE_URL', plugin_dir_url(__FILE__));

// Content model, admin and frontend output
require_once STA_CORE_PATH . 'inc/cms/schema.php';
require_once STA_CORE_PATH . 'inc/cms/register.php';
require_once STA_CORE_PATH . 'inc/cms/translate.php';
require_once STA_CORE_PATH . 'inc/cms/fields.php';
require_once STA_CORE_PATH . 'inc/cms/admin-ux.php';
require_once STA_CORE_PATH . 'inc/cms/dynamic.php';
require_once STA_CORE_PATH . 'inc/cms/shortcodes.php';
require_once STA_CORE_PATH . 'inc/cms/migrate.php';
require_once STA_CORE_PATH . 'inc/admin-settings.php';
require_once STA_CORE_PATH . 'inc/import-directory.php';

/**
 * Flush rewrite rules on plugin activation
 */
function sta_core_activate() {
    sta_cms_register_taxonomies();
    sta_cms_register_post_types();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'sta_core_activate');

/**
 * Flush rewrite rules on deactivation
 */
function sta_core_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'sta_core_deactivate');
