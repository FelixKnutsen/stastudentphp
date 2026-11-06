<?php
/**
 * Registers the STA content types and taxonomies from the content model (schema.php).
 * Post type names, meta keys and public URLs are the same as in earlier plugin versions, so
 * existing content keeps working.
 */

if (!defined('ABSPATH')) {
    exit;
}

const STA_CMS_MENU = 'sta-innhold';

function sta_cms_register_post_types() {
    foreach (sta_cms_types() as $type => $def) {
        $plural   = $def['plural'];
        $singular = $def['singular'];
        $public   = !empty($def['public']);
        $supports = array('title', 'page-attributes', 'revisions');
        if (!empty($def['thumbnail'])) {
            $supports[] = 'thumbnail';
        }
        register_post_type($type, array(
            'labels' => array(
                'name'                  => $plural,
                'singular_name'         => ucfirst($singular),
                'menu_name'             => $def['menu'] ?? $plural,
                'all_items'             => $def['menu'] ?? $plural,
                'add_new'               => 'Legg til ny',
                'add_new_item'          => 'Legg til ' . $singular,
                'edit_item'             => 'Rediger ' . $singular,
                'new_item'              => 'Ny ' . $singular,
                'view_item'             => 'Vis ' . $singular,
                'search_items'          => 'Søk',
                'not_found'             => 'Ingen funnet.',
                'not_found_in_trash'    => 'Papirkurven er tom.',
                'featured_image'        => $def['thumbnail'] ?? 'Bilde',
                'set_featured_image'    => 'Velg ' . lcfirst($def['thumbnail'] ?? 'bilde'),
                'remove_featured_image' => 'Fjern ' . lcfirst($def['thumbnail'] ?? 'bilde'),
                'use_featured_image'    => 'Bruk som ' . lcfirst($def['thumbnail'] ?? 'bilde'),
                'item_published'        => ucfirst($singular) . ' publisert.',
                'item_updated'          => ucfirst($singular) . ' oppdatert.',
            ),
            'public'             => $public,
            'publicly_queryable' => $public,
            'exclude_from_search'=> true,
            'show_ui'            => true,
            'show_in_menu'       => STA_CMS_MENU,
            'query_var'          => $public,
            'rewrite'            => $public ? array('slug' => $def['rewrite']) : false,
            'capability_type'    => 'post',
            'map_meta_cap'       => true,
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_icon'          => $def['icon'],
            'supports'           => $supports,
            'taxonomies'         => $def['taxonomies'] ?? array(),
            // The edit forms do not use the REST API; keep non-public types out of the public API.
            'show_in_rest'       => $public,
        ));
    }
}
add_action('init', 'sta_cms_register_post_types', 1);

function sta_cms_register_taxonomies() {
    foreach (sta_cms_taxonomies() as $tax => $def) {
        $public = !empty($def['public']);
        register_taxonomy($tax, $def['objects'], array(
            'hierarchical'      => true,
            'labels'            => array(
                'name'          => $def['plural'],
                'singular_name' => ucfirst($def['singular']),
                'menu_name'     => $def['menu'] ?? $def['plural'],
                'all_items'     => 'Alle',
                'edit_item'     => 'Rediger ' . $def['singular'],
                'update_item'   => 'Lagre',
                'add_new_item'  => 'Legg til ' . $def['singular'],
                'new_item_name' => 'Navn',
                'search_items'  => 'Søk',
                'not_found'     => 'Ingen funnet.',
                'back_to_items' => '← Tilbake',
            ),
            'public'            => $public,
            'publicly_queryable'=> $public,
            'show_ui'           => true,
            'show_in_menu'      => false,
            'show_admin_column' => true,
            'query_var'         => $public,
            'rewrite'           => $public ? array('slug' => $def['rewrite']) : false,
            'show_in_rest'      => $public,
            'meta_box_cb'       => !empty($def['single']) ? 'sta_cms_single_term_box' : null,
        ));
    }
}
add_action('init', 'sta_cms_register_taxonomies', 0);

/**
 * Radio list instead of checkboxes for taxonomies where an entry belongs to exactly one term
 * (one faculty, one category …). Uses the core field name, so WordPress saves it as usual.
 */
