<?php
/**
 * 404 Error Page Template
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="sta-container" style="padding-top:5rem; padding-bottom:6rem; text-align:center;">
    <div style="max-width:600px; margin:0 auto;">
        <span class="sta-badge" style="background:#ef7330;">404</span>
        <h1 style="font-size:3rem; font-weight:900; margin:1rem 0; color:#fff;">
            <?php echo esc_html(sta_t('Siden ble ikke funnet', 'Page not found')); ?>
        </h1>
        <p style="color:var(--sta-teal-100); font-size:1.15rem; margin-bottom:2rem;">
            <?php echo esc_html(sta_t('Beklager, siden du leter etter eksisterer ikke eller har blitt flyttet.', 'Sorry, the page you are looking for does not exist or has been moved.')); ?>
        </p>

        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="sta-search-box" style="margin:0 auto 2rem;">
            <input type="search" placeholder="<?php echo esc_attr(sta_t('Søk etter innhold...', 'Search for content...')); ?>" name="s">
        </form>

        <a href="<?php echo esc_url(sta_get_localized_url('/')); ?>" class="sta-btn sta-btn-orange">
            <?php echo esc_html(sta_t('Gå til forsiden', 'Go to home')); ?>
        </a>
    </div>
</div>

<?php
get_footer();
