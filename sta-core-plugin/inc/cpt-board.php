<?php
/**
 * Custom Post Type: Styremedlemmer (STA Board Members)
 */

if (!defined('ABSPATH')) {
    exit;
}

function sta_register_cpt_board() {
    $labels = array(
        'name'                  => _x('Styremedlemmer', 'Post type general name', 'sta-core'),
        'singular_name'         => _x('Styremedlem', 'Post type singular name', 'sta-core'),
        'menu_name'             => _x('STA-Styret', 'Admin Menu text', 'sta-core'),
        'name_admin_bar'        => _x('Styremedlem', 'Add New on Toolbar', 'sta-core'),
        'add_new'               => __('Legg til nytt medlem', 'sta-core'),
        'add_new_item'          => __('Legg til nytt styremedlem', 'sta-core'),
        'new_item'              => __('Nytt styremedlem', 'sta-core'),
        'edit_item'             => __('Rediger styremedlem', 'sta-core'),
        'view_item'             => __('Vis styremedlem', 'sta-core'),
        'all_items'             => __('Alle styremedlemmer', 'sta-core'),
        'search_items'          => __('Søk i styremedlemmer', 'sta-core'),
        'not_found'             => __('Ingen styremedlemmer funnet.', 'sta-core'),
        'not_found_in_trash'    => __('Ingen styremedlemmer i papirkurven.', 'sta-core'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 22,
        'menu_icon'          => 'dashicons-businessman',
        'supports'           => array('title', 'thumbnail', 'page-attributes'),
        'show_in_rest'       => true,
    );

    register_post_type('sta_board_member', $args);
}
add_action('init', 'sta_register_cpt_board', 1);
