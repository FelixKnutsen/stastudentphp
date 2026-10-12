<?php
require_once __DIR__ . '/../local-test/wordpress/wp-load.php';

$posts = get_posts([
    'numberposts' => -1,
    'post_status' => 'publish',
    'post_type' => ['post', 'page', 'sta_association', 'sta_activity']
]);

echo "Found " . count($posts) . " published items:\n";
foreach ($posts as $p) {
    echo $p->post_type . " | " . $p->post_name . " | " . get_permalink($p->ID) . "\n";
}
