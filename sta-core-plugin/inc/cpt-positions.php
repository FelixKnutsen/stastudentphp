<?php
/**
 * Custom Post Type: Verv og utvalg (Elected Positions & Committees)
 */

if (!defined('ABSPATH')) {
    exit;
}

function sta_register_cpt_positions() {
    $labels = array(
        'name'                  => _x('Verv og utvalg', 'Post type general name', 'sta-core'),
        'singular_name'         => _x('Verv/Utvalg', 'Post type singular name', 'sta-core'),
        'menu_name'             => _x('Verv og utvalg', 'Admin Menu text', 'sta-core'),
        'name_admin_bar'        => _x('Verv', 'Add New on Toolbar', 'sta-core'),
        'add_new'               => __('Legg til nytt verv', 'sta-core'),
        'add_new_item'          => __('Legg til nytt verv/utvalg', 'sta-core'),
        'new_item'              => __('Nytt verv', 'sta-core'),
        'edit_item'             => __('Rediger verv', 'sta-core'),
        'view_item'             => __('Vis verv', 'sta-core'),
        'all_items'             => __('Alle verv og utvalg', 'sta-core'),
        'search_items'          => __('Søk i verv', 'sta-core'),
        'not_found'             => __('Ingen verv funnet.', 'sta-core'),
        'not_found_in_trash'    => __('Ingen verv i papirkurven.', 'sta-core'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'verv'),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 24,
        'menu_icon'          => 'dashicons-awards',
        'supports'           => array('title', 'editor', 'page-attributes'),
        'taxonomies'         => array('sta_committee_group'),
        'show_in_rest'       => true,
    );

    register_post_type('sta_position', $args);
}
add_action('init', 'sta_register_cpt_positions', 1);
