<?php
/**
 * Template Name: Irene Bredals Hederspris (Honour Award)
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
$is_en = sta_is_en();
$is_nn = sta_is_nn();
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <header class="sta-page-header">
        <span class="sta-badge"><?php echo esc_html(sta_t('Hederspris', 'Honour Award', 'Heiderspris')); ?></span>
        <h1 class="sta-hero-title">
            <?php echo esc_html(sta_t('Irene Bredals Hederspris', 'Irene Bredals Honour Award', 'Irene Bredals Heiderspris')); ?>
        </h1>
        <p class="sta-hero-subtitle" style="max-width:800px; margin-left:auto; margin-right:auto;">
            <?php echo esc_html(sta_t('En utmerkelse til personer som over tid har vist dedikasjon og ekstraordinær innsats for studentenes beste ved Universitetet i Agder.', 'An honour bestowed upon individuals who over time have shown dedicated service and extraordinary efforts for students at the University of Agder.', 'Ei utmerking til personar som over tid har vist dedikasjon og ekstraordinær innsats for studentane sitt beste ved Universitetet i Agder.')); ?>
        </p>
    </header>

    <!-- Intro Card -->
    <div style="max-width:56rem; margin:0 auto 4rem auto;">
        <div class="sta-card" style="padding:2.5rem; display:flex; flex-direction:column; gap:1.75rem;">
            <div style="display:flex; align-items:center; gap:1rem;">
                <div style="width:3.5rem; height:3.5rem; border-radius:1rem; background:rgba(239,115,48,0.2); color:var(--sta-orange); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                </div>
                <div>
                    <h2 style="font-size:1.5rem; font-weight:700; color:#fff; margin:0;">
                        <?php echo esc_html(sta_t('Om hedersprisen', 'About the Honour Award', 'Om heidersprisen')); ?>
                    </h2>
                    <p style="font-size:0.75rem; color:var(--sta-orange); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin:0.25rem 0 0 0;">
                        <?php echo esc_html(sta_t('Opprettet 2024/2025', 'Established 2024/2025', 'Oppretta 2024/2025')); ?>
                    </p>
                </div>
            </div>

            <p style="font-size:1.05rem; color:var(--sta-teal-100); line-height:1.7; margin:0;">
                <?php echo esc_html(sta_t('Studentorganisasjonen i Agders hederspris gis ut til personer som over tid har vist dedikasjon og innsats i arbeid for UiA studentenes beste. Alle personer kan nomineres til prisen, så lenge mottaker har utvist arbeid for å styrke STA og studentstemmen på UiA.', 'The Student Organisation of Agders Honour Award is presented to individuals who have consistently demonstrated dedication and service to the benefit of UiA students. Anyone can be nominated as long as the nominee has strengthened STA and the student voice.', 'Studentorganisasjonen i Agder sin heiderspris blir delt ut til personar som over tid har vist dedikasjon og innsats i arbeidet for studentane ved UiA sitt beste. Alle personar kan nominerast til prisen, så lenge mottakaren har lagt ned arbeid for å styrkje STA og studentstemma ved UiA.')); ?>
            </p>

            <div style="padding:1.25rem 1.5rem; border-radius:0.75rem; background:rgba(14,76,71,0.8); border:1px solid rgba(158,207,201,0.3); display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:1.25rem;">
                <div>
                    <h3 style="font-size:0.95rem; font-weight:700; color:#fff; margin:0 0 0.25rem 0;">
                        <?php echo esc_html(sta_t('Ønsker du å nominere noen?', 'Want to nominate someone?', 'Ynskjer du å nominere nokon?')); ?>
                    </h3>
                    <p style="font-size:0.8rem; color:var(--sta-teal-200); margin:0;">
                        <?php echo esc_html(sta_t('Les statuttene eller send en nominasjon på e-post til STA.', 'Read the award statutes or send a nomination email to STA.', 'Les statuttene eller send ein nominasjon på e-post til STA.')); ?>
                    </p>
                </div>
                <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
                    <a href="https://stastudent.no/wp-content/uploads/2025/11/her.pdf" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="sta-btn sta-btn-outline" 
                       style="padding:0.45rem 1rem; font-size:0.8rem; display:inline-flex; align-items:center; gap:0.4rem;">
                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                        <span><?php echo esc_html(sta_t('Statutter for prisen (PDF)', 'Award Statutes (PDF)', 'Statutt for prisen (PDF)')); ?></span>
                    </a>
                    <a href="mailto:sta@uia.no?subject=<?php echo rawurlencode(sta_t('Nominasjon: Irene Bredals Hederspris', 'Nomination: Irene Bredals Honour Award', 'Nominasjon: Irene Bredals Heiderspris')); ?>" 
                       class="sta-btn sta-btn-orange" 
                       style="padding:0.45rem 1.25rem; font-size:0.8rem; font-weight:700;">
                        <span><?php echo esc_html(sta_t('Send nominasjon', 'Send nomination', 'Send nominasjon')); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Award Winners Archive (2025: Irene Bredal) -->
    <div style="max-width:56rem; margin:0 auto 4rem auto;">
        <h2 style="font-size:1.75rem; font-weight:700; color:#fff; text-align:center; margin-bottom:2rem;">
            <?php echo esc_html(sta_t('Mottakere av hedersprisen', 'Honour Award Recipients', 'Mottakarar av heidersprisen')); ?>
        </h2>

        <div class="sta-card" style="padding:2.5rem; display:flex; flex-direction:column; gap:2rem;">
            <div class="sta-award-recipient-layout">
                <!-- Portrait Image -->
                <div style="position:relative; width:14rem; height:18rem; border-radius:1rem; overflow:hidden; border:2px solid rgba(239,115,48,0.5); box-shadow:0 10px 25px rgba(0,0,0,0.5); flex-shrink:0;">
                    <img src="https://stastudent.no/wp-content/uploads/2025/06/Kopi-av-Kopi-av-Overskrift-1024x1448.png" 
                         alt="Irene Bredal mottar hedersprisen" 
                         style="width:100%; height:100%; object-fit:cover;"
                         loading="lazy">
                </div>

                <!-- Award Content -->
                <div style="flex:1;">
                    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.5rem;">
                        <span class="sta-badge" style="background:var(--sta-orange); color:#fff; border:none; font-size:0.75rem;">
                            2025
                        </span>
                        <span style="font-size:0.8rem; color:var(--sta-teal-200); font-weight:600;">
                            <?php echo esc_html(sta_t('Første mottaker', 'First Recipient', 'Første mottakar')); ?>
                        </span>
                    </div>

                    <h3 style="font-size:1.6rem; font-weight:800; color:#fff; margin:0 0 0.5rem 0;">
                        Irene Bredal
                    </h3>
                    <p style="font-size:0.9rem; color:var(--sta-orange); font-weight:600; margin:0 0 1.25rem 0;">
                        <?php echo esc_html(sta_t('Tidligere organisasjonskonsulent i STA', 'Former Organizational Consultant at STA', 'Tidlegare organisasjonskonsulent i STA')); ?>
                    </p>

                    <div style="font-size:0.95rem; color:var(--sta-teal-100); line-height:1.7; display:flex; flex-direction:column; gap:0.75rem;">
                        <p style="margin:0;">
                            <?php echo esc_html(sta_t(
                                'Irene Bredal ble den aller første mottakeren av Studentorganisasjonen i Agders hederspris. Prisen ble opprettet og oppkalt etter henne som en anerkjennelse for hennes mangeårige, uvurderlige innsats for studentdemokratiet, linjeforeningene og studentenes velferd ved Universitetet i Agder.',
                                'Irene Bredal became the very first recipient of the Student Organisation of Agders Honour Award. The award was founded and named after her in recognition of her dedicated, invaluable efforts for student democracy, associations, and student welfare at the University of Agder.',
                                'Irene Bredal vart den aller første mottakaren av Studentorganisasjonen i Agder sin heiderspris. Prisen vart oppretta og kalla opp etter henne som ei anerkjenning for den mangeårige, uvurderlege innsatsen hennar for studentdemokratiet, linjeforeiningane og velferda til studentane ved Universitetet i Agder.'
                            )); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
