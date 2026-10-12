<?php
/**
 * Search Results Template
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
$search_query = get_search_query();
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <header style="margin-bottom:3rem;">
        <span class="sta-badge"><?php echo esc_html(sta_t('Søkeresultater', 'Search Results', 'Søkeresultat')); ?></span>
        <h1 style="font-size:2.5rem; font-weight:900; margin:0.5rem 0 1rem; color:#fff;">
            <?php printf(esc_html(sta_t('Resultater for: "%s"', 'Results for: "%s"', 'Resultat for: "%s"')), esc_html($search_query)); ?>
        </h1>
        <p style="color:var(--sta-teal-100);">
            <?php
            global $wp_query;
            printf(
                esc_html(sta_t('Fant %d treff på nettstedet.', 'Found %d matches on the website.', 'Fann %d treff på nettstaden.')),
                $wp_query->found_posts
            );
            ?>
        </p>
    </header>

    <?php if (have_posts()): ?>
        <div class="sta-news-grid">
            <?php while (have_posts()): the_post(); ?>
                <article class="sta-card" style="display:flex; flex-direction:column; justify-content:space-between;">
                    <div>
                        <span class="sta-badge" style="font-size:0.7rem; padding:0.2rem 0.5rem; margin-bottom:0.5rem;">
                            <?php echo esc_html(get_post_type_object(get_post_type())->labels->singular_name ?? (sta_is_en() ? 'Page' : (sta_is_nn() ? 'Side' : 'Side'))); ?>
                        </span>
                        <h2 class="sta-card-title" style="font-size:1.3rem;">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <div class="sta-card-desc">
                            <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                        </div>
                    </div>
                    <div style="margin-top:1.5rem;">
                        <a href="<?php the_permalink(); ?>" class="sta-btn sta-btn-outline" style="font-size:0.85rem; padding:0.4rem 0.85rem;">
                            <?php echo esc_html(sta_t('Les mer &rarr;', 'Read more &rarr;', 'Les meir &rarr;')); ?>
                        </a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <?php sta_render_pagination(); ?>

    <?php else: ?>
        <div style="background:var(--sta-card-bg); border:1px solid var(--sta-card-border); border-radius:var(--sta-radius); padding:3rem; text-align:center; max-width:600px; margin:0 auto;">
            <p style="color:var(--sta-teal-200); font-size:1.2rem; margin-bottom:1.5rem;">
                <?php echo esc_html(sta_t('Ingen resultater matchet søkeordet ditt. Prøv et annet søk.', 'No results matched your search query. Please try another search term.', 'Ingen resultat passa til søkeordet ditt. Prøv eit anna søk.')); ?>
            </p>
            <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="sta-search-box" style="margin:0 auto 1.5rem;">
                <input type="search" placeholder="<?php echo esc_attr(sta_t('Søk på stastudent.no...', 'Search stastudent.no...', 'Søk på stastudent.no...')); ?>" name="s" value="<?php echo esc_attr($search_query); ?>">
                <?php if (sta_is_en()): ?><input type="hidden" name="lang" value="en"><?php elseif (sta_is_nn()): ?><input type="hidden" name="lang" value="nn"><?php endif; ?>
            </form>
            <a href="<?php echo esc_url(sta_get_localized_url('/')); ?>" class="sta-btn sta-btn-orange">
                <?php echo esc_html(sta_t('Tilbake til forsiden', 'Back to home', 'Attende til framsida')); ?>
            </a>
        </div>
    <?php endif; ?>
</div>

<?php
get_footer();