function sta_cms_single_term_box($post, $box) {
    $tax   = $box['args']['taxonomy'];
    $terms = sta_cms_ordered_terms($tax, array('hide_empty' => false));
    $current = wp_get_object_terms($post->ID, $tax, array('fields' => 'ids'));
    $current = is_wp_error($current) ? array() : $current;
    $defs  = sta_cms_taxonomies();
    $first = reset($defs[$tax]['objects']);
    $types = sta_cms_types();
    $required = in_array($tax, $types[$post->post_type]['required_taxonomies'] ?? array(), true);
    printf(
        '<div class="sta-cms-terms" data-taxonomy="%s" data-required="%d" data-label="%s">',
        esc_attr($tax),
        $required ? 1 : 0,
        esc_attr(ucfirst($defs[$tax]['singular']))
    );
    echo '<input type="hidden" name="tax_input[' . esc_attr($tax) . '][]" value="0">';
    if (!$terms) {
        echo '<p>Ingen ' . esc_html(lcfirst($defs[$tax]['plural'])) . ' ennå.</p>';
    }
    foreach ($terms as $term) {
        printf(
            '<label style="display:block;margin:4px 0;"><input type="radio" name="tax_input[%1$s][]" value="%2$d" %3$s> %4$s</label>',
            esc_attr($tax),
            (int) $term->term_id,
            checked(in_array($term->term_id, $current, true), true, false),
            esc_html($term->name)
        );
    }
    printf(
        '<p style="margin-top:10px;"><a href="%s">Administrer %s</a></p>',
        esc_url(admin_url('edit-tags.php?taxonomy=' . $tax . '&post_type=' . $first)),
        esc_html(lcfirst($defs[$tax]['plural']))
    );
    echo '</div>';
}

/** Terms sorted by the «Rekkefølge» field, then by name. */
function sta_cms_ordered_terms($taxonomy, $args = array()) {
    $terms = get_terms(array_merge(array('taxonomy' => $taxonomy, 'hide_empty' => false), $args));
    if (is_wp_error($terms)) {
        return array();
    }
    usort($terms, function ($a, $b) {
        $oa = (int) get_term_meta($a->term_id, 'sta_order', true);
        $ob = (int) get_term_meta($b->term_id, 'sta_order', true);
        return $oa === $ob ? strcasecmp($a->name, $b->name) : $oa - $ob;
    });
    return $terms;
}

/** English name of a term (the «Engelsk navn» field; older entries kept it in the description). */
function sta_cms_term_name_en($term) {
    $en = get_term_meta($term->term_id, 'sta_name_en', true);
    return $en !== '' ? $en : (string) $term->description;
}

function sta_cms_term_name_nn($term) {
    return (string) get_term_meta($term->term_id, 'sta_name_nn', true);
}

/** Use the classic edit screen for STA content: one simple form with all fields. */
add_filter('use_block_editor_for_post_type', function ($use, $post_type) {
    return isset(sta_cms_types()[$post_type]) ? false : $use;
}, 10, 2);

/**
 * News uses the same form. Existing posts built with blocks stay in the block editor, so saving
 * them in the classic editor can never damage their layout. The language fields work in both.
 */
add_filter('use_block_editor_for_post', function ($use, $post) {
    return $post->post_type === 'post' ? has_blocks($post->post_content) : $use;
}, 10, 2);

/** The post being edited in wp-admin, 0 on a new post. */
function sta_cms_admin_post_id() {
    return isset($_GET['post']) ? absint($_GET['post']) : (isset($_POST['post_ID']) ? absint($_POST['post_ID']) : 0); // phpcs:ignore WordPress.Security.NonceVerification
}

/**
 * News opens in the plain text editor, not in a page builder. Posts already built with SiteOrigin
 * or Elementor keep their builder, because their content only exists in that form.
 */
add_filter('siteorigin_panels_settings', function ($settings) {
    if (!is_admin() || !is_array($settings) || !isset($settings['post-types'])) {
        return $settings;
    }
    $id = sta_cms_admin_post_id();
    if (!$id || !get_post_meta($id, 'panels_data', true)) {
        $settings['post-types'] = array_values(array_diff((array) $settings['post-types'], array('post')));
    }
    return $settings;
});

add_filter('admin_body_class', function ($classes) {
    $id = sta_cms_admin_post_id();
    return (!$id || get_post_meta($id, '_elementor_edit_mode', true) !== 'builder') ? $classes . ' sta-no-builder' : $classes;
});
