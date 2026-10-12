<?php
/**
 * Custom Post Type: Studentparlamentet (Parliament Representatives)
 */

if (!defined('ABSPATH')) {
    exit;
}

function sta_register_cpt_parliament() {
    $labels = array(
        'name'                  => _x('Parlamentsrepresentanter', 'Post type general name', 'sta-core'),
        'singular_name'         => _x('Parlamentsrepresentant', 'Post type singular name', 'sta-core'),
        'menu_name'             => _x('Studentparlamentet', 'Admin Menu text', 'sta-core'),
        'name_admin_bar'        => _x('Representant', 'Add New on Toolbar', 'sta-core'),
        'add_new'               => __('Legg til ny', 'sta-core'),
        'add_new_item'          => __('Legg til ny representant', 'sta-core'),
        'new_item'              => __('Ny representant', 'sta-core'),
        'edit_item'             => __('Rediger representant', 'sta-core'),
        'view_item'             => __('Vis representant', 'sta-core'),
        'all_items'             => __('Alle representanter', 'sta-core'),
        'search_items'          => __('Søk i representanter', 'sta-core'),
        'not_found'             => __('Ingen representanter funnet.', 'sta-core'),
        'not_found_in_trash'    => __('Ingen representanter i papirkurven.', 'sta-core'),
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
        'menu_position'      => 23,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array('title', 'page-attributes'),
        'taxonomies'         => array('sta_faculty'),
        'show_in_rest'       => true,
    );

    register_post_type('sta_parliament_rep', $args);
}
add_action('init', 'sta_register_cpt_parliament', 1);
