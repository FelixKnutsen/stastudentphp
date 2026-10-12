<?php
/**
 * Setup / Verify Pages and Slugs in Local WordPress Test Site
 */

define('WP_USE_THEMES', false);
require_once __DIR__ . '/../local-test/wordpress/wp-load.php';

$pages_to_ensure = array(
    'forside' => array(
        'title'    => 'Forside',
        'template' => 'front-page.php',
        'content'  => '',
        'is_front' => true,
    ),
    'sta-styret' => array(
        'title'    => 'STA-styret',
        'template' => 'page-sta-styret.php',
        'content'  => '',
    ),
    'studentparlamentet' => array(
        'title'    => 'Studentparlamentet',
        'template' => 'page-studentparlamentet.php',
        'content'  => '',
    ),
    'ledige-verv' => array(
        'title'    => 'Ledige verv',
        'template' => 'page-ledige-verv.php',
        'content'  => '',
    ),
    'linjeforeninger' => array(
        'title'    => 'Linjeforeninger',
        'template' => 'page-linjeforeninger.php',
        'content'  => '',
    ),
    'studentaktiviteter' => array(
        'title'    => 'Studentaktiviteter',
        'template' => 'page-studentaktiviteter.php',
        'content'  => '',
    ),
    'lokaler' => array(
        'title'    => 'Lokaler',
        'template' => 'page-lokaler.php',
        'content'  => '',
    ),
    'dokumenter' => array(
        'title'    => 'Dokumenter',
        'template' => 'page-dokumenter.php',
        'content'  => '',
    ),
    'for-tillitsvalgte' => array(
        'title'    => 'For tillitsvalgte',
        'template' => 'page-for-tillitsvalgte.php',
        'content'  => '',
    ),
    'rad-styrer-og-utvalg' => array(
        'title'    => 'Råd, styrer og utvalg',
        'template' => 'page-rad-styrer-og-utvalg.php',
        'content'  => '',
    ),
    'trygg-organisasjon' => array(
        'title'    => 'Trygg Organisasjon',
        'template' => 'page-trygg-organisasjon.php',
        'content'  => '',
    ),
    'irene-bredals-hederspris' => array(
        'title'    => 'Irene Bredals Hederspris',
        'template' => 'page-irene-bredals-hederspris.php',
        'content'  => '',
    ),
    'personvernserklaering' => array(
        'title'    => 'Personvernserklæring',
        'template' => 'page-personvernserklaering.php',
        'content'  => '',
    ),
);

echo "--- Checking and ensuring pages ---" . PHP_EOL;

foreach ($pages_to_ensure as $slug => $data) {
    $existing = get_page_by_path($slug, OBJECT, 'page');
    
    // Also check if page exists with this post_name regardless of parent
    if (!$existing) {
        $posts = get_posts(array(
            'name'        => $slug,
            'post_type'   => 'page',
            'post_status' => 'publish',
            'numberposts' => 1
        ));
        if (!empty($posts)) {
            $existing = $posts[0];
            // Ensure post_parent is 0 so the slug is top-level /slug
            if ($existing->post_parent != 0) {
                wp_update_post(array(
                    'ID'          => $existing->ID,
                    'post_parent' => 0,
                ));
                echo "Updated {$slug} to top-level (post_parent=0)." . PHP_EOL;
            }
        }
    }

    if ($existing) {
        $page_id = $existing->ID;
        echo "Found existing page: {$slug} (ID: {$page_id})" . PHP_EOL;
    } else {
        $page_id = wp_insert_post(array(
            'post_name'    => $slug,
            'post_title'   => $data['title'],
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_content' => $data['content'],
            'post_parent'  => 0,
        ));
        echo "Created page: {$slug} (ID: {$page_id})" . PHP_EOL;
    }

    // Set template
    if (!empty($data['template'])) {
        update_post_meta($page_id, '_wp_page_template', $data['template']);
    }

    // Set as front page if requested
    if (!empty($data['is_front'])) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $page_id);
        echo "Set {$slug} as front page." . PHP_EOL;
    }
}

// Ensure permalink structure is /%postname%/
update_option('permalink_structure', '/%postname%/');
flush_rewrite_rules();

echo "Done! Rewrite rules flushed." . PHP_EOL;
