<?php
/**
 * Template Name: Studentaktiviteter (Student Activities)
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
$is_en = sta_is_en();
$is_nn = sta_is_nn();

// 1. Try querying CPT sta_activity
$activity_query = new WP_Query(array(
    'post_type'      => 'sta_activity',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'title',
    'order'          => 'ASC'
));

$items = array();

if ($activity_query->have_posts()) {
    while ($activity_query->have_posts()) {
        $activity_query->the_post();
        $post_id = get_the_ID();
        $categories = get_the_terms($post_id, 'sta_activity_category');
        $cat_name = (!empty($categories) && !is_wp_error($categories)) ? $categories[0]->name : 'Annet';
        $cat_slug = (!empty($categories) && !is_wp_error($categories)) ? $categories[0]->slug : 'annet';

        $items[] = array(
            'id'           => get_post_field('post_name', $post_id),
            'name'         => get_the_title(),
            'category'     => $cat_name,
            'categorySlug' => $cat_slug,
            'imageUrl'     => get_post_meta($post_id, '_sta_image_url', true) ?: (get_the_post_thumbnail_url($post_id, 'medium') ?: ''),
            'email'        => get_post_meta($post_id, '_sta_email', true),
            'website'      => get_post_meta($post_id, '_sta_website', true),
            'facebook'     => get_post_meta($post_id, '_sta_facebook', true),
            'instagram'    => get_post_meta($post_id, '_sta_instagram', true),
            'discord'      => get_post_meta($post_id, '_sta_discord', true),
            'desc'         => $is_en ? (get_post_meta($post_id, '_sta_desc_en', true) ?: get_post_meta($post_id, '_sta_desc_no', true)) : get_post_meta($post_id, '_sta_desc_no', true),
        );
    }
    wp_reset_postdata();
}

// 2. Fallback to bundled JSON data if no CPT entries yet
if (empty($items)) {
    $json_file = sta_get_data_file('activities.json');
    if ($json_file && file_exists($json_file)) {
        $raw = json_decode(file_get_contents($json_file), true);
        if (is_array($raw)) {
            foreach ($raw as $entry) {
                $items[] = array(
                    'id'           => $entry['id'] ?? '',
                    'name'         => $entry['name'] ?? '',
                    'category'     => $is_en ? ($entry['categoryEn'] ?? $entry['category'] ?? '') : ($entry['category'] ?? ''),
                    'categorySlug' => sanitize_title($entry['category'] ?? ''),
                    'imageUrl'     => $entry['imageUrl'] ?? '',
                    'email'        => $entry['email'] ?? '',
                    'website'      => $entry['websiteUrl'] ?? '',
                    'facebook'     => $entry['facebookUrl'] ?? '',
                    'instagram'    => $entry['instagramUrl'] ?? '',
                    'discord'      => $entry['discordUrl'] ?? '',
                    'desc'         => $is_en ? ($entry['descriptionEn'] ?? $entry['description'] ?? '') : ($entry['description'] ?? ''),
                );
            }
        }
    }
}

// Collect unique categories for filter pills
$categories_map = array();
foreach ($items as $item) {
    if (!empty($item['category']) && !empty($item['categorySlug'])) {
        $categories_map[$item['categorySlug']] = $item['category'];
    }
}
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <header class="sta-page-header">
        <span class="sta-badge"><?php echo esc_html(sta_t('Engasjement og fritid', 'Clubs & Leisure', 'Engasjement og fritid')); ?></span>
        <h1 class="sta-hero-title">
            <?php echo esc_html(sta_t('Studentaktiviteter ved UiA', 'Student Activities at UiA', 'Studentaktivitetar ved UiA')); ?>
        </h1>
        <p class="sta-hero-subtitle" style="max-width:800px; margin-left:auto; margin-right:auto;">
            <?php echo esc_html(sta_t('Her finner du en oversikt over godkjente studentaktiviteter og interesseorganisasjoner ved UiA.', 'Here is an overview of approved student activities and interest organizations at UiA.', 'Her finn du ei oversikt over godkjende studentaktivitetar og interesseorganisasjonar ved UiA.')); ?>
        </p>
    </header>

    <!-- Interactive Search and Filter Controls -->
    <div class="sta-directory-controls" style="max-width:56rem; margin:0 auto 2.5rem auto;">
        <div class="sta-search-box" style="max-width:100%;">
            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="search" class="sta-directory-search-input" placeholder="<?php echo esc_attr(sta_t('Søk etter kor, idrett, teater, forening...', 'Search choirs, sports, theatre, clubs...', 'Søk etter kor, idrett, teater, foreining...')); ?>" style="border-radius:9999px;">
        </div>

        <div class="sta-filters-pills" role="toolbar" aria-label="<?php echo esc_attr(sta_t('Filtrer etter kategori', 'Filter by category', 'Filtrer etter kategori')); ?>" style="padding-top:0.5rem;">
            <button type="button" class="sta-filter-btn is-active" data-filter="all">
                <?php echo esc_html(sta_t('Alle aktiviteter', 'All activities', 'Alle aktivitetar')); ?> (<?php echo count($items); ?>)
            </button>
            <?php foreach ($categories_map as $slug => $label): 
                $count = 0;
                foreach ($items as $it) {
                    if ($it['categorySlug'] === $slug) $count++;
                }
            ?>
                <button type="button" class="sta-filter-btn" data-filter="<?php echo esc_attr($slug); ?>">
                    <?php echo esc_html($label); ?> (<?php echo $count; ?>)
                </button>
            <?php endforeach; ?>
        </div>

        <div class="sta-directory-count" data-unit="<?php echo esc_attr(sta_t('aktiviteter', 'activities', 'aktivitetar')); ?>" style="color:var(--sta-teal-200); font-size:0.85rem; font-weight:600; margin-top:0.25rem;">
            <?php printf(esc_html(sta_t('Viser %d av %d aktiviteter', 'Showing %d of %d activities', 'Viser %d av %d aktivitetar')), count($items), count($items)); ?>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="sta-directory-grid">
        <?php foreach ($items as $item): ?>
            <div class="sta-directory-card" data-category="<?php echo esc_attr($item['categorySlug']); ?>" style="padding:1.5rem; display:flex; flex-direction:column; justify-content:space-between;">
                <div>
                    <!-- Contained Logo / Image Box (never crops or zooms in on logos) -->
                    <div style="position:relative; height:9.5rem; width:100%; padding:0.85rem; margin-bottom:1rem; border-radius:0.75rem; overflow:hidden; background:rgba(255,255,255,0.07); border:1px solid rgba(158,207,201,0.22); display:flex; align-items:center; justify-content:center;">
                        <?php if (!empty($item['imageUrl'])): ?>
                            <img src="<?php echo esc_url($item['imageUrl']); ?>" alt="<?php echo esc_attr($item['name']); ?>" style="max-width:100%; max-height:100%; width:auto; height:auto; object-fit:contain; border-radius:0.4rem;" loading="lazy">
                        <?php else: ?>
                            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:2rem; font-weight:900; color:#fff; background:rgba(255,255,255,0.05); border-radius:0.4rem;">
                                <?php echo esc_html(mb_substr($item['name'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div style="margin-bottom:0.5rem;">
                        <span class="sta-badge" style="font-size:0.65rem; padding:0.2rem 0.55rem; background:rgba(239,115,48,0.2); border:1px solid rgba(239,115,48,0.4); color:var(--sta-orange);">
                            <?php echo esc_html($item['category']); ?>
                        </span>
                    </div>

                    <h2 class="sta-card-title" style="font-size:1.15rem; margin:0 0 0.5rem 0; line-height:1.3;">
                        <?php echo esc_html($item['name']); ?>
                    </h2>

                    <p class="sta-card-desc" style="font-size:0.85rem; line-height:1.6; display:-webkit-box; -webkit-line-clamp:4; -webkit-box-orient:vertical; overflow:hidden;">
                        <?php echo esc_html($item['desc']); ?>
                    </p>
                </div>

                <div class="sta-dir-socials" style="display:flex; align-items:center; justify-content:space-between; margin-top:1.25rem; padding-top:1rem; border-top:1px solid rgba(158,207,201,0.2);">
                    <div style="display:flex; align-items:center; gap:0.65rem; color:var(--sta-teal-200);">
                        <?php if (!empty($item['facebook'])): ?>
                            <a href="<?php echo esc_url($item['facebook']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($item['name']); ?> Facebook" style="color:var(--sta-teal-200);">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($item['instagram'])): ?>
                            <a href="<?php echo esc_url($item['instagram']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($item['name']); ?> Instagram" style="color:var(--sta-teal-200);">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($item['email'])): ?>
                            <a href="mailto:<?php echo esc_attr($item['email']); ?>" aria-label="<?php echo esc_attr($item['name']); ?> e-post" style="color:var(--sta-teal-200);">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            </a>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($item['website'])): ?>
                        <a href="<?php echo esc_url($item['website']); ?>" target="_blank" rel="noopener noreferrer" style="font-size:0.75rem; font-weight:700; color:var(--sta-orange); display:inline-flex; align-items:center; gap:0.25rem; text-decoration:none;">
                            <span><?php echo esc_html(sta_t('Nettside', 'Website', 'Nettside')); ?></span>
                            <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Empty State -->
    <div class="sta-directory-empty" style="display:none; text-align:center; padding:3rem; background:var(--sta-card-bg); border-radius:var(--sta-radius); margin-top:2rem;">
        <p style="color:var(--sta-teal-200); font-size:1.2rem;"><?php echo esc_html(sta_t('Ingen studentaktiviteter matchet søket ditt.', 'No student activities matched your search.', 'Ingen studentaktivitetar passa til søket ditt.')); ?></p>
    </div>
</div>

<?php
get_footer();
