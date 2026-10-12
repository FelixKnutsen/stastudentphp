<?php
/**
 * Front Page Template
 * Faithful reproduction of the Next.js HomePage (Hero, Verv CTA, QuickNav, NewsHighlight, Featured, Partners)
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
$is_en = sta_is_en();
?>

<!-- 1. Hero Banner Image -->
<section style="padding: 1.5rem 0 1rem;">
    <div class="sta-container">
        <div class="sta-hero-banner-box">
            <img src="https://stastudent.no/wp-content/uploads/2025/11/IMG_4781_CROP-scaled.jpg" 
                 alt="<?php echo esc_attr(sta_t('Studentorganisasjonen i Agder', 'Student Organisation of Agder')); ?>" 
                 class="sta-hero-banner-img"
                 loading="eager">
        </div>
    </div>
</section>

<!-- 2. Verv CTA Section -->
<section style="padding: 1rem 0;">
    <div class="sta-container">
        <div class="sta-verv-banner">
            <div>
                <h2 class="sta-verv-banner-title">
                    <?php echo esc_html(sta_t('Engasjer deg - still til verv!', 'Get involved - run for a position!')); ?>
                </h2>
                <p class="sta-verv-banner-sub">
                    <?php echo esc_html(sta_t('Bli med og gjør en forskjell for over 14 000 studenter ved Universitetet i Agder.', 'Join in and make a difference for over 14,000 students at the University of Agder.')); ?>
                </p>
            </div>
            <a href="<?php echo esc_url(sta_get_localized_url('/ledige-verv')); ?>" class="sta-btn sta-btn-orange" style="font-size:1rem; padding:0.85rem 1.75rem; shrink-0:0; white-space:nowrap;">
                <span><?php echo esc_html(sta_t('Ledige verv', 'Vacant positions')); ?></span>
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="margin-left:0.5rem;" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>
</section>

<!-- 3. Quick Nav Grid (4 Cards) -->
<section style="padding: 2.5rem 0;">
    <div class="sta-container">
        <div class="sta-quick-grid">
            <!-- Linjeforeninger -->
            <a href="<?php echo esc_url(sta_get_localized_url('/linjeforeninger')); ?>" class="sta-quick-card">
                <div class="sta-quick-icon-box">
                    <svg viewBox="0 0 24 24" width="32" height="32" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                </div>
                <h3 class="sta-quick-title"><?php echo esc_html(sta_t('Linjeforeninger', 'Academic Associations')); ?></h3>
                <p class="sta-quick-desc"><?php echo esc_html(sta_t('Møt studentene på ditt studieprogram og delta på sosiale og faglige aktiviteter.', 'Meet students in your study programme and join social and academic activities.')); ?></p>
            </a>

            <!-- Lokaler -->
            <a href="<?php echo esc_url(sta_get_localized_url('/lokaler')); ?>" class="sta-quick-card">
                <div class="sta-quick-icon-box">
                    <svg viewBox="0 0 24 24" width="32" height="32" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                </div>
                <h3 class="sta-quick-title"><?php echo esc_html(sta_t('Lokaler', 'Our Premises')); ?></h3>
                <p class="sta-quick-desc"><?php echo esc_html(sta_t('Lei eller lån lokaler til foreningsmøter, kurs eller studentarrangementer.', 'Book venues for association meetings, courses, or student events.')); ?></p>
            </a>

            <!-- Tillitsvalgte -->
            <a href="<?php echo esc_url(sta_get_localized_url('/for-tillitsvalgte')); ?>" class="sta-quick-card">
                <div class="sta-quick-icon-box">
                    <svg viewBox="0 0 24 24" width="32" height="32" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <h3 class="sta-quick-title"><?php echo esc_html(sta_t('Tillitsvalgt', 'Representatives')); ?></h3>
                <p class="sta-quick-desc"><?php echo esc_html(sta_t('Ressurser, opplæring og verktøy for tillitsvalgte og studentrepresentanter.', 'Resources, training, and tools for student representatives.')); ?></p>
            </a>

            <!-- Studentaktiviteter -->
            <a href="<?php echo esc_url(sta_get_localized_url('/studentaktiviteter')); ?>" class="sta-quick-card">
                <div class="sta-quick-icon-box">
                    <svg viewBox="0 0 24 24" width="32" height="32" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                </div>
                <h3 class="sta-quick-title"><?php echo esc_html(sta_t('Studentaktiviteter', 'Student Activities')); ?></h3>
                <p class="sta-quick-desc"><?php echo esc_html(sta_t('Idrettslag, kulturklubber og studentorganisasjoner ved campus.', 'Sports teams, cultural clubs, and student organisations on campus.')); ?></p>
            </a>
        </div>
    </div>
</section>

<!-- 4. News Highlight Section (SISTE NYTT) -->
<?php
$latest_news_query = new WP_Query(array(
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC'
));

$news_items = array();
if ($latest_news_query->have_posts()) {
    while ($latest_news_query->have_posts()) {
        $latest_news_query->the_post();
        if (get_post_field('post_name', get_the_ID()) === 'hello-world') {
            continue;
        }
        $cats = get_the_category();
        $cat_name = (!empty($cats) && $cats[0]->name !== 'Uncategorized') ? $cats[0]->name : (sta_is_en() ? 'News' : 'Nyheter');
        $news_items[] = array(
            'title'        => get_the_title(),
            'url'          => get_permalink(),
            'date'         => sta_format_date(),
            'category'     => $cat_name,
            'excerpt'      => get_the_excerpt(),
            'thumbnail'    => get_the_post_thumbnail_url(get_the_ID(), 'medium_large') ?: (get_post_meta(get_the_ID(), '_sta_thumbnail_url', true) ?: '')
        );
    }
    wp_reset_postdata();
}

// Fallback to JSON if no WP posts exist
if (empty($news_items)) {
    $news_file = sta_get_data_file('news_archive.json');
    if ($news_file && file_exists($news_file)) {
        $raw = json_decode(file_get_contents($news_file), true);
        if (is_array($raw)) {
            $slice = array_slice($raw, 0, 3);
            foreach ($slice as $n) {
                $news_items[] = array(
                    'title'     => $n['title'] ?? '',
                    'url'       => sta_get_localized_url('/nyheter/' . ($n['slug'] ?? '')),
                    'date'      => sta_format_date($n['publishedAt'] ?? 'now'),
                    'category'  => $n['category'] ?? (sta_is_en() ? 'News' : 'Nyheter'),
                    'excerpt'   => $n['excerpt'] ?? '',
                    'thumbnail' => $n['imageUrl'] ?? ''
                );
            }
        }
    }
}
?>

<section style="padding: 3rem 0; background: rgba(4, 24, 23, 0.4);">
    <div class="sta-container">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:2rem;">
            <a href="<?php echo esc_url(sta_get_localized_url('/nyheter')); ?>" style="font-size:1.75rem; font-weight:800; color:#fff; letter-spacing:0.05em;">
                <?php echo esc_html(sta_t('SISTE NYTT', 'LATEST NEWS')); ?>
            </a>
            <a href="<?php echo esc_url(sta_get_localized_url('/nyheter')); ?>" style="display:flex; align-items:center; gap:0.35rem; color:var(--sta-orange); font-size:0.85rem; font-weight:700;">
                <span><?php echo esc_html(sta_t('Se alle nyheter', 'View all news')); ?></span>
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:1.5rem;">
            <?php foreach ($news_items as $article): ?>
                <article class="sta-card" style="padding:0; overflow:hidden; display:flex; flex-direction:column; justify-content:space-between;">
                    <div style="position:relative; aspect-ratio:16/10; width:100%; background:var(--sta-teal-900); overflow:hidden;">
                        <?php if (!empty($article['thumbnail'])): ?>
                            <img src="<?php echo esc_url($article['thumbnail']); ?>" alt="<?php echo esc_attr($article['title']); ?>" style="width:100%; height:100%; object-fit:cover;">
                        <?php endif; ?>
                        <div style="position:absolute; top:0.75rem; left:0.75rem;">
                            <span class="sta-badge" style="background:var(--sta-orange); color:#fff; border:none; font-size:0.7rem; padding:0.25rem 0.6rem;">
                                <?php echo esc_html($article['category']); ?>
                            </span>
                        </div>
                    </div>

                    <div style="padding:1.5rem; flex:1; display:flex; flex-direction:column; justify-content:space-between;">
                        <div>
                            <div style="display:flex; align-items:center; gap:0.4rem; font-size:0.8rem; color:var(--sta-teal-200); margin-bottom:0.75rem;">
                                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                <time><?php echo esc_html($article['date']); ?></time>
                            </div>
                            <h3 style="font-size:1.15rem; font-weight:700; color:#fff; margin:0 0 0.5rem; line-height:1.4;">
                                <a href="<?php echo esc_url($article['url']); ?>" style="color:#fff;">
                                    <?php echo esc_html($article['title']); ?>
                                </a>
                            </h3>
                            <p style="font-size:0.85rem; color:var(--sta-teal-100); line-height:1.6; margin:0; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;">
                                <?php echo esc_html($article['excerpt']); ?>
                            </p>
                        </div>
                        <div style="margin-top:1.25rem; padding-top:1rem; border-top:1px solid rgba(158,207,201,0.2);">
                            <a href="<?php echo esc_url($article['url']); ?>" style="font-size:0.85rem; font-weight:700; color:var(--sta-orange); display:inline-flex; align-items:center; gap:0.25rem;">
                                <span><?php echo esc_html(sta_t('Les hele saken', 'Read full article')); ?> &rarr;</span>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 5. Featured Section (Card 1: Studentparlamentet, Card 2: UiA Karriereuka) -->
<section style="padding: 3.5rem 0;">
    <div class="sta-container">
        <!-- Feature 1: Studentparlamentet -->
        <div class="sta-feature-card">
            <div class="sta-feature-img-box">
                <img src="https://stastudent.no/wp-content/uploads/2025/11/IMG_4780-1024x683.jpg" 
                     alt="Studentparlamentet" 
                     loading="lazy">
            </div>
            <div class="sta-feature-content">
                <h2><?php echo esc_html(sta_t('Studentparlamentet', 'The Student Parliament')); ?></h2>
                <p class="sta-feature-subtitle">
                    <?php echo esc_html(sta_t('UiAs øverste studentrepresentanter​', "UiA's highest student representatives")); ?>
                </p>
                <p class="sta-feature-text">
                    <?php echo esc_html(sta_t('Studentparlamentet består av 25 folkevalgte representanter som fatter de overordnede politiske beslutningene og velger STA-styret.', 'The Student Parliament consists of 25 elected student representatives who make the supreme political decisions and elect the STA Board.')); ?>
                </p>
                <a href="<?php echo esc_url(sta_get_localized_url('/studentparlamentet')); ?>" class="sta-btn sta-btn-orange">
                    <span><?php echo esc_html(sta_t('Les mer!', 'Read more!')); ?></span>
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="margin-left:0.35rem;" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
        </div>

        <!-- Feature 2: UiA Karriereuka -->
        <div class="sta-feature-card">
            <div class="sta-feature-img-box">
                <img src="https://stastudent.no/wp-content/uploads/2026/02/DSC05899-–-middels.jpeg" 
                     alt="UiA Karriereuka" 
                     loading="lazy">
            </div>
            <div class="sta-feature-content">
                <h2><?php echo esc_html(sta_t('UiA Karriereuka', 'UiA Career Week')); ?></h2>
                <p class="sta-feature-subtitle">
                    <?php echo esc_html(sta_t('Studentenes arena for kontakt med næringslivet', 'The students arena for contact with business life')); ?>
                </p>
                <p class="sta-feature-text">
                    <?php echo esc_html(sta_t('Møt fremtidige arbeidsgivere, delta på workshops og bygg nettverk for livet etter studiene.', 'Meet future employers, join practical workshops, and build a lasting network for life after graduation.')); ?>
                </p>
                <a href="https://uiakarriereuka.no/" target="_blank" rel="noopener noreferrer" class="sta-btn sta-btn-orange">
                    <span><?php echo esc_html(sta_t('Les mer!', 'Read more!')); ?></span>
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="margin-left:0.35rem;" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- 6. Collaborating Partners Section -->
<?php sta_render_partner_logos(); ?>

<?php
get_footer();
