<?php
/**
 * News Card Template Part
 */

if (!defined('ABSPATH')) {
    exit;
}

$categories = get_the_category();
$category_name = (!empty($categories) && $categories[0]->name !== 'Uncategorized') ? $categories[0]->name : (sta_is_en() ? 'News' : 'Nyheter');
?>
<article class="sta-news-card">
    <?php 
    $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'sta-card') ?: get_post_meta(get_the_ID(), '_sta_thumbnail_url', true);
    ?>
    <a href="<?php the_permalink(); ?>" class="sta-news-thumb" aria-hidden="true" tabindex="-1">
        <?php if (!empty($thumb_url)): ?>
            <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" style="width:100%; height:100%; object-fit:cover;">
        <?php else: ?>
            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:var(--sta-teal-800); color:var(--sta-teal-300); font-weight:700;">
                STA
            </div>
        <?php endif; ?>
    </a>

    <div class="sta-news-body">
        <div class="sta-news-meta">
            <span class="sta-badge" style="font-size:0.7rem; padding:0.2rem 0.5rem; margin-bottom:0.4rem;">
                <?php echo esc_html($category_name); ?>
            </span>
            <time datetime="<?php echo get_the_date('c'); ?>" style="display:block; margin-top:2px;">
                <?php echo esc_html(sta_format_date()); ?>
            </time>
        </div>

        <h3 class="sta-news-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <div class="sta-news-excerpt">
            <?php echo wp_trim_words(get_the_excerpt(), 18, '...'); ?>
        </div>
    </div>
</article>
