<?php
/**
 * Custom Post Type: Linjeforeninger (Academic Associations)
 */

if (!defined('ABSPATH')) {
    exit;
}

function sta_register_cpt_associations() {
    $labels = array(
        'name'                  => _x('Linjeforeninger', 'Post type general name', 'sta-core'),
        'singular_name'         => _x('Linjeforening', 'Post type singular name', 'sta-core'),
        'menu_name'             => _x('Linjeforeninger', 'Admin Menu text', 'sta-core'),
        'name_admin_bar'        => _x('Linjeforening', 'Add New on Toolbar', 'sta-core'),
        'add_new'               => __('Legg til ny', 'sta-core'),
        'add_new_item'          => __('Legg til ny linjeforening', 'sta-core'),
        'new_item'              => __('Ny linjeforening', 'sta-core'),
        'edit_item'             => __('Rediger linjeforening', 'sta-core'),
        'view_item'             => __('Vis linjeforening', 'sta-core'),
        'all_items'             => __('Alle linjeforeninger', 'sta-core'),
        'search_items'          => __('Søk i linjeforeninger', 'sta-core'),
        'not_found'             => __('Ingen linjeforeninger funnet.', 'sta-core'),
        'not_found_in_trash'    => __('Ingen linjeforeninger i papirkurven.', 'sta-core'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'linjeforening'),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-welcome-learn-more',
        'supports'           => array('title', 'thumbnail', 'revisions'),
        'taxonomies'         => array('sta_faculty'),
        'show_in_rest'       => true,
    );

    register_post_type('sta_association', $args);
}
add_action('init', 'sta_register_cpt_associations', 1);
