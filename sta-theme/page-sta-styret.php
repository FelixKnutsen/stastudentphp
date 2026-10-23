<?php
/**
 * Template Name: STA-Styret (The Board)
 * Faithful reproduction of src/app/sta-styret/page.tsx
 */

if (!defined('ABSPATH')) {
    exit;
}

if (class_exists('\Elementor\Plugin') && (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() || \Elementor\Plugin::$instance->db->is_built_with_elementor(get_the_ID()))) {
    include STA_THEME_PATH . '/page.php';
    return;
}

get_header();
$is_en = sta_is_en();
$is_nn = sta_is_nn();

// Query CPT sta_board_member or load fallback
$members = array();
$board_query = new WP_Query(array(
    'post_type'      => 'sta_board_member',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC'
));

if ($board_query->have_posts()) {
    while ($board_query->have_posts()) {
        $board_query->the_post();
        $post_id = get_the_ID();
        $resp_raw = $is_en ? (get_post_meta($post_id, '_sta_responsibilities_en', true) ?: get_post_meta($post_id, '_sta_responsibilities_no', true)) : get_post_meta($post_id, '_sta_responsibilities_no', true);
        $resp_lines = is_array($resp_raw) ? $resp_raw : (array_filter(array_map('trim', explode("\n", (string)$resp_raw))));

        $members[] = array(
            'id'               => get_post_field('post_name', $post_id),
            'name'             => get_the_title(),
            'role'             => $is_en ? (get_post_meta($post_id, '_sta_role_en', true) ?: get_post_meta($post_id, '_sta_role_no', true)) : get_post_meta($post_id, '_sta_role_no', true),
            'email'            => get_post_meta($post_id, '_sta_email', true),
            'phone'            => get_post_meta($post_id, '_sta_phone', true),
            'imageUrl'         => get_post_meta($post_id, '_sta_image_url', true) ?: (get_the_post_thumbnail_url($post_id, 'medium_large') ?: ''),
            'responsibilities' => $resp_lines,
        );
    }
    wp_reset_postdata();
}

