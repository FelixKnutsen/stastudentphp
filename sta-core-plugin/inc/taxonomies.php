<?php
/**
 * Taxonomies Registration for STA Core
 */

if (!defined('ABSPATH')) {
    exit;
}

function sta_register_all_taxonomies() {
    // 1. Faculty Taxonomy (Fakultet)
    $faculty_labels = array(
        'name'              => _x('Fakulteter', 'taxonomy general name', 'sta-core'),
        'singular_name'     => _x('Fakultet', 'taxonomy singular name', 'sta-core'),
        'search_items'      => __('Søk i fakulteter', 'sta-core'),
        'all_items'         => __('Alle fakulteter', 'sta-core'),
        'parent_item'       => __('Overordnet fakultet', 'sta-core'),
        'parent_item_colon' => __('Overordnet fakultet:', 'sta-core'),
        'edit_item'         => __('Rediger fakultet', 'sta-core'),
        'update_item'       => __('Oppdater fakultet', 'sta-core'),
        'add_new_item'      => __('Legg til nytt fakultet', 'sta-core'),
        'new_item_name'     => __('Nytt fakultetsnavn', 'sta-core'),
        'menu_name'         => __('Fakulteter', 'sta-core'),
    );

    register_taxonomy('sta_faculty', array('sta_association', 'sta_parliament_rep'), array(
        'hierarchical'      => true,
        'labels'            => $faculty_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'fakultet'),
        'show_in_rest'      => true,
    ));

    // 2. Activity Category Taxonomy (Aktivitetskategori)
    $activity_cat_labels = array(
        'name'              => _x('Aktivitetskategorier', 'taxonomy general name', 'sta-core'),
        'singular_name'     => _x('Aktivitetskategori', 'taxonomy singular name', 'sta-core'),
        'search_items'      => __('Søk i kategorier', 'sta-core'),
        'all_items'         => __('Alle kategorier', 'sta-core'),
        'edit_item'         => __('Rediger kategori', 'sta-core'),
        'update_item'       => __('Oppdater kategori', 'sta-core'),
        'add_new_item'      => __('Legg til ny kategori', 'sta-core'),
        'new_item_name'     => __('Nytt kategorinavn', 'sta-core'),
        'menu_name'         => __('Aktivitetskategorier', 'sta-core'),
    );

    register_taxonomy('sta_activity_category', array('sta_activity'), array(
        'hierarchical'      => true,
        'labels'            => $activity_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'aktivitetskategori'),
        'show_in_rest'      => true,
    ));

    // 3. Committee Group Taxonomy (Utvalgsgruppe)
    $committee_group_labels = array(
        'name'              => _x('Utvalgsgrupper', 'taxonomy general name', 'sta-core'),
        'singular_name'     => _x('Utvalgsgruppe', 'taxonomy singular name', 'sta-core'),
        'search_items'      => __('Søk i utvalgsgrupper', 'sta-core'),
        'all_items'         => __('Alle utvalgsgrupper', 'sta-core'),
        'edit_item'         => __('Rediger utvalgsgruppe', 'sta-core'),
        'update_item'       => __('Oppdater utvalgsgruppe', 'sta-core'),
        'add_new_item'      => __('Legg til ny utvalgsgruppe', 'sta-core'),
        'menu_name'         => __('Utvalgsgrupper', 'sta-core'),
    );

    register_taxonomy('sta_committee_group', array('sta_position'), array(
        'hierarchical'      => true,
        'labels'            => $committee_group_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'utvalgsgruppe'),
        'show_in_rest'      => true,
    ));
}
add_action('init', 'sta_register_all_taxonomies', 0);
