<?php
/**
 * Seed News into Local WordPress Test Site with exact dates and sequence from stastudent.no
 */

define('WP_USE_THEMES', false);
require_once __DIR__ . '/../local-test/wordpress/wp-load.php';

// 1. Delete all existing 'post' entries to clean out incorrect dates/sequence
$existing_posts = get_posts(array(
    'post_type'   => 'post',
    'post_status' => 'any',
    'numberposts' => -1
));

foreach ($existing_posts as $p) {
    wp_delete_post($p->ID, true);
}
echo "Cleaned out " . count($existing_posts) . " existing posts." . PHP_EOL;

// 2. Ensure "Nyheter" category exists
$cat_id = 0;
$cat = get_category_by_slug('nyheter');
if (!$cat) {
    $cat_res = wp_insert_term('Nyheter', 'category', array('slug' => 'nyheter'));
    if (!is_wp_error($cat_res)) {
        $cat_id = $cat_res['term_id'];
    }
} else {
    $cat_id = $cat->term_id;
}

// 3. Load news_archive.json (exported from src/content/news.ts)
$json_file = __DIR__ . '/../sta-theme/data/news_archive.json';
if (!file_exists($json_file)) {
    $json_file = __DIR__ . '/../data/news_archive.json';
}

if (!file_exists($json_file)) {
    echo "Could not find news_archive.json" . PHP_EOL;
    exit;
}

$news = json_decode(file_get_contents($json_file), true);
if (!is_array($news)) {
    echo "Invalid JSON in news_archive.json" . PHP_EOL;
    exit;
}

echo "Found " . count($news) . " articles in news_archive.json." . PHP_EOL;

$inserted = 0;
foreach ($news as $item) {
    $slug = sanitize_title($item['slug'] ?? $item['title']);
    
    // Parse exact original date from publishedAt (e.g. 2026-04-13T12:00:00Z)
    $timestamp = !empty($item['publishedAt']) ? strtotime($item['publishedAt']) : null;
    if (!$timestamp && !empty($item['link'])) {
        // Fallback: parse YYYY/MM/DD from URL link
        if (preg_match('#/(\d{4})/(\d{2})/(\d{2})/#', $item['link'], $matches)) {
            $timestamp = strtotime("{$matches[1]}-{$matches[2]}-{$matches[3]} 12:00:00");
        }
    }
    
    $post_date = $timestamp ? date('Y-m-d H:i:s', $timestamp) : '2026-01-01 12:00:00';
    $post_date_gmt = gmdate('Y-m-d H:i:s', $timestamp ?: strtotime('2026-01-01 12:00:00'));

    $post_id = wp_insert_post(array(
        'post_name'      => $slug,
        'post_title'     => $item['title'],
        'post_excerpt'   => $item['excerpt'] ?? '',
        'post_content'   => $item['content'] ?? ($item['excerpt'] ?? ''),
        'post_status'    => 'publish',
        'post_type'      => 'post',
        'post_date'      => $post_date,
        'post_date_gmt'  => $post_date_gmt,
        'post_category'  => $cat_id ? array($cat_id) : array(),
    ));

    if ($post_id && !is_wp_error($post_id)) {
        if (!empty($item['imageUrl'])) {
            update_post_meta($post_id, '_sta_thumbnail_url', $item['imageUrl']);
            update_post_meta($post_id, '_thumbnail_ext_url', $item['imageUrl']);
        }
        $inserted++;
    }
}

echo "Successfully seeded {$inserted} news articles with exact original dates and sequence from stastudent.no!" . PHP_EOL;
