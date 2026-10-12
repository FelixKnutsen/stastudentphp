<?php
/**
 * Standard Page Template
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <?php while (have_posts()): the_post(); ?>
        <article id="page-<?php the_ID(); ?>" <?php post_class('sta-page-content'); ?> style="max-width:900px; margin:0 auto;">
            <header class="sta-page-header">
                <h1 class="sta-hero-title">
                    <?php the_title(); ?>
                </h1>
            </header>

            <?php if (has_post_thumbnail()): ?>
                <div style="border-radius:var(--sta-radius); overflow:hidden; margin-bottom:2.5rem; background:var(--sta-teal-800);">
                    <?php the_post_thumbnail('large', array('style' => 'width:100%; height:auto; display:block;')); ?>
                </div>
            <?php endif; ?>

            <div class="sta-entry-content" style="font-size:1.15rem; line-height:1.8; color:var(--sta-teal-50);">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</div>

<?php
get_footer();
