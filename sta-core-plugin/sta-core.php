<?php
/**
 * Plugin Name: STA Core Plugin
 * Plugin URI: https://stastudent.no/
 * Description: Core custom post types, taxonomies, and custom fields for Studentorganisasjonen i Agder (STA). Preserves student activities, academic associations, board members, and positions independently of the active theme.
 * Version: 1.0.0
 * Author: Studentorganisasjonen i Agder (STA)
 * Author URI: https://stastudent.no/
 * Text Domain: sta-core
 * Domain Path: /languages
 * License: GPLv2 or later
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('STA_CORE_VERSION', '1.0.0');
define('STA_CORE_PATH', plugin_dir_path(__FILE__));
define('STA_CORE_URL', plugin_dir_url(__FILE__));

// Require modular components
require_once STA_CORE_PATH . 'inc/taxonomies.php';
require_once STA_CORE_PATH . 'inc/cpt-associations.php';
require_once STA_CORE_PATH . 'inc/cpt-activities.php';
require_once STA_CORE_PATH . 'inc/cpt-board.php';
require_once STA_CORE_PATH . 'inc/cpt-parliament.php';
require_once STA_CORE_PATH . 'inc/cpt-positions.php';
require_once STA_CORE_PATH . 'inc/meta-boxes.php';
require_once STA_CORE_PATH . 'inc/admin-settings.php';

/**
 * Flush rewrite rules on plugin activation
 */
function sta_core_activate() {
    sta_register_all_taxonomies();
    sta_register_cpt_associations();
    sta_register_cpt_activities();
    sta_register_cpt_board();
    sta_register_cpt_parliament();
    sta_register_cpt_positions();
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
