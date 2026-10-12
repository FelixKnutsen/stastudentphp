<?php
define('WP_USE_THEMES', false);
require_once __DIR__ . '/../local-test/wordpress/wp-load.php';

$pages = get_pages(array('post_status' => 'publish', 'sort_column' => 'post_name'));
echo "Total published pages: " . count($pages) . PHP_EOL;
foreach ($pages as $p) {
    $template = get_post_meta($p->ID, '_wp_page_template', true);
    echo str_pad($p->ID, 4) . " | " . str_pad($p->post_name, 30) . " | " . str_pad($template ?: 'default', 25) . " | " . $p->post_title . PHP_EOL;
}
