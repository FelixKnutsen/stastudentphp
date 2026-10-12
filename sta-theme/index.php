<?php
/**
 * Main Index & News Archive Template
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
$is_en = sta_is_en();
$is_nn = sta_is_nn();
?>

<div class="sta-container" style="padding-top:3rem; padding-bottom:5rem;">
    <header class="sta-page-header" style="margin-bottom:2.5rem;">
        <span class="sta-badge"><?php echo esc_html(sta_t('Aktuelt', 'News Archive', 'Aktuelt')); ?></span>
        <h1 class="sta-hero-title" style="font-size:2.5rem; margin-top:0.5rem;">
            <?php
            if (is_category()) {
                single_cat_title();
            } elseif (is_tag()) {
                single_tag_title();
            } elseif (is_archive()) {
                the_archive_title();
            } else {
                echo esc_html(sta_t('Siste nytt fra STA', 'Latest news from STA', 'Siste nytt frå STA'));
            }
            ?>
        </h1>
        <p style="color:var(--sta-teal-100); font-size:1.1rem; max-width:700px;">
            <?php echo esc_html(sta_t('Følg med på oppdateringer, resolusjoner, studentvalg og pressemeldinger fra Studentorganisasjonen i Agder.', 'Stay updated with news, resolutions, student elections and press releases from the Student Organisation of Agder.', 'Følg med på oppdateringar, resolusjonar, studentval og pressemeldingar frå Studentorganisasjonen i Agder.')); ?>
        </p>
    </header>

    <?php if (have_posts()): ?>
        <div class="sta-news-grid">
            <?php while (have_posts()): the_post(); ?>
                <?php get_template_part('template-parts/news-card'); ?>
            <?php endwhile; ?>
        </div>

        <?php sta_render_pagination(); ?>

    <?php else: ?>
        <div style="background:var(--sta-card-bg); border:1px solid var(--sta-card-border); border-radius:var(--sta-radius); padding:3rem; text-align:center;">
            <p style="color:var(--sta-teal-200); font-size:1.2rem;"><?php echo esc_html(sta_t('Ingen artikler funnet i dette arkivet.', 'No articles found in this archive.', 'Ingen artiklar funne i dette arkivet.')); ?></p>
            <a href="<?php echo esc_url(sta_get_localized_url('/')); ?>" class="sta-btn sta-btn-orange" style="margin-top:1rem;">
                <?php echo esc_html(sta_t('Tilbake til forsiden', 'Back to home', 'Attende til framsida')); ?>
            </a>
        </div>
    <?php endif; ?>
</div>

<?php
get_footer();
