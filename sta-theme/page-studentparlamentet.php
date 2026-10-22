<?php
/**
 * Template Name: Studentparlamentet (The Student Parliament)
 * Faithful reproduction of src/app/studentparlamentet/page.tsx
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
$is_en = sta_is_en();
$is_nn = sta_is_nn();

// Fallback faculties data
$faculties = array();
$json_file = sta_get_data_file('parliament_faculties.json');
if ($json_file && file_exists($json_file)) {
    $faculties = json_decode(file_get_contents($json_file), true) ?: array();
}
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <!-- Section Heading -->
    <header class="sta-page-header">
        <span class="sta-badge"><?php echo esc_html(sta_t('Studentdemokrati', 'Student Democracy', 'Studentdemokrati')); ?></span>
        <h1 class="sta-hero-title">
            <?php echo esc_html(sta_t('Studentparlamentet 2026/2027', 'The Student Parliament 2026/2027', 'Studentparlamentet 2026/2027')); ?>
        </h1>
        <p class="sta-hero-subtitle" style="max-width:800px; margin-left:auto; margin-right:auto;">
            <?php echo esc_html(sta_t('UiAs øverste studentorgan og demokratiske forsamling', 'UiA\'s highest student body and democratic assembly', 'Det øvste studentorganet og den demokratiske forsamlinga ved UiA')); ?>
        </p>
    </header>

    <!-- 16/7 Cropped Banner Image -->
    <div style="position:relative; margin-bottom:3rem; border-radius:1rem; overflow:hidden; border:1px solid rgba(158,207,201,0.3); box-shadow:0 20px 25px -5px rgba(0,0,0,0.4); max-width:1280px; margin-left:auto; margin-right:auto;">
        <div style="position:relative; aspect-ratio:16/7; width:100%; background:var(--sta-teal-900);">
            <img src="https://stastudent.no/wp-content/uploads/2025/09/IMG_4688-–-Kopi-CROPPED-1536x581.jpg" 
                 alt="<?php echo esc_attr(sta_t('Studentparlamentet samlet i møtesalen', 'The Student Parliament gathered in session', 'Studentparlamentet samla i møtesalen')); ?>" 
                 style="width:100%; height:100%; object-fit:cover;"
                 loading="eager">
        </div>
    </div>

    <!-- Introduction Text -->
    <div style="max-width:48rem; margin:0 auto 3.5rem auto; font-size:1rem; color:var(--sta-teal-100); line-height:1.75; display:flex; flex-direction:column; gap:1.25rem;">
        <p style="margin:0;">
            <?php echo esc_html(sta_t(
                'Studentene har en viktig stemme i demokratiet på UiA. Derfor er Studentparlamentet øverste beslutningsorgan i STA. Studentparlamentet består av 25 representanter fra alle fakultetene og Avdeling for lærerutdanning. Det er disse representantene som skal ta politiske avgjørelser på vegne av UiAs studentmasse.',
                'Students have an important voice in the democracy at UiA. Therefore, the Student Parliament is the highest decision-making body in STA. The Student Parliament consists of 25 representatives from all the faculties and the Department of Teacher Education. It is these representatives who will make political decisions on behalf of UiA\'s student body.',
                'Studentane har ei viktig stemme i demokratiet ved UiA. Difor er Studentparlamentet det øvste vedtaksorganet i STA. Studentparlamentet er sett saman av 25 representantar frå alle fakulteta og Avdeling for lærarutdanning. Det er desse representantane som skal ta politiske avgjerder på vegner av studentmassen ved UiA.'
            )); ?>
        </p>
        <p style="margin:0;">
            <?php echo esc_html(sta_t(
                'Studentparlamentet har møter én gang i måneden hvor parlamentsmedlemmene skal diskutere og vedta saker som STA-styret legger frem. Møtene blir holdt annenhver gang i Kristiansand, og annenhver gang i Grimstad.',
                'The student parliament meets once a month, where the members of the parliament discuss and adopt matters presented by the STA-board. The meetings are held every second time in Kristiansand, and every second time in Grimstad.',
                'Studentparlamentet har møte éin gong i månaden der parlamentsmedlemmene skal diskutere og vedta saker som STA-styret legg fram. Møta blir haldne annakvar gong i Kristiansand og annakvar gong i Grimstad.'
            )); ?>
        </p>
    </div>

    <!-- Quick Action / Meeting Document Links (2 Cards) -->
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.5rem; max-width:44rem; margin:0 auto 4rem auto;">
        <a href="<?php echo esc_url(sta_get_localized_url('/sakspapirer')); ?>" 
           class="sta-parliament-quick-link">
            <div style="width:3rem; height:3rem; border-radius:0.75rem; background:rgba(239,115,48,0.2); color:var(--sta-orange); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <div>
                <h3 style="font-size:1rem; font-weight:700; color:#fff; margin:0;">
                    <?php echo esc_html(sta_t('Sakspapirer til parlamentsmøter', 'Agenda and meeting papers', 'Sakspapir til parlamentsmøte')); ?>
                </h3>
                <p style="font-size:0.75rem; color:var(--sta-teal-200); margin:0.25rem 0 0 0;">
                    <?php echo esc_html(sta_t('Se innkallinger og saksliste', 'View notices and agenda', 'Sjå innkallingar og saksliste')); ?>
                </p>
            </div>
        </a>

        <a href="<?php echo esc_url(sta_get_localized_url('/referat-fra-moter-i-studentparlamentet')); ?>" 
           class="sta-parliament-quick-link">
            <div style="width:3rem; height:3rem; border-radius:0.75rem; background:rgba(239,115,48,0.2); color:var(--sta-orange); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <div>
                <h3 style="font-size:1rem; font-weight:700; color:#fff; margin:0;">
                    <?php echo esc_html(sta_t('Protokoll fra parlamentsmøter', 'Minutes and protocols', 'Protokoll frå parlamentsmøte')); ?>
                </h3>
                <p style="font-size:0.75rem; color:var(--sta-teal-200); margin:0.25rem 0 0 0;">
                    <?php echo esc_html(sta_t('Vedtatte resolusjoner og referater', 'Adopted resolutions and minutes', 'Vedtekne resolusjonar og referat')); ?>
                </p>
            </div>
        </a>
    </div>

    <!-- Responsible persons -->
    <div style="margin-bottom:4.5rem;">
        <h2 style="font-size:1.75rem; font-weight:700; color:#fff; text-align:center; margin-bottom:2rem;">
            <?php echo esc_html(sta_t('Ansvarlige for Studentparlamentet', 'Leadership of the Student Parliament', 'Ansvarlege for Studentparlamentet')); ?>
        </h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1.5rem; max-width:44rem; margin:0 auto;">
            <div class="sta-card" style="padding:1.75rem; text-align:center; display:flex; flex-direction:column; align-items:center;">
                <div style="position:relative; width:6rem; height:6rem; margin:0 auto 1rem auto; border-radius:50%; overflow:hidden; border:2px solid var(--sta-orange); box-shadow:0 4px 14px rgba(0,0,0,0.3);">
                    <img src="https://stastudent.no/wp-content/uploads/2026/08/a0da7b30-a50f-4dd3-9b89-6ec47da958aa.jpg" 
                         alt="Mia Sandøy" 
                         style="width:100%; height:100%; object-fit:cover;"
                         loading="lazy">
                </div>
                <h3 style="font-size:1.15rem; font-weight:700; color:#fff; margin:0;">Mia Sandøy</h3>
                <p style="font-size:0.75rem; font-weight:600; color:var(--sta-orange); margin:0.25rem 0 0.75rem 0;">
                    <?php echo esc_html(sta_t('Førstekonsulent i STA', 'First Consultant at STA', 'Førstekonsulent i STA')); ?>
                </p>
                <a href="mailto:sta@uia.no" style="display:inline-flex; align-items:center; gap:0.35rem; font-size:0.75rem; color:var(--sta-teal-100); text-decoration:none;">
                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <span>sta@uia.no</span>
                </a>
            </div>

            <div class="sta-card" style="padding:1.75rem; text-align:center; display:flex; flex-direction:column; align-items:center;">
                <div style="position:relative; width:6rem; height:6rem; margin:0 auto 1rem auto; border-radius:50%; overflow:hidden; border:2px solid var(--sta-orange); box-shadow:0 4px 14px rgba(0,0,0,0.3);">
                    <img src="https://stastudent.no/wp-content/uploads/2026/08/Karen-Smil-1024x683.jpg" 
                         alt="Karen Elise Stenersen" 
                         style="width:100%; height:100%; object-fit:cover;"
                         loading="lazy">
                </div>
                <h3 style="font-size:1.15rem; font-weight:700; color:#fff; margin:0;">Karen Elise Stenersen</h3>
                <p style="font-size:0.75rem; font-weight:600; color:var(--sta-orange); margin:0.25rem 0 0.75rem 0;">
                    <?php echo esc_html(sta_t('Studentleder ved UiA', 'Student President at UiA', 'Studentleiar ved UiA')); ?>
                </p>
                <a href="mailto:staleder@uia.no" style="display:inline-flex; align-items:center; gap:0.35rem; font-size:0.75rem; color:var(--sta-teal-100); text-decoration:none;">
                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <span>staleder@uia.no</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Representatives by Faculty (2 Column Grid) -->
    <div style="margin-bottom:4rem;">
        <div style="text-align:center; max-width:36rem; margin:0 auto 2.5rem auto;">
            <h2 style="font-size:1.75rem; font-weight:700; color:#fff; margin-bottom:0.5rem;">
                <?php echo esc_html(sta_t('Representanter etter fakultet', 'Representatives by Faculty', 'Representantar etter fakultet')); ?>
            </h2>
            <p style="font-size:0.875rem; color:var(--sta-teal-200); margin:0;">
                <?php echo esc_html(sta_t('Totalt 25 faste folkevalgte representanter og deres personlige varamedlemmer.', 'A total of 25 regular elected representatives and their personal substitutes.', 'Totalt 25 faste folkevalde representantar og dei personlege varamedlemmene deira.')); ?>
            </p>
        </div>

        <div class="sta-parliament-faculties-grid">
            <?php foreach ($faculties as $fac): 
                $fac_title = $is_en ? ($fac['nameEn'] ?? $fac['name']) : $fac['name'];
            ?>
                <div class="sta-card sta-parliament-faculty-card" style="padding:1.75rem; display:flex; flex-direction:column; justify-content:space-between;">
                    <div>
                        <!-- Faculty Header with Users icon -->
                        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid rgba(158,207,201,0.25);">
                            <svg viewBox="0 0 24 24" width="22" height="22" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <h3 style="font-size:1.2rem; font-weight:700; color:#fff; margin:0;">
                                <?php echo esc_html($fac_title); ?>
                            </h3>
                        </div>

                        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1.25rem;">
                            <!-- Regulars -->
                            <div>
                                <h4 style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:var(--sta-orange); margin:0 0 0.6rem 0;">
                                    <?php printf(esc_html(sta_t('Faste representanter (%d)', 'Regular members (%d)', 'Faste representantar (%d)')), count($fac['representatives'])); ?>
                                </h4>
                                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:0.45rem;">
                                    <?php foreach ($fac['representatives'] as $rep): ?>
                                        <li style="display:flex; align-items:center; gap:0.5rem; font-size:0.85rem; color:var(--sta-teal-100);">
                                            <span style="width:0.4rem; height:0.4rem; border-radius:50%; background:var(--sta-orange); flex-shrink:0;"></span>
                                            <span><?php echo esc_html($rep['name']); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <!-- Substitutes -->
                            <div>
                                <h4 style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:var(--sta-teal-300); margin:0 0 0.6rem 0;">
                                    <?php printf(esc_html(sta_t('Vararepresentanter (%d)', 'Substitutes (%d)', 'Vararepresentantar (%d)')), count($fac['substitutes'])); ?>
                                </h4>
                                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:0.45rem;">
                                    <?php foreach ($fac['substitutes'] as $idx => $sub): 
                                        $is_vacant = !empty($sub['isVacant']);
                                    ?>
                                        <li style="display:flex; align-items:center; gap:0.5rem; font-size:0.85rem; color:var(--sta-teal-100);">
                                            <span style="width:0.4rem; height:0.4rem; border-radius:50%; background:var(--sta-teal-400); flex-shrink:0;"></span>
                                            <?php if ($is_vacant): ?>
                                                <span style="color:var(--sta-orange); font-style:italic;">
                                                    <?php echo esc_html(sta_t('Ledig verv – kontakt sta@uia.no', 'Vacant – contact sta@uia.no', 'Ledig verv – kontakt sta@uia.no')); ?>
                                                </span>
                                            <?php else: ?>
                                                <span><?php echo ($idx + 1) . '. ' . esc_html(sta_t('vara:', 'substitute:', 'vara:')) . ' ' . esc_html($sub['name']); ?></span>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bottom CTA Button -->
    <div style="margin-top:4rem; text-align:center;">
        <a href="<?php echo esc_url(sta_get_localized_url('/ledige-verv')); ?>" class="sta-btn sta-btn-orange" style="font-size:1.05rem; padding:0.9rem 2rem; font-weight:700;">
            <?php echo esc_html(sta_t('Still til ledig verv i parlamentet &rarr;', 'Run for a vacant seat in parliament &rarr;', 'Still til ledig verv i parlamentet &rarr;')); ?>
        </a>
    </div>
</div>

<?php
get_footer();
