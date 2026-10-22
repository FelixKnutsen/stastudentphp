<?php
/**
 * Single News Article Template
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
$is_en = sta_is_en();
$is_nn = sta_is_nn();
?>

<div class="sta-container" style="padding-top:3rem; padding-bottom:5rem;">
    <?php while (have_posts()): the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php body_class('sta-article'); ?> style="max-width:840px; margin:0 auto;">
            <!-- Back navigation link -->
            <div style="margin-bottom:1.5rem;">
                <a href="<?php echo esc_url(sta_get_localized_url('/category/nyheter')); ?>" style="color:var(--sta-teal-200); font-size:0.9rem; display:inline-flex; align-items:center; gap:0.35rem;">
                    &larr; <?php echo esc_html(sta_t('Tilbake til alle nyheter', 'Back to all news', 'Attende til alle nyheiter')); ?>
                </a>
            </div>

            <!-- Meta & Title -->
            <header style="margin-bottom:2rem;">
                <?php
                $categories = get_the_category();
                $fallback_cat = $is_en ? 'News' : ($is_nn ? 'Nyheiter' : 'Nyheter');
                $cat_title = (!empty($categories) && $categories[0]->name !== 'Uncategorized') ? $categories[0]->name : $fallback_cat;
                ?>
                <span class="sta-badge" style="margin-bottom:0.75rem;">
                    <?php echo esc_html($cat_title); ?>
                </span>

                <h1 style="font-size:2.5rem; font-weight:900; line-height:1.2; margin:0 0 1rem; color:#fff;">
                    <?php the_title(); ?>
                </h1>

                <div style="display:flex; align-items:center; gap:1.5rem; color:var(--sta-teal-200); font-size:0.9rem;">
                    <time datetime="<?php echo get_the_date('c'); ?>">
                        <?php echo esc_html(sta_format_date()); ?>
                    </time>
                    <span>&bull;</span>
                    <span><?php echo esc_html(get_the_author()); ?></span>
                </div>
            </header>

            <!-- Featured Image -->
            <?php 
            $single_thumb = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: get_post_meta(get_the_ID(), '_sta_thumbnail_url', true);
            if (!empty($single_thumb)): 
            ?>
                <div style="border-radius:var(--sta-radius); overflow:hidden; margin-bottom:2.5rem; background:var(--sta-teal-800); border:1px solid var(--sta-card-border); box-shadow:0 12px 32px rgba(0,0,0,0.3);">
                    <img src="<?php echo esc_url($single_thumb); ?>" alt="<?php the_title_attribute(); ?>" style="width:100%; height:auto; display:block;">
                </div>
            <?php endif; ?>

            <!-- Article Body -->
            <div class="sta-article-content" style="font-size:1.15rem; line-height:1.8; color:var(--sta-teal-50);">
                <?php the_content(); ?>
            </div>

            <!-- Article Footer CTA -->
            <footer style="margin-top:3.5rem; padding-top:2rem; border-top:1px solid rgba(255,255,255,0.1); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
                <a href="<?php echo esc_url(sta_get_localized_url('/category/nyheter')); ?>" class="sta-btn sta-btn-outline">
                    &larr; <?php echo esc_html(sta_t('Flere nyheter', 'More news', 'Fleire nyheiter')); ?>
                </a>
                <a href="#main-content" style="color:var(--sta-teal-200); font-size:0.9rem;">
                    <?php echo esc_html(sta_t('Til toppen &uarr;', 'Back to top &uarr;', 'Til toppen &uarr;')); ?>
                </a>
            </footer>
        </article>
    <?php endwhile; ?>
</div>

<?php
get_footer();
