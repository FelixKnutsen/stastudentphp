<?php
/**
 * Custom Post Type: Studentaktiviteter (Student Activities & Clubs)
 */

if (!defined('ABSPATH')) {
    exit;
}

function sta_register_cpt_activities() {
    $labels = array(
        'name'                  => _x('Studentaktiviteter', 'Post type general name', 'sta-core'),
        'singular_name'         => _x('Studentaktivitet', 'Post type singular name', 'sta-core'),
        'menu_name'             => _x('Studentaktiviteter', 'Admin Menu text', 'sta-core'),
        'name_admin_bar'        => _x('Studentaktivitet', 'Add New on Toolbar', 'sta-core'),
        'add_new'               => __('Legg til ny', 'sta-core'),
        'add_new_item'          => __('Legg til ny studentaktivitet', 'sta-core'),
        'new_item'              => __('Ny studentaktivitet', 'sta-core'),
        'edit_item'             => __('Rediger studentaktivitet', 'sta-core'),
        'view_item'             => __('Vis studentaktivitet', 'sta-core'),
        'all_items'             => __('Alle studentaktiviteter', 'sta-core'),
        'search_items'          => __('Søk i studentaktiviteter', 'sta-core'),
        'not_found'             => __('Ingen studentaktiviteter funnet.', 'sta-core'),
        'not_found_in_trash'    => __('Ingen studentaktiviteter i papirkurven.', 'sta-core'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'studentaktivitet'),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 21,
        'menu_icon'          => 'dashicons-buddicons-activity',
        'supports'           => array('title', 'thumbnail', 'revisions'),
        'taxonomies'         => array('sta_activity_category'),
        'show_in_rest'       => true,
    );

    register_post_type('sta_activity', $args);
}
add_action('init', 'sta_register_cpt_activities', 1);
