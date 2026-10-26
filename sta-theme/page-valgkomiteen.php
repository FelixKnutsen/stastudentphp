<?php
/**
 * Template Name: Valgkomitéen (The Election Committee)
 * Faithful reproduction of https://stastudent.no/valgkomiteen/
 */

if (!defined('ABSPATH')) {
    exit;
}

if (class_exists('\Elementor\Plugin') && (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() || \Elementor\Plugin::$instance->db->is_built_with_elementor(get_the_ID()))) {
    include STA_THEME_PATH . '/page.php';
    return;
}

get_header();
$is_en = sta_is_en();
$is_nn = sta_is_nn();

$leader = array(
    'name'  => 'William T. Svendsen',
    'role'  => sta_t('Leder av Valgkomitéen 2025/2026', 'Chair of the Election Committee 2025/2026', 'Leiar av Valkomitéen 2025/2026'),
    'image' => 'https://stastudent.no/wp-content/uploads/2023/06/WTS-6-1024x683.jpg',
    'email' => 'stavalg@uia.no',
);

$members = array(
    array(
        'name' => 'Lisa K. Fredsvik',
        'role' => sta_t('Medlem av Valgkomitéen', 'Committee Member', 'Medlem av Valkomitéen'),
    ),
    array(
        'name' => 'Rania Farah',
        'role' => sta_t('Medlem av Valgkomitéen', 'Committee Member', 'Medlem av Valkomitéen'),
    ),
    array(
        'name' => 'Andrea M. Åmodt',
        'role' => sta_t('Medlem av Valgkomitéen', 'Committee Member', 'Medlem av Valkomitéen'),
    ),
);
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <!-- Breadcrumb / Back Link -->
    <div style="margin-bottom:1.5rem;">
        <a href="<?php echo esc_url(sta_get_localized_url('/rad-styrer-og-utvalg')); ?>" style="display:inline-flex; align-items:center; gap:0.5rem; color:var(--sta-teal-200); font-size:0.9rem; font-weight:600; text-decoration:none;">
            <span aria-hidden="true">&larr;</span>
            <span><?php echo esc_html(sta_t('Tilbake til Råd, Styrer og Utvalg', 'Back to Boards, Councils & Committees', 'Tilbake til Råd, Styre og Utval')); ?></span>
        </a>
    </div>

    <!-- Header Section -->
    <header class="sta-page-header">
        <span class="sta-badge"><?php echo esc_html(sta_t('Studentdemokrati', 'Student Democracy', 'Studentdemokrati')); ?></span>
        <h1 class="sta-hero-title">
            <?php echo esc_html(sta_t('Valgkomitéen 2025/2026', 'The Election Committee 2025/2026', 'Valkomitéen 2025/2026')); ?>
        </h1>
        <p class="sta-hero-subtitle" style="max-width:800px; margin-left:auto; margin-right:auto;">
            <?php echo esc_html(sta_t(
                'Valgkomitéen har ansvaret for rekruttering, intervjuer og innstilling av kandidater til alle verv som velges av Studentparlamentet ved UiA.',
                'The Election Committee is responsible for recruitment, interviewing, and recommending candidates for all positions elected by the Student Parliament at UiA.',
                'Valkomitéen har ansvaret for rekruttering, intervju og innstilling av kandidatar til alle verv som blir valde av Studentparlamentet ved UiA.'
            )); ?>
        </p>
    </header>

    <!-- Committee Leader Feature Card -->
    <div style="max-width:48rem; margin:0 auto 3rem auto;">
        <div class="sta-card" style="padding:2rem; display:flex; flex-direction:column; md:flex-row; gap:2rem; align-items:center;">
            <div style="position:relative; width:12rem; height:12rem; border-radius:50%; overflow:hidden; border:3px solid var(--sta-orange); box-shadow:0 12px 28px rgba(0,0,0,0.35); flex-shrink:0;">
                <img src="<?php echo esc_url($leader['image']); ?>" 
                     alt="<?php echo esc_attr($leader['name']); ?>" 
                     style="width:100%; height:100%; object-fit:cover;"
                     loading="eager">
            </div>
            <div style="text-align:center; flex:1;">
                <span class="sta-badge" style="background:var(--sta-orange-bg); color:#fff; border:none; font-size:0.75rem; margin-bottom:0.5rem;">
                    <?php echo esc_html(sta_t('Leder', 'Chair', 'Leiar')); ?>
                </span>
                <h2 style="font-size:1.75rem; font-weight:800; color:#fff; margin:0 0 0.35rem 0;">
                    <?php echo esc_html($leader['name']); ?>
                </h2>
                <p style="font-size:0.95rem; font-weight:600; color:var(--sta-teal-100); margin:0 0 1rem 0;">
                    <?php echo esc_html($leader['role']); ?>
                </p>
                <a href="mailto:<?php echo esc_attr($leader['email']); ?>" style="display:inline-flex; align-items:center; gap:0.4rem; color:var(--sta-orange-text); font-weight:700; font-size:0.95rem; text-decoration:none;">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <span><?php echo esc_html($leader['email']); ?></span>
                </a>
            </div>
        </div>
    </div>

    <!-- Committee Members Grid -->
    <div style="margin-bottom:4rem;">
        <h2 style="font-size:1.5rem; font-weight:700; color:#fff; text-align:center; margin-bottom:1.5rem;">
            <?php echo esc_html(sta_t('Medlemmer av Valgkomitéen', 'Committee Members', 'Medlemmer av Valkomitéen')); ?>
        </h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:1.5rem; max-width:54rem; margin:0 auto;">
            <?php foreach ($members as $mem): ?>
                <div class="sta-card" style="padding:1.5rem; text-align:center; display:flex; flex-direction:column; align-items:center;">
                    <div style="width:3.5rem; height:3.5rem; border-radius:50%; background:rgba(239,115,48,0.18); color:var(--sta-orange-text); display:flex; align-items:center; justify-content:center; margin-bottom:1rem; flex-shrink:0;">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <h3 style="font-size:1.15rem; font-weight:700; color:#fff; margin:0 0 0.35rem 0;">
                        <?php echo esc_html($mem['name']); ?>
                    </h3>
                    <p style="font-size:0.85rem; color:var(--sta-teal-200); margin:0;">
                        <?php echo esc_html($mem['role']); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- About the Committee Work / Responsibilities -->
    <div style="max-width:52rem; margin:0 auto 4rem auto;">
        <div class="sta-card" style="padding:2rem 2.5rem; display:flex; flex-direction:column; gap:1.25rem;">
            <h2 style="font-size:1.4rem; font-weight:800; color:#fff; margin:0;">
                <?php echo esc_html(sta_t('Hva gjør Valgkomitéen?', 'What does the Election Committee do?', 'Kva gjer Valkomitéen?')); ?>
            </h2>
            <p style="font-size:0.95rem; color:var(--sta-teal-100); line-height:1.75; margin:0;">
                <?php echo esc_html(sta_t(
                    'Valgkomiteens oppgave er å innstille på kandidater til verv som velges av Studentparlamentet. For å kunne gi den beste anbefalingen intervjuer komiteens medlemmer alle kandidater som har stilt til valg innenfor en gitt frist som settes av komiteens leder og STA-styret. Komiteens leder har et ansvar for å koordinere intervjuprosessen, slik at alle kandidater vet hvor og når de skal møte til intervju, samt for at innstillingen er klar til offentliggjøringen til rett tid.',
                    'The Election Committee\'s duty is to interview and nominate candidates for positions elected by the Student Parliament. To provide the best recommendations, the committee interviews all applicants within deadlines set jointly with the STA Board. The chair coordinates the interview process so candidates know where and when to appear, and ensures the nomination report is released on schedule.',
                    'Valkomiteen si oppgåve er å innstille på kandidatar til verv som blir valde av Studentparlamentet. For å kunne gje den beste tilrådinga intervjuar komiteen sine medlemer alle kandidatar som har stilt til val innanfor ein gitt frist sett av leiaren i komiteen og STA-styret. Komiteen sin leiar har ansvar for å koordinere intervjuprosessen og syte for at innstillinga er klar til rett tid.'
                )); ?>
            </p>
            <p style="font-size:0.95rem; color:var(--sta-teal-100); line-height:1.75; margin:0;">
                <?php echo esc_html(sta_t(
                    'Å være medlem av Valgkomiteen er et spennende arbeid. Man får erfaring med rekruttering av flinke folk, og med å intervjue dem. Å bli trygg på rollen som intervjuer er en nyttig erfaring som gjør at man forstår en rekrutteringsprosess bedre når man selv søker stillinger i arbeidslivet. Leder og 3 medlemmer velges i mai, med funksjonstid fra 01.07 til 30.06.',
                    'Serving on the Election Committee is engaging work. You gain direct experience recruiting talent and conducting professional interviews. Becoming confident in an interviewing role gives invaluable insight when applying for career jobs later. The chair and 3 members are elected each May for a term from 01.07 to 30.06.',
                    'Å vere medlem av Valkomiteen gjev verdifull leiar- og rekrutteringserfaring. Leiar og 3 medlemer blir valde i mai, med funksjonstid frå 01.07 til 30.06.'
                )); ?>
            </p>
            <div style="padding-top:1rem; border-top:1px solid rgba(158,207,201,0.2); display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:1rem;">
                <span style="font-size:0.85rem; color:var(--sta-teal-200);">
                    <?php echo esc_html(sta_t('Har du spørsmål om valgprosessen?', 'Questions about the election process?', 'Har du spørsmål om valprosessen?')); ?>
                </span>
                <a href="mailto:stavalg@uia.no" style="display:inline-flex; align-items:center; gap:0.4rem; color:var(--sta-orange-text); font-weight:700; font-size:0.9rem; text-decoration:none;">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <span>stavalg@uia.no</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom CTA Button -->
    <div style="text-align:center;">
        <a href="<?php echo esc_url(sta_get_localized_url('/ledige-verv')); ?>" class="sta-btn sta-btn-orange" style="font-size:1.05rem; padding:0.9rem 2rem; font-weight:700;">
            <?php echo esc_html(sta_t('Se ledige verv og still til valg &rarr;', 'View vacant positions and apply &rarr;', 'Sjå ledige verv og still til val &rarr;')); ?>
        </a>
    </div>
</div>

<?php
get_footer();
