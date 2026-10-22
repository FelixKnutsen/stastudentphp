<?php
/**
 * Template Name: Protokoller (Minutes & Protocols - Student Parliament)
 * Displays Student Parliament meeting protocols grouped by year (2026–2018),
 * and automatically merges any new PDF/DOC links added via the WordPress Page Editor / Elementor.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
$is_en = sta_is_en();
$is_nn = sta_is_nn();

// Default structured archive from https://stastudent.no/dokumenter/referat-fra-moter-i-studentparlamentet/
$default_groups = array(
    '2026' => array(
        array('title' => 'Protokoll SP-møte 23.09.26', 'href' => 'https://stastudent.no/wp-content/uploads/2026/10/Protokoll-SP-mote-23.09.26.pdf'),
        array('title' => 'Protokoll SP-møte 20.05.26', 'href' => 'https://stastudent.no/wp-content/uploads/2026/10/Protokoll-SP-mote-20.05.26.doc'),
        array('title' => 'Protokoll Valgforsamling 06.05.26', 'href' => 'https://stastudent.no/wp-content/uploads/2026/10/Protokoll-Valgforsamling-06.05.26.doc'),
        array('title' => 'Protokoll SP-møte 22.04.26', 'href' => 'https://stastudent.no/wp-content/uploads/2026/05/Protokoll-SP-mote-22.04.26.pdf'),
        array('title' => 'Protokoll SP-møte 25.03.26', 'href' => 'https://stastudent.no/wp-content/uploads/2026/05/Protokoll-SP-mote-25.03.26.pdf'),
        array('title' => 'Protokoll SP-møte 25.02.26', 'href' => 'https://stastudent.no/wp-content/uploads/2026/04/Protokoll-SP-mote-25.02.26.pdf'),
        array('title' => 'Protokoll SP-møte 28.01.26', 'href' => 'https://stastudent.no/wp-content/uploads/2026/03/Protokoll-SP-mote-28.01.26.pdf'),
    ),
    '2025' => array(
        array('title' => 'Protokoll SP-møte 19.11.25', 'href' => 'https://stastudent.no/wp-content/uploads/2026/02/Protokoll-SP-mote-19.11.25.pdf'),
        array('title' => 'Protokoll SP-møte 29.10.25', 'href' => 'https://stastudent.no/wp-content/uploads/2025/11/Protokoll-SP-mote-29.10.25.pdf'),
        array('title' => 'Protokoll SP-møte 24.09.25', 'href' => 'https://stastudent.no/wp-content/uploads/2025/11/Protokoll-SP-mote-24.09.25.pdf'),
        array('title' => 'Protokoll SP-møte 21.05.25', 'href' => 'https://stastudent.no/wp-content/uploads/2025/09/Protokoll-SP-mote-21.05.25.pdf'),
        array('title' => 'Protokoll Valgforsamling 07.05.25', 'href' => 'https://stastudent.no/wp-content/uploads/2025/09/Protokoll-Valgforsamling-07.05.25.pdf'),
        array('title' => 'Protokoll SP-møte 23.04.25', 'href' => 'https://stastudent.no/wp-content/uploads/2025/06/Protokoll-SP-mote-23.04.25.pdf'),
        array('title' => 'Protokoll SP-møte 19.03.25', 'href' => 'https://stastudent.no/wp-content/uploads/2025/06/Protokoll-SP-mote-19.03.25.pdf'),
        array('title' => 'Protokoll SP-møte 19.02.25', 'href' => 'https://stastudent.no/wp-content/uploads/2025/06/Protokoll-SP-mote-19.02.25.pdf'),
        array('title' => 'Protokoll SP-møte 22.01.25', 'href' => 'https://stastudent.no/wp-content/uploads/2025/02/Protokoll-SP-mote-22.01.25.pdf'),
    ),
    '2024' => array(
        array('title' => 'Protokoll SP-møte 20.11.24', 'href' => 'https://stastudent.no/wp-content/uploads/2025/01/Protokoll-SP-mote-20.11.24.pdf'),
        array('title' => 'Protokoll SP-møte 23.10.24', 'href' => 'https://stastudent.no/wp-content/uploads/2024/12/Protokoll-SP-mote-23.10.24.pdf'),
        array('title' => 'Protokoll SP-møte 25.09.24', 'href' => 'https://stastudent.no/wp-content/uploads/2024/11/Protokoll-SP-mote-25.09.24.pdf'),
        array('title' => 'Protokoll SP-møte 22.05.24', 'href' => 'https://stastudent.no/wp-content/uploads/2024/09/Protokoll-SP-mote-22.05.24.pdf'),
        array('title' => 'Protokoll Valgforsamling 08.05.24', 'href' => 'https://stastudent.no/wp-content/uploads/2024/09/Protokoll-Valgforsamling-08.05.24.pdf'),
        array('title' => 'Protokoll SP-møte 24.04.24', 'href' => 'https://stastudent.no/wp-content/uploads/2024/05/Protokoll-SP-mote-24.04.24.pdf'),
        array('title' => 'Protokoll SP-møte 20.03.24', 'href' => 'https://stastudent.no/wp-content/uploads/2024/05/Protokoll-SP-mote-20.03.24.pdf'),
        array('title' => 'Protokoll SP-møte 21.02.24', 'href' => 'https://stastudent.no/wp-content/uploads/2024/05/Protokoll-SP-mote-21.02.24.pdf'),
        array('title' => 'Protokoll SP-møte 24.01.24', 'href' => 'https://stastudent.no/wp-content/uploads/2024/05/Protokoll-SP-mote-24.01.24.pdf'),
    ),
    '2023' => array(
        array('title' => 'Protokoll SP-møte 22.11.23', 'href' => 'https://stastudent.no/wp-content/uploads/2024/05/Protokoll-SP-mote-22.11.23.pdf'),
        array('title' => 'Protokoll SP-møte 25.10.23', 'href' => 'https://stastudent.no/wp-content/uploads/2024/05/Protokoll-SP-mote-25.10.23.pdf'),
        array('title' => 'Protokoll SP-møte 27.09.23', 'href' => 'https://stastudent.no/wp-content/uploads/2024/05/Protokoll-SP-mote-27.09.23.pdf'),
        array('title' => 'Protokoll SP-møte 24.05.23', 'href' => 'https://stastudent.no/wp-content/uploads/2024/05/Protokoll-SP-mote-24.05.23.pdf'),
        array('title' => 'Protokoll Valgforsamling 10.05.23', 'href' => 'https://stastudent.no/wp-content/uploads/2026/03/Protokoll-Valgforsamling-10.05.23.pdf'),
        array('title' => 'Protokoll SP-møte 19.04.23', 'href' => 'https://stastudent.no/wp-content/uploads/2023/05/Protokoll-19.04.23.pdf'),
        array('title' => 'Protokoll SP-møte 22.03.23', 'href' => 'https://stastudent.no/wp-content/uploads/2023/04/Protokoll-SP-mote-mars-2023.pdf'),
        array('title' => 'Protokoll SP-møte 22.02.23', 'href' => 'https://stastudent.no/wp-content/uploads/2023/04/Protokoll-SP-mote-februar-2023.pdf'),
        array('title' => 'Protokoll SP-møte 25.01.23', 'href' => 'https://stastudent.no/wp-content/uploads/2023/04/Protokoll-SP-mote-januar-2023.pdf'),
    ),
    '2022' => array(
        array('title' => 'Protokoll SP-møte 23.11.22', 'href' => 'https://stastudent.no/wp-content/uploads/2023/04/Protokoll-SP-mote-november-2022.pdf'),
        array('title' => 'Protokoll SP-møte 19.10.22', 'href' => 'https://stastudent.no/wp-content/uploads/2023/04/Protokoll-SP-mote-oktober-2022.pdf'),
        array('title' => 'Protokoll SP-møte 21.09.22', 'href' => 'https://stastudent.no/wp-content/uploads/2023/04/Protokoll-SP-mote-september-2022.pdf'),
        array('title' => 'Protokoll SP-møte 25.05.22', 'href' => 'https://stastudent.no/wp-content/uploads/2023/04/Protokoll-SP-mote-mai-2022.pdf'),
        array('title' => 'Protokoll Valgforsamling 19.05.22', 'href' => 'https://stastudent.no/wp-content/uploads/2023/04/Protokoll-valgforsamling-mai-2022.pdf'),
        array('title' => 'Protokoll SP-møte 27.04.22', 'href' => 'https://stastudent.no/wp-content/uploads/2023/04/Protokoll-SP-mote-april-2022.pdf'),
        array('title' => 'Protokoll SP-møte 23.03.22', 'href' => 'https://stastudent.no/wp-content/uploads/2022/05/Protokoll-SP-mote-23.03.22.pdf'),
        array('title' => 'Protokoll SP-møte 23.02.22', 'href' => 'https://stastudent.no/wp-content/uploads/2022/05/Protokoll-SP-mote-23.02.22.pdf'),
        array('title' => 'Protokoll SP-møte 26.01.22', 'href' => 'https://stastudent.no/wp-content/uploads/2022/05/Protokoll-SP-mote-26.01.22.pdf'),
    ),
    '2021' => array(
        array('title' => 'Protokoll SP-møte 18.11.21', 'href' => 'https://stastudent.no/wp-content/uploads/2022/01/Protokoll-SP-mote-18.11.21.pdf'),
        array('title' => 'Protokoll SP-møte 20.10.21', 'href' => 'https://stastudent.no/wp-content/uploads/2021/11/Protokoll-SP-mote-20.10.21-1.pdf'),
        array('title' => 'Protokoll SP-møte 22.09.21', 'href' => 'https://stastudent.no/wp-content/uploads/2021/11/Protokoll-SP-mote-22.09.21.pdf'),
        array('title' => 'Protokoll Ekstraordinær Valgforsamling 24.08.21', 'href' => 'https://stastudent.no/wp-content/uploads/2021/09/Protokoll-Ekstraordinaer-Valgforsamling-24.08.21.pdf'),
        array('title' => 'Protokoll SP-møte 26.05.21', 'href' => 'https://stastudent.no/wp-content/uploads/2021/09/Protokoll-SP-mote-26.05.21.pdf'),
        array('title' => 'Protokoll Valgforsamling 19.05.21', 'href' => 'https://stastudent.no/wp-content/uploads/2021/09/Protokoll-Valgforsamling-19.05.21.pdf'),
        array('title' => 'Protokoll SP-møte 21.04.21', 'href' => 'https://stastudent.no/wp-content/uploads/2021/09/Protokoll-SP-mote-21.04.21.doc'),
        array('title' => 'Protokoll SP-møte 24.03.21', 'href' => 'https://stastudent.no/wp-content/uploads/2021/09/Protokoll-SP-mote-24.03.21.doc'),
        array('title' => 'Protokoll SP-møte 24.02.21', 'href' => 'https://stastudent.no/wp-content/uploads/2021/03/Protokoll-SP-mote-24.02.21.doc'),
        array('title' => 'Protokoll SP-møte 27.01.21', 'href' => 'https://stastudent.no/wp-content/uploads/2021/03/Protokoll-SP-mote-27.01.21.doc'),
    ),
    '2020' => array(
        array('title' => 'Protokoll SP-møte 25.11.20', 'href' => 'https://stastudent.no/wp-content/uploads/2021/02/Protokoll-SP-mote-25.11.20.doc'),
        array('title' => 'Protokoll SP-møte 28.10.20', 'href' => 'https://stastudent.no/wp-content/uploads/2020/11/Protokoll-SP-mote-28.10.20.doc'),
        array('title' => 'Protokoll Ekstraordinær Valgforsamling 14.10.20', 'href' => 'https://stastudent.no/wp-content/uploads/2020/11/Protokoll-Ekstraordinaer-Valgforsamling-14.10.20.doc'),
        array('title' => 'Protokoll SP-møte 23.09.20', 'href' => 'https://stastudent.no/wp-content/uploads/2020/11/Protokoll-SP-mote-23.09.20.doc'),
        array('title' => 'Protokoll SP-møte 27.05.20', 'href' => 'https://stastudent.no/wp-content/uploads/2020/11/Protokoll-SP-mote-27.05.20.doc'),
        array('title' => 'Protokoll Valgforsamling 20.05.20', 'href' => 'https://stastudent.no/wp-content/uploads/2020/11/Protokoll-Valgforsamling-20.05.20.doc'),
    ),
);

$wp_page = get_page_by_path('dokumenter/referat-fra-moter-i-studentparlamentet', OBJECT, 'page');
if (!$wp_page) {
    $wp_page = get_page_by_path('referat-fra-moter-i-studentparlamentet', OBJECT, 'page');
}
if (!$wp_page) {
    $found_posts = get_posts(array('name' => 'referat-fra-moter-i-studentparlamentet', 'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => 1));
    if (!empty($found_posts)) {
        $wp_page = $found_posts[0];
    }
}

$groups = sta_merge_editable_year_docs($wp_page ? $wp_page->post_content : '', $default_groups);
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <!-- Breadcrumb / Back Link -->
    <div style="margin-bottom:1.5rem; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:1rem;">
        <a href="<?php echo esc_url(sta_get_localized_url('/dokumenter')); ?>" style="display:inline-flex; align-items:center; gap:0.5rem; color:var(--sta-teal-200); font-size:0.9rem; font-weight:600; text-decoration:none;">
            <span aria-hidden="true">&larr;</span>
            <span><?php echo esc_html(sta_t('Tilbake til Dokumenter', 'Back to Documents', 'Tilbake til Dokument')); ?></span>
        </a>

        <?php if ($wp_page && current_user_can('edit_post', $wp_page->ID)): ?>
            <a href="<?php echo esc_url(get_edit_post_link($wp_page->ID)); ?>" class="sta-btn sta-btn-outline" style="padding:0.4rem 0.9rem; font-size:0.8rem;">
                <?php echo esc_html(sta_t('Rediger protokoller i WordPress', 'Edit protocols in WordPress', 'Rediger protokollar i WordPress')); ?>
            </a>
        <?php endif; ?>
    </div>

    <header class="sta-page-header">
        <span class="sta-badge"><?php echo esc_html(sta_t('Studentparlamentet', 'The Student Parliament', 'Studentparlamentet')); ?></span>
        <h1 class="sta-hero-title" style="font-size:clamp(2rem, 5vw, 2.75rem); margin-top:0.5rem;">
            <?php echo esc_html(sta_t('Protokoller fra møter i Studentparlamentet', 'Minutes & Protocols from the Student Parliament', 'Protokollar frå møte i Studentparlamentet')); ?>
        </h1>
        <p style="color:var(--sta-teal-100); font-size:1.1rem; max-width:760px; margin:0.5rem auto 0 auto;">
            <?php echo esc_html(sta_t(
                'Her finner du vedtatte protokoller og referater fra møter og valgforsamlinger i Studentparlamentet.',
                'Here you will find adopted minutes and protocols from meetings and election assemblies in the Student Parliament.',
                'Her finn du vedtekne protokollar og referat frå møte og valforsamlingar i Studentparlamentet.'
            )); ?>
        </p>
    </header>

    <!-- Sub-navigation tabs between Dokumenter, Sakspapirer, and Protokoller -->
    <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:0.75rem; margin-bottom:3rem;">
        <a href="<?php echo esc_url(sta_get_localized_url('/dokumenter')); ?>" class="sta-filter-btn" style="text-decoration:none;">
            <?php echo esc_html(sta_t('Styringsdokumenter & resolusjoner', 'Policy Documents & Resolutions', 'Styringsdokument & resolusjonar')); ?>
        </a>
        <a href="<?php echo esc_url(sta_get_localized_url('/sakspapirer')); ?>" class="sta-filter-btn" style="text-decoration:none;">
            <?php echo esc_html(sta_t('Sakspapirer (SP-møter)', 'Meeting Papers (Agenda)', 'Sakspapir (SP-møte)')); ?>
        </a>
        <a href="<?php echo esc_url(sta_get_localized_url('/referat-fra-moter-i-studentparlamentet')); ?>" class="sta-filter-btn is-active" style="text-decoration:none;">
            <?php echo esc_html(sta_t('Protokoller fra SP-møter', 'Minutes & Protocols', 'Protokollar frå SP-møte')); ?>
        </a>
    </div>

    <!-- Year Cards Grid -->
    <div class="sta-year-docs-grid">
        <?php foreach ($groups as $year => $items): ?>
            <div class="sta-card" style="padding:1.5rem 1.75rem;">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:0.75rem; margin-bottom:1rem; padding-bottom:0.65rem; border-bottom:1px solid rgba(158,207,201,0.22);">
                    <h2 style="font-size:1.35rem; font-weight:800; color:var(--sta-orange); margin:0;">
                        <?php echo esc_html($year); ?>
                    </h2>
                    <span class="sta-badge" style="margin:0; font-size:0.7rem; padding:0.2rem 0.55rem; background:rgba(255,255,255,0.06); border:1px solid rgba(158,207,201,0.2); color:var(--sta-teal-100);">
                        <?php printf(esc_html(sta_t('%d protokoller', '%d protocols', '%d protokollar')), count($items)); ?>
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
