<?php
/**
 * Template Name: For tillitsvalgte (Student Representatives)
 * Faithful reproduction of src/app/for-tillitsvalgte/page.tsx
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
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <header class="sta-page-header">
        <span class="sta-badge"><?php echo esc_html(sta_t('Studentdemokrati', 'Student Democracy', 'Studentdemokrati')); ?></span>
        <h1 class="sta-hero-title" style="font-size:2.75rem; margin-top:0.5rem;">
            <?php echo esc_html(sta_t('Studenttillitsvalgt', 'Student Representative', 'Studenttillitsvald')); ?>
        </h1>
        <p style="color:var(--sta-teal-100); font-size:1.15rem; max-width:800px;">
            <?php echo esc_html(sta_t('Les om det å være studenttillitsvalgt på Universitetet i Agder og hvordan STA bidrar inn i arbeidet med kvalitetssystemet.', 'Read about being a student representative at the University of Agder and how STA contributes to the education quality system.', 'Les om det å vere studenttillitsvald ved Universitetet i Agder og korleis STA bidreg inn i arbeidet med kvalitetssystemet.')); ?>
        </p>
    </header>

    <!-- 3 Top Resource Cards -->
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.5rem; margin-bottom:4rem;">
        <a href="https://stastudent.no/wp-content/uploads/2025/09/TILLITSVALGTHANDBOKEN-STUDENTORGANISASJONEN-I-AGDER.pdf" target="_blank" rel="noopener noreferrer" class="sta-quick-card">
            <div class="sta-quick-icon-box">
                <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
            </div>
            <h3 class="sta-quick-title"><?php echo esc_html(sta_t('Tillitsvalgthåndboken', 'Representative Handbook', 'Tillitsvaldhandsaka')); ?></h3>
            <p class="sta-quick-desc"><?php echo esc_html(sta_t('Last ned håndboken som PDF', 'Download handbook as PDF', 'Last ned handboka som PDF')); ?></p>
        </a>

        <a href="https://stastudent.no/wp-content/uploads/2024/05/Sjekkliste-for-studentevaluering-av-emner.pdf" target="_blank" rel="noopener noreferrer" class="sta-quick-card">
            <div class="sta-quick-icon-box">
                <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <h3 class="sta-quick-title"><?php echo esc_html(sta_t('Mal for emneevaluering', 'Course Evaluation Template', 'Mal for emneevaluering')); ?></h3>
            <p class="sta-quick-desc"><?php echo esc_html(sta_t('Sjekkliste og mal for evalueringer', 'Checklist and evaluation template', 'Sjekkliste og mal for evalueringar')); ?></p>
        </a>

        <a href="https://forms.office.com/Pages/ShareFormPage.aspx?id=HoiChJk2P0uxNc9IALwe-5paDL8Z10RCgktRPOD_6vhUQzFYVlo1UU1JVjQzUDQ3NVZESDdYSjJETy4u&sharetoken=0qiUY8Cn2iKY2FHAUTax" target="_blank" rel="noopener noreferrer" class="sta-quick-card">
            <div class="sta-quick-icon-box">
                <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><path d="M8 13h8M8 17h8M8 9h2"></path></svg>
            </div>
            <h3 class="sta-quick-title"><?php echo esc_html(sta_t('Studieprogramevaluering', 'Programme Evaluation', 'Studieprogramevaluering')); ?></h3>
            <p class="sta-quick-desc"><?php echo esc_html(sta_t('Åpne mal i Microsoft Forms', 'Open template in Microsoft Forms', 'Opna mal i Microsoft Forms')); ?></p>
        </a>
    </div>

    <!-- Informative Text Cards -->
    <div style="max-width:896px; margin:0 auto; display:flex; flex-direction:column; gap:2rem;">
        <!-- Card 1: Din rolle -->
        <div class="sta-card" style="padding:2rem;">
            <h3 style="font-size:1.5rem; font-weight:800; color:#fff; margin:0 0 1rem;">
                <?php echo esc_html(sta_t('Din rolle som studenttillitsvalgt', 'Your Role as a Student Representative', 'Rolla di som studenttillitsvald')); ?>
            </h3>
            <p style="font-size:0.95rem; color:var(--sta-teal-100); line-height:1.7; margin:0 0 1.25rem;">
                <?php echo esc_html(sta_t('En tillitsvalgt er en valgt person som har oppnådd tilliten fra sine medstudenter på Universitetet i Agder (UiA) til å representere dem i prosesser, møter og samlinger hvor studentenes stemme skal løftes. Som tillitsvalgt spiller du en avgjørende rolle for kvalitetssystemet ved UiA.', 'A representative is an elected student who has gained the trust of their peers at the University of Agder (UiA) to represent them in meetings and committees. As a representative, you play a vital role in UiAs educational quality system.', 'Ein tillitsvald er ein vald person som har oppnådd tillit frå medstudentane sine ved Universitetet i Agder (UiA) til å representere dei i prosessar, møte og samlingar der stemma til studentane skal løftast. Som tillitsvald spelar du ei avgjerande rolle for kvalitetssystemet ved UiA.')); ?>
            </p>
            <div style="padding:1rem 1.25rem; border-radius:var(--sta-radius-sm); background:rgba(9,50,47,0.8); border:1px solid rgba(239,115,48,0.4); font-size:0.85rem; color:#fff; line-height:1.6;">
                <strong style="color:var(--sta-orange-text);"><?php echo esc_html(sta_t('Viktig:', 'Important:', 'Viktig:')); ?></strong> 
                <?php echo esc_html(sta_t('Når du er valgt, må du registrere deg på', 'Once elected, you must register at', 'Når du er vald, må du registrere deg på')); ?> 
                <a href="https://innsia.uia.no" target="_blank" rel="noopener noreferrer" style="color:var(--sta-orange-text); font-weight:700; text-decoration:underline;">innsia.uia.no</a> 
                <?php echo esc_html(sta_t('for å motta viktig informasjon, invitasjoner til tillitsvalgtsamlinger og opplæring.', 'to receive important updates, invitations to training seminars, and resources.', 'for å få viktig informasjon, invitasjonar til tillitsvaldsamlingar og opplæring.')); ?>
            </div>
        </div>

        <!-- Card 2: 3-step escalation -->
        <div class="sta-card" style="padding:2rem;">
            <h3 style="font-size:1.5rem; font-weight:800; color:#fff; margin:0 0 1.25rem;">
                <?php echo esc_html(sta_t('Hvem skal du henvende deg til ved problemer?', 'Who Should You Contact in Case of Problems?', 'Kven skal du venda deg til ved problem?')); ?>
            </h3>

            <div style="display:flex; flex-direction:column; gap:1.25rem;">
                <div style="display:flex; align-items:flex-start; gap:1rem;">
                    <span style="width:2rem; height:2rem; border-radius:50%; background:rgba(239,115,48,0.2); color:var(--sta-orange-text); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:0.85rem; flex-shrink:0; margin-top:0.2rem;">
                        1
                    </span>
                    <p style="font-size:0.95rem; color:var(--sta-teal-100); line-height:1.6; margin:0;">
                        <strong><?php echo esc_html(sta_t('Første trinn:', 'First step:', 'Første trinn:')); ?></strong> 
                        <?php echo esc_html(sta_t('Forhold deg først til studentgruppen din, emneansvarlig, og instituttleder/studieleder ved lærerutdanningen, samt studierådet.', 'First consult your student group, the course coordinator, department head, and the study council.', 'Hald deg først til studentgruppa di, emneansvarleg, og instituttleiar/studieleiar ved lærarutdanninga, samt studierådet.')); ?>
                    </p>
                </div>

                <div style="display:flex; align-items:flex-start; gap:1rem;">
                    <span style="width:2rem; height:2rem; border-radius:50%; background:rgba(239,115,48,0.2); color:var(--sta-orange-text); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:0.85rem; flex-shrink:0; margin-top:0.2rem;">
                        2
                    </span>
                    <p style="font-size:0.95rem; color:var(--sta-teal-100); line-height:1.6; margin:0;">
                        <strong><?php echo esc_html(sta_t('Andre trinn:', 'Second step:', 'Andre trinn:')); ?></strong> 
                        <?php echo esc_html(sta_t('Ta kontakt med kontaktpersonen for studenttillitsvalgte på ditt fakultet eller avdeling (se oversikt på UiAs nettsider).', 'Contact the faculty student representative coordinator on your faculty.', 'Ta kontakt med kontaktpersonen for studenttillitsvalde på fakultetet eller avdelinga di (sjå oversikt på nettsidene til UiA).')); ?>
                    </p>
                </div>

                <div style="display:flex; align-items:flex-start; gap:1rem;">
                    <span style="width:2rem; height:2rem; border-radius:50%; background:rgba(239,115,48,0.2); color:var(--sta-orange-text); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:0.85rem; flex-shrink:0; margin-top:0.2rem;">
                        3
                    </span>
                    <p style="font-size:0.95rem; color:var(--sta-teal-100); line-height:1.6; margin:0;">
                        <strong><?php echo esc_html(sta_t('Tredje trinn:', 'Third step:', 'Tredje trinn:')); ?></strong> 
                        <?php echo esc_html(sta_t('Dersom emneansvarlig og instituttleder ikke kan bistå, kan du kontakte fag- og læringsmiljøpolitisk ansvarlig i STA på', 'If the course coordinator cannot resolve the matter, contact the STA learning environment officer at', 'Dersom emneansvarleg og instituttleiar ikkje kan hjelpe, kan du kontakte fag- og læringsmiljøpolitisk ansvarleg i STA på')); ?> 
                        <a href="mailto:stalaringsmiljo@uia.no" style="color:var(--sta-orange-text); font-weight:700; text-decoration:underline;">stalaringsmiljo@uia.no</a>.
                    </p>
                </div>
            </div>
        </div>

        <!-- 3 Useful Systems Grid -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:1.5rem; margin-top:1rem;">
            <!-- Si ifra -->
            <div class="sta-card" style="padding:1.5rem; display:flex; flex-direction:column; justify-content:space-between;">
                <div>
                    <div style="color:var(--sta-orange-text); margin-bottom:0.75rem;">
                        <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    </div>
                    <h4 style="font-size:1.15rem; font-weight:700; color:#fff; margin:0 0 0.5rem;">
                        <?php echo esc_html(sta_t('Si ifra-systemet', 'Speak Up System (Si ifra)', 'Sei ifrå-systemet')); ?>
                    </h4>
                    <p style="font-size:0.85rem; color:var(--sta-teal-100); line-height:1.6; margin:0 0 1rem;">
                        <?php echo esc_html(sta_t('UiAs portal for å varsle om inneklima, fysisk læringsmiljø, mobbing eller kritikkverdige forhold.', 'UiAs portal for reporting poor indoor air, physical safety issues, bullying, or adverse conditions.', 'Portalen til UiA for å varsle om inneklima, fysisk læringsmiljø, mobbing eller kritikkverdige tilhøve.')); ?>
                    </p>
                </div>
                <a href="https://www.uia.no/om-uia/kontakt/si-ifra/" target="_blank" rel="noopener noreferrer" style="font-size:0.85rem; font-weight:700; color:var(--sta-orange-text); display:inline-flex; align-items:center; gap:0.35rem;">
                    <span><?php echo esc_html(sta_t('Gå til Si ifra', 'Go to Speak Up', 'Gå til Sei ifrå')); ?></span>
                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                </a>
            </div>

            <!-- Studentombudet -->
            <div class="sta-card" style="padding:1.5rem; display:flex; flex-direction:column; justify-content:space-between;">
                <div>
                    <div style="color:var(--sta-orange-text); margin-bottom:0.75rem;">
                        <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    </div>
                    <h4 style="font-size:1.15rem; font-weight:700; color:#fff; margin:0 0 0.5rem;">
                        <?php echo esc_html(sta_t('Studentombudet', 'Student Ombud', 'Studentombodet')); ?>
                    </h4>
                    <p style="font-size:0.85rem; color:var(--sta-teal-100); line-height:1.6; margin:0 0 1rem;">
                        <?php echo esc_html(sta_t('Uavhengig rådgiver som sikrer studentenes rettssikkerhet i møte med universitetet.', 'Independent advisor who safeguards students legal rights and procedural fairness.', 'Uavhengig rådgjevar som sikrar rettstryggleiken til studentane i møte med universitetet.')); ?>
                    </p>
                </div>
                <a href="https://www.uia.no/studier/for-studenter/veiledning-og-kurs/studentombud/" target="_blank" rel="noopener noreferrer" style="font-size:0.85rem; font-weight:700; color:var(--sta-orange-text); display:inline-flex; align-items:center; gap:0.35rem;">
                    <span><?php echo esc_html(sta_t('Kontakt Studentombudet', 'Contact the Ombud', 'Kontakt Studentombodet')); ?></span>
                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                </a>
            </div>

            <!-- SiA Helse -->
            <div class="sta-card" style="padding:1.5rem; display:flex; flex-direction:column; justify-content:space-between;">
                <div>
                    <div style="color:var(--sta-orange-text); margin-bottom:0.75rem;">
                        <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                    </div>
                    <h4 style="font-size:1.15rem; font-weight:700; color:#fff; margin:0 0 0.5rem;">
                        <?php echo esc_html(sta_t('SiA Helse', 'SiA Health', 'SiA Helse')); ?>
                    </h4>
                    <p style="font-size:0.85rem; color:var(--sta-teal-100); line-height:1.6; margin:0 0 1rem;">
                        <?php echo esc_html(sta_t('Gratis samtaleterapi, kurs og helsetilbud for studenter som opplever personlige eller psykiske utfordringer.', 'Free counseling, courses, and health provisions for students experiencing personal or mental distress.', 'Gratis samtaleterapi, kurs og helsetilbod for studentar som opplever personlege eller psykiske utfordringar.')); ?>
                    </p>
                </div>
                <a href="https://sia.no/Helse" target="_blank" rel="noopener noreferrer" style="font-size:0.85rem; font-weight:700; color:var(--sta-orange-text); display:inline-flex; align-items:center; gap:0.35rem;">
                    <span><?php echo esc_html(sta_t('Gå til SiA Helse', 'Go to SiA Health', 'Gå til SiA Helse')); ?></span>
                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
