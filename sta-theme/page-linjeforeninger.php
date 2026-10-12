<?php
/**
 * Template Name: Linjeforeninger (Academic Associations)
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
$is_en = sta_is_en();

// 1. Try querying CPT sta_association
$associations_query = new WP_Query(array(
    'post_type'      => 'sta_association',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'title',
    'order'          => 'ASC'
));

$items = array();

if ($associations_query->have_posts()) {
    while ($associations_query->have_posts()) {
        $associations_query->the_post();
        $post_id = get_the_ID();
        $faculties = get_the_terms($post_id, 'sta_faculty');
        $faculty_name = (!empty($faculties) && !is_wp_error($faculties)) ? $faculties[0]->name : 'Annet';
        $faculty_slug = (!empty($faculties) && !is_wp_error($faculties)) ? $faculties[0]->slug : 'annet';

        $items[] = array(
            'id'           => get_post_field('post_name', $post_id),
            'name'         => get_the_title(),
            'faculty'      => $faculty_name,
            'facultySlug'  => $faculty_slug,
            'campus'       => get_post_meta($post_id, '_sta_campus', true) ?: 'Kristiansand',
            'studyProgram' => get_post_meta($post_id, '_sta_study_program', true),
            'logoUrl'      => get_post_meta($post_id, '_sta_logo_url', true) ?: (get_the_post_thumbnail_url($post_id, 'medium') ?: ''),
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
    $json_file = sta_get_data_file('associations.json');
    if ($json_file && file_exists($json_file)) {
        $raw = json_decode(file_get_contents($json_file), true);
        if (is_array($raw)) {
            foreach ($raw as $entry) {
                $items[] = array(
                    'id'           => $entry['id'] ?? '',
                    'name'         => $entry['name'] ?? '',
                    'faculty'      => $is_en ? ($entry['facultyEn'] ?? $entry['faculty'] ?? '') : ($entry['faculty'] ?? ''),
                    'facultySlug'  => sanitize_title($entry['faculty'] ?? ''),
                    'campus'       => $entry['campus'] ?? 'Kristiansand',
                    'studyProgram' => $entry['studyProgram'] ?? '',
                    'logoUrl'      => $entry['logoUrl'] ?? '',
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

// Collect unique faculties for filter pills
$faculties_map = array();
foreach ($items as $item) {
    if (!empty($item['faculty']) && !empty($item['facultySlug'])) {
        $faculties_map[$item['facultySlug']] = $item['faculty'];
    }
}
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <header class="sta-page-header">
        <span class="sta-badge"><?php echo esc_html(sta_t('Studentmiljø', 'Student Life')); ?></span>
        <h1 class="sta-hero-title">
            <?php echo esc_html(sta_t('Linjeforeninger ved UiA', 'Academic Associations at UiA')); ?>
        </h1>
        <p class="sta-hero-subtitle" style="max-width:800px; margin-left:auto; margin-right:auto;">
            <?php echo esc_html(sta_t('Her finner du oversikt over alle linjeforeningene ved Universitetet i Agder i Kristiansand og Grimstad.', 'Here is the overview of all academic student associations at the University of Agder in Kristiansand and Grimstad.')); ?>
        </p>
    </header>

    <!-- Interactive Search and Filter Controls -->
    <div class="sta-directory-controls" style="max-width:56rem; margin:0 auto 2.5rem auto;">
        <div class="sta-search-box" style="max-width:100%;">
            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="search" class="sta-directory-search-input" placeholder="<?php echo esc_attr(sta_t('Søk etter linjeforening, studieprogram eller fag...', 'Search associations, study programs or subjects...')); ?>" style="border-radius:9999px;">
        </div>

        <div class="sta-filters-pills" role="toolbar" aria-label="<?php echo esc_attr(sta_t('Filtrer etter fakultet', 'Filter by faculty')); ?>" style="padding-top:0.5rem;">
            <button type="button" class="sta-filter-btn is-active" data-filter="all">
                <?php echo esc_html(sta_t('Alle fakulteter', 'All faculties')); ?> (<?php echo count($items); ?>)
            </button>
            <?php foreach ($faculties_map as $slug => $label): 
                $count = 0;
                foreach ($items as $it) {
                    if ($it['facultySlug'] === $slug) $count++;
                }
            ?>
                <button type="button" class="sta-filter-btn" data-filter="<?php echo esc_attr($slug); ?>">
                    <?php echo esc_html($label); ?> (<?php echo $count; ?>)
                </button>
            <?php endforeach; ?>
        </div>

        <div class="sta-directory-count" data-unit="<?php echo esc_attr(sta_t('linjeforeninger', 'associations')); ?>" style="color:var(--sta-teal-200); font-size:0.85rem; font-weight:600; margin-top:0.25rem;">
            <?php printf(esc_html(sta_t('Viser %d av %d linjeforeninger', 'Showing %d of %d associations')), count($items), count($items)); ?>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="sta-directory-grid">
        <?php foreach ($items as $item): ?>
            <div class="sta-directory-card" data-category="<?php echo esc_attr($item['facultySlug']); ?>" style="padding:1.5rem; display:flex; flex-direction:column; justify-content:space-between;">
                <div>
                    <!-- Header with Logo and Info -->
                    <div style="display:flex; align-items:flex-start; gap:1rem; margin-bottom:1rem;">
                        <div class="sta-dir-logo-box" style="width:4.25rem; height:4.25rem; padding:0.4rem; border-radius:0.75rem; flex-shrink:0; margin-bottom:0; background:rgba(255,255,255,0.08); border:1px solid rgba(158,207,201,0.25);">
                            <?php if (!empty($item['logoUrl'])): ?>
                                <img src="<?php echo esc_url($item['logoUrl']); ?>" alt="<?php echo esc_attr($item['name']); ?> logo" style="max-height:100%; max-width:100%; object-fit:contain;" loading="lazy">
                            <?php else: ?>
                                <span style="font-size:1.5rem; font-weight:900; color:var(--sta-teal);"><?php echo esc_html(mb_substr($item['name'], 0, 1)); ?></span>
                            <?php endif; ?>
                        </div>

                        <div style="min-width:0;">
                            <?php if (!empty($item['campus'])): ?>
                                <span style="font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:var(--sta-orange); display:block;">
                                    <?php echo esc_html($item['campus']); ?>
                                </span>
                            <?php endif; ?>
                            <h2 class="sta-card-title" style="font-size:1.15rem; margin:0.15rem 0 0 0; line-height:1.25; overflow:hidden; text-overflow:ellipsis;">
                                <?php echo esc_html($item['name']); ?>
                            </h2>
                            <?php if (!empty($item['studyProgram'])): ?>
                                <p style="font-size:0.75rem; color:var(--sta-teal-200); margin:0.25rem 0 0 0; line-height:1.3; overflow:hidden; text-overflow:ellipsis;">
                                    <?php echo esc_html($item['studyProgram']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div style="margin-bottom:0.75rem;">
                        <span class="sta-badge" style="font-size:0.65rem; padding:0.2rem 0.55rem; background:rgba(255,255,255,0.06); border:1px solid rgba(158,207,201,0.25); color:var(--sta-teal-100);">
                            <?php echo esc_html($item['faculty']); ?>
                        </span>
                    </div>

                    <p class="sta-card-desc" style="font-size:0.85rem; line-height:1.6; display:-webkit-box; -webkit-line-clamp:4; -webkit-box-orient:vertical; overflow:hidden;">
                        <?php echo esc_html($item['desc']); ?>
                    </p>
                </div>

                <div class="sta-dir-socials" style="display:flex; align-items:center; justify-content:space-between; margin-top:1.25rem; padding-top:1rem; border-top:1px solid rgba(158,207,201,0.2);">
                    <div style="display:flex; align-items:center; gap:0.65rem; color:var(--sta-teal-200);">
                        <?php if (!empty($item['website'])): ?>
                            <a href="<?php echo esc_url($item['website']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($item['name']); ?> nettside" style="color:var(--sta-teal-200);">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                            </a>
                        <?php endif; ?>
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
                            <span><?php echo esc_html(sta_t('Nettside', 'Website')); ?></span>
                            <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                        </a>
                    <?php else: ?>
                        <span style="font-size:0.7rem; color:var(--sta-teal-300);">
                            <?php echo esc_html(sta_t('Medlem i STA', 'Member of STA')); ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Empty State -->
    <div class="sta-directory-empty" style="display:none; text-align:center; padding:3rem; background:var(--sta-card-bg); border-radius:var(--sta-radius); margin-top:2rem;">
        <p style="color:var(--sta-teal-200); font-size:1.2rem;"><?php echo esc_html(sta_t('Ingen linjeforeninger matchet søket ditt.', 'No associations matched your search.')); ?></p>
    </div>
</div>

<?php
get_footer();
