<?php
/**
 * Template Name: Råd, Styrer og Utvalg (RSU)
 * Faithful reproduction of src/app/rad-styrer-og-utvalg/page.tsx
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

// 6 gode grunner
$six_reasons = array(
    array(
        'title'    => '1. Representere studentene',
        'title_en' => '1. Representing students',
        'title_nn' => '1. Representere studentane',
        'desc'     => 'Være med på å forbedre UiA for studentene, og gjøre studiehverdagen bedre for 14 000 medstudenter.',
        'desc_en'  => 'Help improve UiA for all students and enhance everyday university life for 14,000 fellow students.',
        'desc_nn'  => 'Vere med på å forbetre UiA for studentane, og gjere studiekvardagen betre for 14 000 medstudentar.',
        'icon'     => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>'
    ),
    array(
        'title'    => '2. Nettverksbygging',
        'title_en' => '2. Networking',
        'title_nn' => '2. Nettverksbygging',
        'desc'     => 'Få et bredt og tverrfaglig nettverk på tvers av studier, campus og med universitetsledelsen.',
        'desc_en'  => 'Build an extensive interdisciplinary network across academic fields, campuses, and university leadership.',
        'desc_nn'  => 'Få eit breitt og tverrfagleg nettverk på tvers av studium, campus og med universitetsleiinga.',
        'icon'     => '<rect x="16" y="16" width="6" height="6" rx="1"></rect><rect x="2" y="16" width="6" height="6" rx="1"></rect><rect x="9" y="2" width="6" height="6" rx="1"></rect><path d="M5 16v-3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3"></path><path d="M12 12V8"></path>'
    ),
    array(
        'title'    => '3. Arbeidslivsrelevant',
        'title_en' => '3. Career-relevant',
        'title_nn' => '3. Arbeidslivsrelevant',
        'desc'     => 'Utfordre deg selv, få verdifull leder- og samfunnserfaring og skaff deg en solid attest til CV-en.',
        'desc_en'  => 'Challenge yourself, gain valuable governance experience, and build an exceptional reference for your CV.',
        'desc_nn'  => 'Utfordre deg sjølv, få verdifull leiar- og samfunnserfaring og skaff deg ein solid attest til CV-en.',
        'icon'     => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>'
    ),
    array(
        'title'    => '4. Organisasjonserfaring',
        'title_en' => '4. Organizational experience',
        'title_nn' => '4. Organisasjonserfaring',
        'desc'     => 'Få dypere innsikt i hvordan store kunnskapsorganisasjoner, styrer og demokratiske prosesser fungerer i praksis.',
        'desc_en'  => 'Gain direct insight into how large academic institutions, boards, and democratic processes operate in practice.',
        'desc_nn'  => 'Få djupare innsikt i korleis store kunnskabsorganisasjonar, styre og demokratiske prosessar fungerer i praksis.',
        'icon'     => '<path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path><path d="M10 6h4"></path><path d="M10 10h4"></path><path d="M10 14h4"></path><path d="M10 18h4"></path>'
    ),
    array(
        'title'    => '5. God diskusjonsevne',
        'title_en' => '5. Debate & negotiation skills',
        'title_nn' => '5. God diskusjonsevne',
        'desc'     => 'Lære å se saker fra flere sider, argumentere saklig, forhandle og bygge gode kompromisser.',
        'desc_en'  => 'Learn to analyze issues from multiple angles, argue constructively, negotiate, and forge lasting compromises.',
        'desc_nn'  => 'Lære å sjå saker frå fleire sider, argumentere sakleg, forhandle og byggje gode kompromiss.',
        'icon'     => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>'
    ),
    array(
        'title'    => '6. Å bygge seg selv',
        'title_en' => '6. Personal growth',
        'title_nn' => '6. Å byggje seg sjølv',
        'desc'     => 'Det viktigste punktet av alle: Du vokser som menneske. Du blir litt glupere, mer reflektert og modnere.',
        'desc_en'  => 'The most important reason of all: Personal development. You grow as a person, becoming more reflective and confident.',
        'desc_nn'  => 'Det viktigaste punktet av alle: Du veks som menneske. Du blir litt glupare, meir reflektert og modnare.',
        'icon'     => '<path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"></path>'
    ),
);

// Load committee categories
$categories = array();
$committees_file = sta_get_data_file('committees.json');
if ($committees_file && file_exists($committees_file)) {
    $categories = json_decode(file_get_contents($committees_file), true) ?: array();
}
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <!-- Centered Header Section -->
    <header class="sta-page-header">
        <span class="sta-badge"><?php echo esc_html(sta_t('Studentdemokrati', 'Student Democracy', 'Studentdemokrati')); ?></span>
        <h1 class="sta-hero-title">
            <?php echo esc_html(sta_t('Råd, Styrer og Utvalg (RSU)', 'Boards, Councils & Committees', 'Råd, styre og utval (RSU)')); ?>
        </h1>
        <p class="sta-hero-subtitle" style="max-width:800px; margin-left:auto; margin-right:auto;">
            <?php echo esc_html(sta_t('Hos STA kan alle UiA-studenter stille til valg! Bli med på å forme studentenes hverdag.', 'At STA, all UiA students can run for elected office! Join in shaping everyday student life.', 'Hos STA kan alle UiA-studentar stille til val! Bli med på å forme kvardagen til studentane.')); ?>
        </p>
    </header>

    <!-- Intro Card with Action Button -->
    <div style="max-width:768px; margin:0 auto 4rem; text-align:center;">
        <p style="font-size:1rem; color:var(--sta-teal-100); line-height:1.7; margin-bottom:1.5rem;">
            <?php echo esc_html(sta_t('Valg til råd, styrer og utvalg (RSU) skjer på en valgforsamling, hvor Studentparlamentet stemmer over hvem som får plassene. Valgene lyses ut på stastudent.no og deles i våre kanaler på våren – så følg med! Ved spørsmål, ta gjerne kontakt på', 'Elections to councils and boards occur at election assemblies where the Student Parliament votes. Positions are published on stastudent.no every spring. For questions, contact', 'Val til råd, styre og utval (RSU) skjer på ei valforsamling, der Studentparlamentet røystar over kven som får plassane. Vala blir lyste ut på stastudent.no og delte i kanalane våre om våren – så følg med! Ved spørsmål, ta gjerne kontakt på')); ?> 
            <a href="mailto:sta@uia.no" style="color:var(--sta-orange-text); font-weight:700; text-decoration:underline;">sta@uia.no</a>.
        </p>
        <a href="<?php echo esc_url(sta_get_localized_url('/ledige-verv')); ?>" class="sta-btn sta-btn-orange" style="font-size:1rem; padding:0.85rem 1.75rem;">
            <span><?php echo esc_html(sta_t('Se ledige verv nå', 'View vacant positions now', 'Sjå ledige verv no')); ?></span>
            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="margin-left:0.5rem;" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </a>
    </div>

    <!-- 6 gode grunner -->
    <div style="margin-bottom:4rem;">
        <h2 style="font-size:1.75rem; font-weight:800; color:#fff; text-align:center; margin-bottom:2rem;">
            <?php echo esc_html(sta_t('6 gode grunner til hvorfor du skal ta på deg verv:', '6 great reasons to take on an elected position:', '6 gode grunnar til kvifor du bør ta på deg verv:')); ?>
        </h2>
        <div class="sta-reasons-grid">
            <?php foreach ($six_reasons as $reason): 
                $r_title = $is_en ? $reason['title_en'] : ($is_nn ? ($reason['title_nn'] ?? $reason['title']) : $reason['title']);
                $r_desc  = $is_en ? $reason['desc_en'] : ($is_nn ? ($reason['desc_nn'] ?? $reason['desc']) : $reason['desc']);
            ?>
                <div class="sta-card sta-reason-card">
                    <div style="width:3rem; height:3rem; border-radius:0.75rem; background:rgba(239,115,48,0.2); color:var(--sta-orange-text); display:flex; align-items:center; justify-content:center; margin-bottom:1rem; flex-shrink:0;">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <?php echo $reason['icon']; ?>
                        </svg>
                    </div>
                    <h3 class="sta-reason-title">
                        <?php echo esc_html($r_title); ?>
                    </h3>
                    <p style="font-size:0.875rem; color:var(--sta-teal-100); line-height:1.6; margin:0; width:100%;">
                        <?php echo esc_html($r_desc); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Featured Photo Banner -->
    <div style="max-width:896px; margin:0 auto 4.5rem; border-radius:var(--sta-radius); overflow:hidden; border:1px solid rgba(61,148,139,0.3); box-shadow:0 20px 25px -5px rgba(0,0,0,0.4);">
        <div style="position:relative; aspect-ratio:21/9; width:100%; background:var(--sta-teal-800);">
            <img src="https://stastudent.no/wp-content/uploads/2025/10/IMG_4439-1024x683.jpg" 
                 alt="Studenter i verv" 
                 style="width:100%; height:100%; object-fit:cover;"
                 loading="lazy">
        </div>
    </div>

    <!-- Committee Categories & Details Multi-Column Grid -->
    <div style="display:flex; flex-direction:column; gap:3.5rem;">
        <?php foreach ($categories as $cat): 
            $raw_cat_title = $cat['title'] ?? $cat['name'] ?? '';
            $cat_name = $is_en ? ($cat['titleEn'] ?? $cat['nameEn'] ?? $raw_cat_title) : $raw_cat_title;
            if ($is_nn) {
                $nn_cat_map = array(
                    'STA-Verv'                             => 'STA-verv',
                    'STAs Politiske Utvalg'                => 'STA sine politiske utval',
                    'STAs Organisatoriske Utvalg'          => 'STA sine organisatoriske utval',
                    'Universitetsorganer og utvalg ved UiA'=> 'Universitetsorgan og utval ved UiA',
                    'Universitetsorganer (UiA)'            => 'Universitetsorgan (UiA)',
                );
                if (isset($nn_cat_map[$raw_cat_title])) {
                    $cat_name = $nn_cat_map[$raw_cat_title];
                }
            }
            $cat_desc = $is_en ? ($cat['descEn'] ?? $cat['desc'] ?? '') : ($cat['desc'] ?? '');
            $items = $cat['items'] ?? array();
        ?>
            <section>
                <div style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:0.75rem; margin-bottom:1.5rem; padding-bottom:0.75rem; border-bottom:1px solid rgba(158,207,201,0.25);">
                    <h2 style="font-size:1.6rem; font-weight:800; color:#fff; margin:0;">
                        <?php echo esc_html($cat_name); ?>
                    </h2>
                    <span class="sta-badge" style="margin:0; background:rgba(239,115,48,0.18); border:1px solid rgba(239,115,48,0.4); color:var(--sta-orange-text); font-size:0.75rem;">
                        <?php printf(esc_html(sta_t('%d organer / utvalg', '%d boards / committees', '%d organ / utval')), count($items)); ?>
                    </span>
                </div>
                <?php if (!empty($cat_desc)): ?>
                    <p style="font-size:0.95rem; color:var(--sta-teal-100); line-height:1.6; margin-bottom:1.5rem;">
                        <?php echo esc_html($cat_desc); ?>
                    </p>
                <?php endif; ?>

                <div class="sta-rsu-grid">
                    <?php foreach ($items as $item): 
                        $item_name = $is_en ? ($item['nameEn'] ?? $item['name']) : $item['name'];
                        $item_pos  = $is_en ? ($item['positionsEn'] ?? $item['positions'] ?? '') : ($item['positions'] ?? '');
                        $item_desc = $is_en ? ($item['descEn'] ?? $item['desc'] ?? '') : ($item['desc'] ?? '');
                        $info_url  = $item['infoUrl'] ?? '';
                        if ($info_url === '/valgkomiteen') {
                            $info_url = '/ledige-verv';
                        }
                        $is_ext    = !empty($item['isExternal']);
                        $final_url = '';
                        if (!empty($info_url)) {
                            $final_url = $is_ext ? $info_url : sta_get_localized_url($info_url);
                        }
                        $info_label = $is_en ? ($item['infoUrlLabelEn'] ?? $item['infoUrlLabel'] ?? 'Read more') : ($item['infoUrlLabel'] ?? 'Les mer');
                    ?>
                        <div class="sta-card" style="padding:1.5rem; display:flex; flex-direction:column; justify-content:space-between;">
                            <div>
                                <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.85rem;">
                                    <div style="width:2.25rem; height:2.25rem; border-radius:0.5rem; background:rgba(239,115,48,0.18); color:var(--sta-orange-text); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    </div>
                                    <h3 style="font-size:1.15rem; font-weight:700; color:#fff; margin:0; line-height:1.35; overflow-wrap:break-word; word-break:break-word;">
                                        <?php echo esc_html($item_name); ?>
                                    </h3>
                                </div>
                                <?php if (!empty($item_desc)): ?>
                                    <p style="font-size:0.875rem; color:var(--sta-teal-100); line-height:1.6; margin:0;">
                                        <?php echo esc_html($item_desc); ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($final_url)): ?>
                                <div style="margin-top:1.25rem; padding-top:0.85rem; border-top:1px solid rgba(158,207,201,0.18);">
                                    <a href="<?php echo esc_url($final_url); ?>" <?php echo $is_ext ? 'target="_blank" rel="noopener noreferrer"' : ''; ?> style="display:inline-flex; align-items:center; gap:0.35rem; font-size:0.85rem; font-weight:700; color:var(--sta-orange-text); text-decoration:none;">
                                        <span><?php echo esc_html($info_label); ?></span>
                                        <?php if ($is_ext): ?>
                                            <svg viewBox="0 0 24 24" width="13" height="13" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                        <?php else: ?>
                                            <span aria-hidden="true">&rarr;</span>
                                        <?php endif; ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>

    <!-- Bottom CTA Button -->
    <div style="margin-top:4.5rem; text-align:center;">
        <a href="<?php echo esc_url(sta_get_localized_url('/ledige-verv')); ?>" class="sta-btn sta-btn-orange" style="font-size:1.05rem; padding:0.9rem 2rem; font-weight:700;">
            <?php echo esc_html(sta_t('Se alle ledige verv og søk nå &rarr;', 'View all vacant positions and apply now &rarr;', 'Sjå alle ledige verv og søk no &rarr;')); ?>
        </a>
    </div>
</div>

<?php
get_footer();
