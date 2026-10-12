<?php
/**
 * Template Name: Ledige verv (Vacant Positions)
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
$is_en = sta_is_en();

// Load vacancies data
$vacancies = array();
$json_file = sta_get_data_file('vacancies.json');
if ($json_file && file_exists($json_file)) {
    $vacancies = json_decode(file_get_contents($json_file), true) ?: array();
}
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <header class="sta-page-header">
        <span class="sta-badge"><?php echo esc_html(sta_t('Bli engasjert', 'Get Involved')); ?></span>
        <h1 class="sta-hero-title">
            <?php echo esc_html(sta_t('Ledige verv 2026/2027', 'Vacant positions 2026/2027')); ?>
        </h1>
        <p class="sta-hero-subtitle" style="max-width:800px; margin-left:auto; margin-right:auto;">
            <?php echo esc_html(sta_t('Er du interessert i å gjøre en endring på Universitetet i Agder? Da kan du sjekke om det fortsatt er ledige verv å søke på!', 'Are you interested in making a difference at the University of Agder? Check out our vacant positions and apply today!')); ?>
        </p>
    </header>

    <!-- Top CTA Box -->
    <div style="max-width:44rem; margin:0 auto 3.5rem auto; padding:2rem; border-radius:1rem; background:rgba(14,76,71,0.6); border:1px solid rgba(239,115,48,0.4); text-align:center;">
        <p style="font-size:0.95rem; color:var(--sta-teal-100); margin-bottom:1.25rem;">
            <?php echo esc_html(sta_t('Ønsker du å stille til et av vervene nedenfor? Send en e-post med en kort introduksjon om deg selv til:', 'Would you like to run for one of the positions below? Send an email with a brief introduction of yourself to:')); ?>
        </p>
        <a href="mailto:sta@uia.no?subject=<?php echo rawurlencode(sta_t('Søknad på ledig verv', 'Application for vacant position')); ?>" 
           class="sta-btn sta-btn-orange" 
           style="display:inline-flex; align-items:center; gap:0.5rem; font-weight:700;">
            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            <span><?php echo esc_html(sta_t('Send søknad til sta@uia.no', 'Send application to sta@uia.no')); ?></span>
        </a>
    </div>

    <!-- Vacancies Grouped by Category -->
    <div style="max-width:56rem; margin:0 auto; display:flex; flex-direction:column; gap:3rem;">
        <?php foreach ($vacancies as $group): 
            $cat_title = $is_en ? ($group['categoryEn'] ?? $group['category']) : $group['category'];
            $items = $group['items'] ?? array();
        ?>
            <div>
                <h2 style="font-size:1.5rem; font-weight:700; color:#fff; margin-bottom:1.25rem; padding-bottom:0.6rem; border-bottom:1px solid rgba(158,207,201,0.25);">
                    <?php echo esc_html($cat_title); ?>
                </h2>

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.25rem;">
                    <?php foreach ($items as $item): 
                        $item_title = $is_en ? ($item['titleEn'] ?? $item['title']) : $item['title'];
                        $item_pos = $is_en ? ($item['positionsEn'] ?? $item['positions']) : $item['positions'];
                        $item_org = $is_en ? ($item['organizationEn'] ?? $item['organization']) : $item['organization'];
                        $item_elig = $is_en ? ($item['eligibilityEn'] ?? $item['eligibility'] ?? '') : ($item['eligibility'] ?? '');
                        $info_url = $item['infoUrl'] ?? '';
                        $info_label = $is_en ? ($item['infoUrlLabelEn'] ?? $item['infoUrlLabel'] ?? 'Offisiell informasjon') : ($item['infoUrlLabel'] ?? 'Offisiell informasjon');
                        $is_external = str_starts_with($info_url, 'http');
                    ?>
                        <div class="sta-card" style="padding:1.35rem; display:flex; flex-direction:column; justify-content:space-between;">
                            <div>
                                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:0.75rem; margin-bottom:0.5rem;">
                                    <h3 style="font-size:1rem; font-weight:700; color:#fff; line-height:1.35; margin:0;">
                                        <?php echo esc_html($item_title); ?>
                                    </h3>
                                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="var(--sta-orange)" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:0.15rem;" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                </div>

                                <p style="font-size:0.8rem; color:var(--sta-orange); font-weight:600; margin-bottom:0.5rem;">
                                    <?php echo esc_html($item_pos); ?>
                                </p>

                                <div style="display:flex; align-items:center; gap:0.4rem; font-size:0.75rem; color:var(--sta-teal-200); margin-bottom:0.75rem;">
                                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.8;" aria-hidden="true"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect><line x1="9" y1="22" x2="9" y2="22.01"></line><line x1="15" y1="22" x2="15" y2="22.01"></line><line x1="9" y1="6" x2="9" y2="6.01"></line><line x1="15" y1="6" x2="15" y2="6.01"></line><line x1="9" y1="10" x2="9" y2="10.01"></line><line x1="15" y1="10" x2="15" y2="10.01"></line><line x1="9" y1="14" x2="9" y2="14.01"></line><line x1="15" y1="14" x2="15" y2="14.01"></line><line x1="9" y1="18" x2="9" y2="18.01"></line><line x1="15" y1="18" x2="15" y2="18.01"></line></svg>
                                    <span><?php echo esc_html($item_org); ?></span>
                                </div>

                                <?php if (!empty($item_elig)): ?>
                                    <p style="font-size:0.75rem; color:var(--sta-teal-300); font-style:italic; margin-bottom:0.75rem; line-height:1.4;">
                                        <strong><?php echo esc_html(sta_t('Kvalifikasjonskrav:', 'Eligibility:')); ?></strong> <?php echo esc_html($item_elig); ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($info_url)): ?>
                                <div style="padding-top:0.75rem; border-top:1px solid rgba(158,207,201,0.2); margin-top:0.5rem;">
                                    <a href="<?php echo esc_url($info_url); ?>" 
                                       <?php echo $is_external ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
                                       style="display:inline-flex; align-items:center; gap:0.4rem; font-size:0.8rem; font-weight:600; color:var(--sta-orange); text-decoration:none;">
                                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                        <span><?php echo esc_html($info_label); ?></span>
                                        <?php if ($is_external): ?>
                                            <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.8;" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                        <?php endif; ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Bottom CTA Button -->
    <div style="margin-top:4rem; text-align:center;">
        <a href="<?php echo esc_url(sta_get_localized_url('/rad-styrer-og-utvalg')); ?>" class="sta-btn sta-btn-outline">
            <?php echo esc_html(sta_t('Les mer om hva de ulike vervene innebærer &rarr;', 'Read more about what the various positions entail &rarr;')); ?>
        </a>
    </div>
</div>

<?php
get_footer();
