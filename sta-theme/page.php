<?php
/**
 * Standard Page Template
 * Supports English and Nynorsk translations for generic pages (om-sta, engasjer-deg, for-foreninger).
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
$is_en = sta_is_en();
$is_nn = sta_is_nn();
$post_slug = get_post_field('post_name', get_the_ID());
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <?php while (have_posts()): the_post(); ?>
        <?php if ($post_slug === 'om-sta' || $post_slug === 'om-oss' || $post_slug === 'om-oss-2'): ?>
            <header class="sta-page-header" style="text-align:center; max-width:800px; margin:0 auto 3rem;">
                <span class="sta-badge"><?php echo esc_html(sta_t('Om STA', 'About STA', 'Om STA')); ?></span>
                <h1 class="sta-hero-title" style="margin-top:0.5rem; font-size:2.75rem;">
                    <?php echo esc_html(sta_t('Om Studentorganisasjonen i Agder', 'About the Student Organisation of Agder', 'Om Studentorganisasjonen i Agder')); ?>
                </h1>
                <p style="color:var(--sta-teal-100); font-size:1.15rem; margin-top:0.75rem;">
                    <?php echo esc_html(sta_t('STA er interesseorganisasjonen for studentene ved Universitetet i Agder.', 'STA is the student interest organisation at the University of Agder.', 'STA er studentane sin interesseorganisasjon ved Universitetet i Agder.')); ?>
                </p>
            </header>

            <div class="sta-quick-grid" style="grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.5rem;">
                <a href="<?php echo esc_url(sta_get_localized_url('/sta-styret')); ?>" class="sta-quick-card">
                    <div class="sta-quick-icon-box">
                        <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <h3 class="sta-quick-title"><?php echo esc_html(sta_t('STA-styret', 'The STA-board', 'STA-styret')); ?></h3>
                    <p class="sta-quick-desc"><?php echo esc_html(sta_t('Arbeidsutvalget og tillitsvalgte som jobber fulltid for studentene.', 'The executive committee and officers working full-time for students.', 'Arbeidsutvalet og tillitsvalde som jobbar heiltid for studentane.')); ?></p>
                </a>

                <a href="<?php echo esc_url(sta_get_localized_url('/studentparlamentet')); ?>" class="sta-quick-card">
                    <div class="sta-quick-icon-box">
                        <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="3" y1="21" x2="21" y2="21"></line><line x1="6" y1="21" x2="6" y2="9"></line><line x1="18" y1="21" x2="18" y2="9"></line><path d="M2 9l10-7 10 7"></path></svg>
                    </div>
                    <h3 class="sta-quick-title"><?php echo esc_html(sta_t('Studentparlamentet', 'The Student Parliament', 'Studentparlamentet')); ?></h3>
                    <p class="sta-quick-desc"><?php echo esc_html(sta_t('Det øverste studentpolitiske organet ved Universitetet i Agder.', 'The highest student political body at the University of Agder.', 'Det høgste studentpolitiske organet ved Universitetet i Agder.')); ?></p>
                </a>

                <a href="<?php echo esc_url(sta_get_localized_url('/linjeforeninger')); ?>" class="sta-quick-card">
                    <div class="sta-quick-icon-box">
                        <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                    </div>
                    <h3 class="sta-quick-title"><?php echo esc_html(sta_t('Linjeforeninger', 'Academic associations', 'Linjeforeiningar')); ?></h3>
                    <p class="sta-quick-desc"><?php echo esc_html(sta_t('Oversikt over alle aktive linjeforeninger i Kristiansand og Grimstad.', 'Overview of all active student associations in Kristiansand and Grimstad.', 'Oversikt over alle aktive linjeforeiningar i Kristiansand og Grimstad.')); ?></p>
                </a>

                <a href="<?php echo esc_url(sta_get_localized_url('/studentaktiviteter')); ?>" class="sta-quick-card">
                    <div class="sta-quick-icon-box">
                        <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="m4.93 4.93 4.24 4.24"></path><path d="m14.83 9.17 4.24-4.24"></path><path d="m14.83 14.83 4.24 4.24"></path><path d="m9.17 14.83-4.24 4.24"></path><circle cx="12" cy="12" r="4"></circle></svg>
                    </div>
                    <h3 class="sta-quick-title"><?php echo esc_html(sta_t('Studentaktiviteter', 'Student Activities', 'Studentaktivitetar')); ?></h3>
                    <p class="sta-quick-desc"><?php echo esc_html(sta_t('Fritidsaktiviteter, idrett og kultur utenfor lesesalen.', 'Student societies, sports and culture outside the classroom.', 'Fritidsaktivitetar, idrett og kultur utanfor førelesingssalen.')); ?></p>
                </a>

                <a href="<?php echo esc_url(sta_get_localized_url('/lokaler')); ?>" class="sta-quick-card">
                    <div class="sta-quick-icon-box">
                        <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect><line x1="9" y1="22" x2="9" y2="2"></line><line x1="15" y1="22" x2="15" y2="2"></line></svg>
                    </div>
                    <h3 class="sta-quick-title"><?php echo esc_html(sta_t('Lokaler', 'Our premises', 'Lokale')); ?></h3>
                    <p class="sta-quick-desc"><?php echo esc_html(sta_t('Møterom og lokaler tilgjengelig for studenter og foreninger.', 'Meeting rooms and venues available for students and associations.', 'Møterom og lokale tilgjengelege for studentar og foreiningar.')); ?></p>
                </a>

                <a href="<?php echo esc_url(sta_get_localized_url('/irene-bredals-hederspris')); ?>" class="sta-quick-card">
                    <div class="sta-quick-icon-box">
                        <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                    </div>
                    <h3 class="sta-quick-title"><?php echo esc_html(sta_t('Irene Bredals Hederspris', 'Irene Bredals Hederspris', 'Irene Bredals Heiderspris')); ?></h3>
                    <p class="sta-quick-desc"><?php echo esc_html(sta_t('STAs hederspris for studenter som utmerker seg med ekstraordinært engasjement.', "STA's honour award for students showing extraordinary dedication.", 'STA sin heiderspris for studentar som syner ekstraordinært engasjement.')); ?></p>
                </a>
            </div>

        <?php elseif ($post_slug === 'engasjer-deg'): ?>
            <article id="page-<?php the_ID(); ?>" <?php post_class('sta-page-content'); ?> style="max-width:900px; margin:0 auto;">
                <header class="sta-page-header">
                    <span class="sta-badge"><?php echo esc_html(sta_t('Engasjement', 'Engagement', 'Engasjement')); ?></span>
                    <h1 class="sta-hero-title" style="margin-top:0.5rem; font-size:2.75rem;">
                        <?php echo esc_html(sta_t('Engasjer deg i STA', 'Get Involved in STA', 'Engasjer deg i STA')); ?>
                    </h1>
                    <p style="color:var(--sta-teal-100); font-size:1.15rem; margin-top:0.75rem;">
                        <?php echo esc_html(sta_t('Gjør en forskjell for over 14 000 studenter ved Universitetet i Agder.', 'Make a real difference for over 14,000 students at the University of Agder.', 'Gjer ein skilnad for over 14 000 studentar ved Universitetet i Agder.')); ?>
                    </p>
                </header>

                <div class="sta-entry-content" style="font-size:1.15rem; line-height:1.8; color:var(--sta-teal-50); margin-top:2rem;">
                    <p><?php echo esc_html(sta_t('Å bli en aktiv studentrepresentant eller bli med i en forening gir deg uforglemmelige erfaringer, lederegenskaper og sjansen til å forme studenthverdagen.', 'Becoming an active student representative or joining an association gives you unforgettable experiences, leadership skills, and the chance to shape university life.', 'Å bli ein aktiv studenttillitsvald eller bli med i ei foreining gjev deg uforgløymelege erfaringar, leiarerfaring og sjansen til å forme studentkvardagen.')); ?></p>
                    
                    <div style="margin:2.5rem 0; display:flex; flex-wrap:wrap; gap:1rem;">
                        <a href="<?php echo esc_url(sta_get_localized_url('/ledige-verv')); ?>" class="sta-btn sta-btn-orange" style="padding:0.75rem 1.5rem; font-weight:700;">
                            <?php echo esc_html(sta_t('Se ledige verv &rarr;', 'View vacant positions &rarr;', 'Sjå ledige verv &rarr;')); ?>
                        </a>
                        <a href="<?php echo esc_url(sta_get_localized_url('/studentparlamentet')); ?>" class="sta-btn sta-btn-ghost" style="padding:0.75rem 1.5rem; font-weight:700;">
                            <?php echo esc_html(sta_t('Om Studentparlamentet &rarr;', 'About the Student Parliament &rarr;', 'Om Studentparlamentet &rarr;')); ?>
                        </a>
                    </div>
                </div>
            </article>

        <?php elseif ($post_slug === 'for-foreninger'): ?>
            <article id="page-<?php the_ID(); ?>" <?php post_class('sta-page-content'); ?> style="max-width:900px; margin:0 auto;">
                <header class="sta-page-header">
                    <span class="sta-badge"><?php echo esc_html(sta_t('Foreninger', 'Associations', 'Foreiningar')); ?></span>
                    <h1 class="sta-hero-title" style="margin-top:0.5rem; font-size:2.75rem;">
                        <?php echo esc_html(sta_t('For studentforeninger', 'For Student Associations', 'For studentforeiningar')); ?>
                    </h1>
                    <p style="color:var(--sta-teal-100); font-size:1.15rem; margin-top:0.75rem;">
                        <?php echo esc_html(sta_t('Ressurser, retningslinjer, støtteordninger og lokaleleie for studentforeninger.', 'Resources, guidelines, funding applications and venue booking for student organisations.', 'Ressursar, retningslinjer, støtteordningar og lokalebooking for studentorganisasjonar.')); ?>
                    </p>
                </header>

                <div class="sta-entry-content" style="font-size:1.15rem; line-height:1.8; color:var(--sta-teal-50); margin-top:2rem;">
                    <p><?php echo esc_html(sta_t('Driver du en linjeforening eller studentaktivitet? STA tilbyr veiledning, lokaler på Alibiet og Bluebox, og administrativ støtte.', 'Are you running an academic association or student activity? STA provides support, premises booking at Alibiet and Bluebox, and administrative assistance.', 'Driv du ei linjeforeining eller studentaktivitet? STA tilbyr rettleiing, booking av lokale på Alibiet og Bluebox, og administrativ støtte.')); ?></p>
                    
                    <div style="margin:2.5rem 0; display:flex; flex-wrap:wrap; gap:1rem;">
                        <a href="<?php echo esc_url(sta_get_localized_url('/lokaler')); ?>" class="sta-btn sta-btn-orange" style="padding:0.75rem 1.5rem; font-weight:700;">
                            <?php echo esc_html(sta_t('Book våre lokaler &rarr;', 'Book our premises &rarr;', 'Book våre lokale &rarr;')); ?>
                        </a>
                        <a href="<?php echo esc_url(sta_get_localized_url('/dokumenter')); ?>" class="sta-btn sta-btn-ghost" style="padding:0.75rem 1.5rem; font-weight:700;">
                            <?php echo esc_html(sta_t('Retningslinjer & dokumenter &rarr;', 'Guidelines & Documents &rarr;', 'Retningslinjer og dokument &rarr;')); ?>
                        </a>
                    </div>
                </div>
            </article>

        <?php else: ?>
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
        <?php endif; ?>
    <?php endwhile; ?>
</div>

<?php
get_footer();