// Fallback to JSON
if (empty($members)) {
    $json_file = sta_get_data_file('board_members.json');
    if ($json_file && file_exists($json_file)) {
        $raw = json_decode(file_get_contents($json_file), true);
        if (is_array($raw)) {
            foreach ($raw as $entry) {
                $resp = $is_en ? ($entry['responsibilitiesEn'] ?? $entry['responsibilities'] ?? array()) : ($entry['responsibilities'] ?? array());
                $members[] = array(
                    'id'               => $entry['id'] ?? '',
                    'name'             => $entry['name'] ?? '',
                    'role'             => $is_en ? ($entry['roleEn'] ?? $entry['role'] ?? '') : ($entry['role'] ?? ''),
                    'email'            => $entry['email'] ?? '',
                    'phone'            => $entry['phone'] ?? '',
                    'imageUrl'         => $entry['imageUrl'] ?? '',
                    'responsibilities' => (array)$resp,
                );
            }
        }
    }
}
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <header class="sta-page-header">
        <span class="sta-badge"><?php echo esc_html(sta_t('Ledelse', 'Executive Leadership', 'Leiing')); ?></span>
        <h1 class="sta-hero-title" style="margin-top:0.5rem;">
            <?php echo esc_html(sta_t('STA-styret og Sekretariatet 2026-2027', 'The STA Board & Secretariat 2026-2027', 'STA-styret og Sekretariatet 2026-2027')); ?>
        </h1>
        <p style="color:var(--sta-teal-100); font-size:1.15rem; max-width:800px;">
            <?php echo esc_html(sta_t('STA-styret og Sekretariatet står for den daglige driften av organisasjonen og har hovedansvaret for studentdemokratiet på Universitetet i Agder.', 'The STA Board and Secretariat are responsible for the daily operations and student democracy at the University of Agder.', 'STA-styret og Sekretariatet står for den daglege drifta av organisasjonen og har hovudansvaret for studentdemokratiet ved Universitetet i Agder.')); ?>
        </p>
    </header>

    <!-- Group Photo Banner with Natural 3:2 Ratio & Skyfish Link -->
    <div style="max-width:896px; margin:0 auto 3.5rem; border-radius:var(--sta-radius); overflow:hidden; border:1px solid rgba(61,148,139,0.3); box-shadow:0 25px 50px -12px rgba(0,0,0,0.5); background:rgba(4,24,23,0.6);">
        <div style="position:relative; aspect-ratio:3/2; width:100%; background:var(--sta-teal-800);">
            <img src="https://stastudent.no/wp-content/uploads/2026/08/Gruppebilde-STA-styret-1024x683.jpg" 
                 alt="<?php echo esc_attr(sta_t('Gruppebilde av STA-styret og sekretariatet', 'Group photo of the STA Board', 'Gruppebilete av STA-styret og sekretariatet')); ?>" 
                 style="width:100%; height:100%; object-fit:cover;"
                 loading="eager">
        </div>
        <div style="background:rgba(4,24,23,0.9); padding:1.25rem 1.75rem; backdrop-filter:blur(4px); display:flex; flex-direction:column; gap:1rem; font-size:0.85rem; color:var(--sta-teal-100);">
            <div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:1rem;">
                <p style="margin:0;">
                    <strong style="color:#fff;"><?php echo esc_html(sta_t('Pressehenvendelser:', 'Press Inquiries:', 'Pressehenvendingar:')); ?></strong> 
                    <?php echo esc_html(sta_t('Alle pressehenvendelser går til studentleder Karen Elise Stenersen.', 'All press inquiries should be directed to student president Karen Elise Stenersen.', 'Alle pressehenvendingar går til studentleiar Karen Elise Stenersen.')); ?>
                </p>
                <a href="https://www.skyfish.com/p/uia/2397359" target="_blank" rel="noopener noreferrer" style="display:inline-flex; align-items:center; gap:0.4rem; font-weight:700; color:var(--sta-orange); shrink-0:0;">
                    <span><?php echo esc_html(sta_t('Last ned pressebilder (Skyfish)', 'Download press photos (Skyfish)', 'Last ned pressebilete (Skyfish)')); ?></span>
                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Board Members Grid (6 Members) -->
    <div class="sta-board-grid">
        <?php foreach ($members as $member): ?>
            <div class="sta-card" style="display:flex; flex-direction:column; justify-content:space-between;">
                <div>
                    <!-- Photo 4:3 -->
                    <div style="position:relative; aspect-ratio:4/3; width:100%; border-radius:var(--sta-radius-sm); overflow:hidden; background:var(--sta-teal-800); border:1px solid rgba(61,148,139,0.25); margin-bottom:1.25rem;">
                        <?php if (!empty($member['imageUrl'])): ?>
                            <img src="<?php echo esc_url($member['imageUrl']); ?>" alt="<?php echo esc_attr($member['name']); ?>" style="width:100%; height:100%; object-fit:cover;">
                        <?php else: ?>
                            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:2rem; font-weight:800; color:#fff;">
                                <?php echo esc_html(mb_substr($member['name'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h3 style="font-size:1.35rem; font-weight:800; color:#fff; margin:0 0 0.25rem;">
                        <?php echo esc_html($member['name']); ?>
                    </h3>
                    <p style="font-size:0.95rem; font-weight:700; color:var(--sta-orange); margin:0 0 1rem;">
                        <?php echo esc_html($member['role']); ?>
                    </p>

                    <?php if (!empty($member['responsibilities'])): ?>
                        <div style="margin-top:1rem; padding-top:0.75rem; border-top:1px solid rgba(158,207,201,0.2);">
                            <p style="font-size:0.75rem; font-weight:800; text-transform:uppercase; letter-spacing:0.05em; color:rgba(255,255,255,0.9); margin:0 0 0.5rem;">
                                <?php echo esc_html(sta_t('Hovedansvar', 'Main Responsibilities', 'Hovudansvar')); ?>
                            </p>
                            <ul style="font-size:0.85rem; color:var(--sta-teal-100); line-height:1.6; padding-left:1.1rem; margin:0;">
                                <?php foreach ($member['responsibilities'] as $resp): ?>
                                    <li><?php echo esc_html($resp); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Contact links -->
                <div style="margin-top:1.5rem; padding-top:1rem; border-top:1px solid rgba(158,207,201,0.2); display:flex; flex-direction:column; gap:0.5rem; font-size:0.85rem;">
                    <?php if (!empty($member['email'])): ?>
                        <a href="mailto:<?php echo esc_attr($member['email']); ?>" style="display:flex; align-items:center; gap:0.5rem; color:var(--sta-teal-100); transition:color 0.2s;">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            <span style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?php echo esc_html($member['email']); ?></span>
                        </a>
                    <?php endif; ?>

                    <?php if (!empty($member['phone'])): ?>
                        <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $member['phone'])); ?>" style="display:flex; align-items:center; gap:0.5rem; color:var(--sta-teal-100); transition:color 0.2s;">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            <span><?php echo esc_html($member['phone']); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Campus & Contact Info Grid -->
    <div style="padding:2rem; border-radius:var(--sta-radius); background:rgba(7,38,36,0.4); border:1px solid rgba(61,148,139,0.3); display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:2rem; font-size:0.85rem; color:var(--sta-teal-200);">
        <div>
            <h4 style="font-size:1rem; font-weight:700; color:#fff; margin:0 0 0.5rem;">Campus Kristiansand</h4>
            <p style="display:flex; align-items:center; gap:0.5rem; margin:0;">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <span>Universitetsveien 24, 4630 Kristiansand S</span>
            </p>
        </div>
        <div>
            <h4 style="font-size:1rem; font-weight:700; color:#fff; margin:0 0 0.5rem;">Campus Grimstad</h4>
            <p style="display:flex; align-items:center; gap:0.5rem; margin:0;">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <span>Jon Lilletuns vei 9, 4879 Grimstad</span>
            </p>
        </div>
        <div>
            <h4 style="font-size:1rem; font-weight:700; color:#fff; margin:0 0 0.5rem;"><?php echo esc_html(sta_t('Felles kontakt', 'Joint Contact', 'Felles kontakt')); ?></h4>
            <p style="margin:0 0 0.25rem;">E-post: sta@uia.no</p>
            <p style="margin:0 0 0.25rem;">Tlf: +47 38 14 20 10</p>
            <p style="margin:0;">Org.nr: 981 596 412</p>
        </div>
    </div>
</div>

<?php
get_footer();
