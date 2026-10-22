<?php
/**
 * Template Name: Sakspapirer (Meeting Papers - Student Parliament)
 * Displays Student Parliament meeting agendas & papers grouped by year (2026–2018),
 * and automatically merges any new PDF links added via the WordPress Page Editor / Elementor.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
$is_en = sta_is_en();
$is_nn = sta_is_nn();

// Default structured archive from https://stastudent.no/dokumenter/sakspapirer/
$default_groups = array(
    '2026' => array(
        array('title' => 'SP-møte 21.10.26', 'href' => 'https://stastudent.no/wp-content/uploads/2026/10/SP-21.10.26.pdf'),
        array('title' => 'SP-møte 23.09.26', 'href' => 'https://stastudent.no/wp-content/uploads/2026/10/SP-23.09.26.pdf'),
        array('title' => 'SP-møte 20.05.26', 'href' => 'https://stastudent.no/wp-content/uploads/2026/05/SP-mote-20.05.26.pdf'),
        array('title' => 'SP-møte 22.04.26', 'href' => 'https://stastudent.no/wp-content/uploads/2026/05/SP-mote-22.04.26.pdf'),
        array('title' => 'SP-møte 25.03.26', 'href' => 'https://stastudent.no/wp-content/uploads/2026/03/SP-mote-25.03.26.pdf'),
        array('title' => 'SP-møte 25.02.26', 'href' => 'https://stastudent.no/wp-content/uploads/2026/02/SP-mote-25.02.26.pdf'),
        array('title' => 'SP-møte 28.01.26', 'href' => 'https://stastudent.no/wp-content/uploads/2026/01/SP-mote-28.01.26.pdf'),
    ),
    '2025' => array(
        array('title' => 'SP-møte 19.11.25', 'href' => 'https://stastudent.no/wp-content/uploads/2025/11/SP-mote-19.11.25.pdf'),
        array('title' => 'SP-møte 29.10.25', 'href' => 'https://stastudent.no/wp-content/uploads/2025/10/SP-mote-29.10.25.pdf'),
        array('title' => 'SP-møte 24.09.25', 'href' => 'https://stastudent.no/wp-content/uploads/2025/10/SP-mote-24.09.25.pdf'),
        array('title' => 'SP-møte 21.05.25', 'href' => 'https://stastudent.no/wp-content/uploads/2025/07/SP-21.05.25.pdf'),
        array('title' => 'SP-møte 19.02.25', 'href' => 'https://stastudent.no/wp-content/uploads/2025/02/SP-19.02.25.pdf'),
        array('title' => 'SP-møte 22.01.25', 'href' => 'https://stastudent.no/wp-content/uploads/2025/01/SP-22.01.25.pdf'),
    ),
    '2024' => array(
        array('title' => 'SP-møte 20.11.24', 'href' => 'https://stastudent.no/wp-content/uploads/2025/01/SP-20.11.24.pdf'),
        array('title' => 'SP-møte 23.10.24', 'href' => 'https://stastudent.no/wp-content/uploads/2025/01/SP-23.10.24.pdf'),
        array('title' => 'SP-møte 25.09.24', 'href' => 'https://stastudent.no/wp-content/uploads/2024/09/SP-25.09.24.pdf'),
        array('title' => 'SP-møte 22.05.24', 'href' => 'https://stastudent.no/wp-content/uploads/2024/05/SP-22.05.24.pdf'),
        array('title' => 'SP-møte 24.04.24', 'href' => 'https://stastudent.no/wp-content/uploads/2024/05/SP-24.04.24.pdf'),
        array('title' => 'SP-møte 20.03.24', 'href' => 'https://stastudent.no/wp-content/uploads/2024/03/SP-20.03.24.pdf'),
        array('title' => 'SP-møte 21.02.24', 'href' => 'https://stastudent.no/wp-content/uploads/2024/03/SP-21.02.24.pdf'),
        array('title' => 'SP-møte 24.01.24', 'href' => 'https://stastudent.no/wp-content/uploads/2024/01/SP-24.01.24.pdf'),
    ),
    '2023' => array(
        array('title' => 'SP-møte 22.11.23', 'href' => 'https://stastudent.no/wp-content/uploads/2024/01/SP-22.11.23.pdf'),
        array('title' => 'SP-møte 25.10.23', 'href' => 'https://stastudent.no/wp-content/uploads/2024/01/SP-25.10.23.pdf'),
        array('title' => 'SP-møte 27.09.23', 'href' => 'https://stastudent.no/wp-content/uploads/2023/10/SP-27.09.23.pdf'),
        array('title' => 'SP-møte 24.05.23', 'href' => 'https://stastudent.no/wp-content/uploads/2023/05/SP-24.05.23.pdf'),
        array('title' => 'SP-møte 19.04.23', 'href' => 'https://stastudent.no/wp-content/uploads/2023/04/SP-19.04.23.pdf'),
        array('title' => 'SP-møte 22.03.23', 'href' => 'https://stastudent.no/wp-content/uploads/2023/04/SP-22.03.23.pdf'),
        array('title' => 'SP-møte 22.02.23', 'href' => 'https://stastudent.no/wp-content/uploads/2023/04/SP-22.02.23.pdf'),
        array('title' => 'SP-møte 25.01.23', 'href' => 'https://stastudent.no/wp-content/uploads/2023/04/SP-25.01.23.pdf'),
    ),
    '2022' => array(
        array('title' => 'SP-møte 23.11.22', 'href' => 'https://stastudent.no/wp-content/uploads/2023/04/SP-23.11.22.pdf'),
        array('title' => 'SP-møte 19.10.22', 'href' => 'https://stastudent.no/wp-content/uploads/2023/04/SP-19.10.22.pdf'),
        array('title' => 'SP-møte 21.09.22', 'href' => 'https://stastudent.no/wp-content/uploads/2023/04/SP-21.09.22-1.pdf'),
        array('title' => 'SP-møte 25.05.22', 'href' => 'https://stastudent.no/wp-content/uploads/2022/05/SP-25.05.22.pdf'),
        array('title' => 'SP-møte 27.04.22', 'href' => 'https://stastudent.no/wp-content/uploads/2022/05/SP-27.04.22.pdf'),
        array('title' => 'SP-møte 23.03.22', 'href' => 'https://stastudent.no/wp-content/uploads/2022/05/SP-23.03.22.pdf'),
        array('title' => 'SP-møte 23.02.22', 'href' => 'https://stastudent.no/wp-content/uploads/2022/02/SP-23.02.22.pdf'),
        array('title' => 'SP-møte 26.01.22', 'href' => 'https://stastudent.no/wp-content/uploads/2022/02/SP-26.01.22.pdf'),
    ),
    '2021' => array(
        array('title' => 'SP-møte 18.11.21', 'href' => 'https://stastudent.no/wp-content/uploads/2021/11/SP-18.11.21.pdf'),
        array('title' => 'SP-møte 20.10.21', 'href' => 'https://stastudent.no/wp-content/uploads/2021/11/SP-20.10.21.pdf'),
        array('title' => 'SP-møte 22.09.21', 'href' => 'https://stastudent.no/wp-content/uploads/2021/11/SP-22.09.21.pdf'),
        array('title' => 'SP-møte 21.04.21 (digitalt Zoom-møte)', 'href' => 'https://stastudent.no/sp-21-04-21-2/'),
        array('title' => 'SP-møte 24.03.21 (digitalt Zoom-møte)', 'href' => 'https://stastudent.no/sp-24-03-21-1/'),
        array('title' => 'SP-møte 24.02.21 (digitalt Zoom-møte)', 'href' => 'https://stastudent.no/sp-24-01-21-1/'),
        array('title' => 'SP-møte 27.01.21 (digitalt Zoom-møte)', 'href' => 'https://stastudent.no/sp-27-01-21/'),
    ),
    '2020' => array(
        array('title' => 'SP-møte 25.11.20 (digitalt Zoom-møte)', 'href' => 'https://stastudent.no/sp-25-11-20/'),
        array('title' => 'SP-møte 28.10.20 (digitalt Zoom-møte)', 'href' => 'https://stastudent.no/sp-28-10-20-1/'),
        array('title' => 'SP-møte 23.09.20 (digitalt Zoom-møte)', 'href' => 'https://stastudent.no/sp-23-09-20/'),
        array('title' => 'SP-møte 27.05.20 (digitalt Zoom-møte)', 'href' => 'https://stastudent.no/wp-content/uploads/2020/07/SP-27.05.20.pdf'),
        array('title' => 'SP-møte 29.04.20 (digitalt Zoom-møte)', 'href' => 'https://stastudent.no/wp-content/uploads/2020/05/SP-29.04.20.pdf'),
        array('title' => 'SP-møte 26.02.20', 'href' => 'https://stastudent.no/wp-content/uploads/2020/05/SP-26.02.20.pdf'),
        array('title' => 'SP-møte 22.01.20', 'href' => 'https://stastudent.no/wp-content/uploads/2020/05/SP-22.01.20.pdf'),
    ),
    '2019' => array(
        array('title' => 'SP-møte 20.11.19', 'href' => 'https://stastudent.no/wp-content/uploads/2019/11/SP-Sakspapirer-20.11.19.pdf'),
        array('title' => 'SP-møte 23.10.19', 'href' => 'https://stastudent.no/wp-content/uploads/2019/11/SP-Sakspapirer-23.10.19.pdf'),
        array('title' => 'SP-møte STA 25.09.19', 'href' => 'https://stastudent.no/wp-content/uploads/2019/09/SP-STA-25.09.19.pdf'),
        array('title' => 'SP-møte 29.05.19', 'href' => 'https://stastudent.no/wp-content/uploads/2019/06/SP-29.05.19.pdf'),
        array('title' => 'Valgforsamling 22.05.19 (DOC)', 'href' => 'https://stastudent.no/wp-content/uploads/2019/06/Innkalling-SP-22.05.19.doc'),
        array('title' => 'SP-møte 24.04.19', 'href' => 'https://stastudent.no/wp-content/uploads/2019/06/SP-24.04.19.pdf'),
        array('title' => 'SP-møte 20.03.19', 'href' => 'https://stastudent.no/wp-content/uploads/2019/06/SP-20.03.19.pdf'),
    ),
    '2018' => array(
        array('title' => 'SP-møte 21.11.18', 'href' => 'https://stastudent.no/wp-content/uploads/2019/01/SP-21.11.18.pdf'),
        array('title' => 'SP-møte 31.10.18', 'href' => 'https://stastudent.no/wp-content/uploads/2019/01/SP-31.10.18.pdf'),
        array('title' => 'SP-møte 26.09.18 – Møtet ble avlyst', 'href' => 'https://stastudent.no/wp-content/uploads/2019/01/SP-26.09.18.pdf'),
        array('title' => 'SP-møte 29.08.18', 'href' => 'https://stastudent.no/wp-content/uploads/2019/01/SP-29.08.18.pdf'),
        array('title' => 'SP-møte 30.05.18', 'href' => 'https://stastudent.no/wp-content/uploads/2019/01/SP-30.05.18.pdf'),
        array('title' => 'SP-møte 23.05.18', 'href' => 'https://stastudent.no/wp-content/uploads/2019/01/SP-29.08.18.pdf'),
        array('title' => 'SP-møte 26.04.18', 'href' => 'https://stastudent.no/wp-content/uploads/2019/01/SP-26.04.18.pdf'),
        array('title' => 'SP-møte 20.03.18', 'href' => 'https://stastudent.no/wp-content/uploads/2019/01/SP-20.03.18.pdf'),
        array('title' => 'SP-møte 21.02.18', 'href' => 'https://stastudent.no/wp-content/uploads/2019/01/SP-21.02.18.pdf'),
        array('title' => 'SP-møte 24.01.18', 'href' => 'https://stastudent.no/wp-content/uploads/2019/01/SP-24.01.17-1.pdf'),
    ),
);

// Locate the WordPress page for Sakspapirer so edits in WordPress/Elementor automatically sync!
$wp_page = get_page_by_path('dokumenter/sakspapirer', OBJECT, 'page');
if (!$wp_page) {
    $wp_page = get_page_by_path('sakspapirer', OBJECT, 'page');
}
if (!$wp_page) {
    $found_posts = get_posts(array('name' => 'sakspapirer', 'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => 1));
    if (!empty($found_posts)) {
        $wp_page = $found_posts[0];
    }
}

$groups = sta_merge_editable_year_docs($wp_page ? $wp_page->post_content : '', $default_groups);
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <!-- Breadcrumb / Back Link -->
    <div style="margin-bottom:1.5rem;">
        <a href="<?php echo esc_url(sta_get_localized_url('/dokumenter')); ?>" style="display:inline-flex; align-items:center; gap:0.5rem; color:var(--sta-teal-200); font-size:0.9rem; font-weight:600; text-decoration:none;">
            <span aria-hidden="true">&larr;</span>
            <span><?php echo esc_html(sta_t('Tilbake til Dokumenter', 'Back to Documents', 'Tilbake til Dokument')); ?></span>
        </a>
    </div>

    <header class="sta-page-header">
        <span class="sta-badge"><?php echo esc_html(sta_t('Studentparlamentet', 'The Student Parliament', 'Studentparlamentet')); ?></span>
        <h1 class="sta-hero-title" style="font-size:clamp(2rem, 5vw, 2.75rem); margin-top:0.5rem;">
            <?php echo esc_html(sta_t('Sakspapirer til møter i Studentparlamentet', 'Meeting Papers for the Student Parliament', 'Sakspapir til møte i Studentparlamentet')); ?>
        </h1>
        <p style="color:var(--sta-teal-100); font-size:1.1rem; max-width:760px; margin:0.5rem auto 0 auto;">
            <?php echo esc_html(sta_t(
                'Her finner du innkallinger og sakspapirer til alle møter i Studentparlamentet sortert etter år.',
                'Here you will find agendas and meeting papers for all meetings in the Student Parliament sorted by year.',
                'Her finn du innkallingar og sakspapir til alle møte i Studentparlamentet sortert etter år.'
            )); ?>
        </p>
    </header>

    <!-- Sub-navigation tabs between Dokumenter, Sakspapirer, and Protokoller -->
    <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:0.75rem; margin-bottom:3rem;">
        <a href="<?php echo esc_url(sta_get_localized_url('/dokumenter')); ?>" class="sta-filter-btn" style="text-decoration:none;">
            <?php echo esc_html(sta_t('Styringsdokumenter & resolusjoner', 'Policy Documents & Resolutions', 'Styringsdokument & resolusjonar')); ?>
        </a>
        <a href="<?php echo esc_url(sta_get_localized_url('/sakspapirer')); ?>" class="sta-filter-btn is-active" style="text-decoration:none;">
            <?php echo esc_html(sta_t('Sakspapirer (SP-møter)', 'Meeting Papers (Agenda)', 'Sakspapir (SP-møte)')); ?>
        </a>
        <a href="<?php echo esc_url(sta_get_localized_url('/referat-fra-moter-i-studentparlamentet')); ?>" class="sta-filter-btn" style="text-decoration:none;">
            <?php echo esc_html(sta_t('Protokoller fra SP-møter', 'Minutes & Protocols', 'Protokollar frå SP-møte')); ?>
        </a>
    </div>

    <!-- Year Cards Grid (3 columns on desktop, 2 on tablet/laptop, 1 on mobile) -->
    <div class="sta-year-docs-grid">
        <?php foreach ($groups as $year => $items): ?>
            <div class="sta-card" style="padding:1.5rem 1.75rem;">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:0.75rem; margin-bottom:1rem; padding-bottom:0.65rem; border-bottom:1px solid rgba(158,207,201,0.22);">
                    <h2 style="font-size:1.35rem; font-weight:800; color:var(--sta-orange); margin:0;">
                        <?php echo esc_html($year); ?>
                    </h2>
                    <span class="sta-badge" style="margin:0; font-size:0.7rem; padding:0.2rem 0.55rem; background:rgba(255,255,255,0.06); border:1px solid rgba(158,207,201,0.2); color:var(--sta-teal-100);">
                        <?php printf(esc_html(sta_t('%d møter', '%d meetings', '%d møte')), count($items)); ?>
                    </span>
                </div>

                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:0.25rem;">
                    <?php foreach ($items as $item): ?>
                        <li>
                            <a href="<?php echo esc_url($item['href']); ?>" target="_blank" rel="noopener noreferrer" class="sta-doc-row-link">
                                <span style="display:flex; align-items:center; gap:0.5rem;">
                                    <svg viewBox="0 0 24 24" width="15" height="15" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                                    <span><?php echo esc_html($item['title']); ?></span>
                                </span>
                                <svg viewBox="0 0 24 24" width="14" height="14" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
get_footer();
