<?php
define('WP_USE_THEMES', false);
require_once __DIR__ . '/../local-test/wordpress/wp-load.php';

$q = new WP_Query(array(
    'post_type'      => 'post',
    'posts_per_page' => 5,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC'
));

echo "WP_Query found_posts: " . $q->found_posts . PHP_EOL;
foreach ($q->posts as $p) {
    $cats = wp_get_post_categories($p->ID, array('fields' => 'names'));
    $link = get_permalink($p->ID);
    echo "Post {$p->ID}: '{$p->post_title}' (date: {$p->post_date}, cats: " . implode(', ', $cats) . ", link: {$link})" . PHP_EOL;
}

$cat_id = get_cat_ID('Nyheter');
echo "Nyheter cat ID: {$cat_id}, link: " . get_category_link($cat_id) . PHP_EOL;
