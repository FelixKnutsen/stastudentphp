<?php
/**
 * Template Name: Irene Bredals Hederspris (Honour Award)
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
$is_en = sta_is_en();
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <header class="sta-page-header">
        <span class="sta-badge"><?php echo esc_html(sta_t('Hederspris', 'Honour Award')); ?></span>
        <h1 class="sta-hero-title">
            <?php echo esc_html(sta_t('Irene Bredals Hederspris', 'Irene Bredals Honour Award')); ?>
        </h1>
        <p class="sta-hero-subtitle" style="max-width:800px; margin-left:auto; margin-right:auto;">
            <?php echo esc_html(sta_t('En utmerkelse til personer som over tid har vist dedikasjon og ekstraordinær innsats for studentenes beste ved Universitetet i Agder.', 'An honour bestowed upon individuals who over time have shown dedicated service and extraordinary efforts for students at the University of Agder.')); ?>
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
                        <?php echo esc_html(sta_t('Om hedersprisen', 'About the Honour Award')); ?>
                    </h2>
                    <p style="font-size:0.75rem; color:var(--sta-orange); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin:0.25rem 0 0 0;">
                        <?php echo esc_html(sta_t('Opprettet 2024/2025', 'Established 2024/2025')); ?>
                    </p>
                </div>
            </div>

            <p style="font-size:1.05rem; color:var(--sta-teal-100); line-height:1.7; margin:0;">
                <?php echo esc_html(sta_t('Studentorganisasjonen i Agders hederspris gis ut til personer som over tid har vist dedikasjon og innsats i arbeid for UiA studentenes beste. Alle personer kan nomineres til prisen, så lenge mottaker har utvist arbeid for å styrke STA og studentstemmen på UiA.', 'The Student Organisation of Agders Honour Award is presented to individuals who have consistently demonstrated dedication and service to the benefit of UiA students. Anyone can be nominated as long as the nominee has strengthened STA and the student voice.')); ?>
            </p>

            <div style="padding:1.25rem 1.5rem; border-radius:0.75rem; background:rgba(14,76,71,0.8); border:1px solid rgba(158,207,201,0.3); display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:1.25rem;">
                <div>
                    <h3 style="font-size:0.95rem; font-weight:700; color:#fff; margin:0 0 0.25rem 0;">
                        <?php echo esc_html(sta_t('Ønsker du å nominere noen?', 'Want to nominate someone?')); ?>
                    </h3>
                    <p style="font-size:0.8rem; color:var(--sta-teal-200); margin:0;">
                        <?php echo esc_html(sta_t('Les statuttene eller send en nominasjon på e-post til STA.', 'Read the award statutes or send a nomination email to STA.')); ?>
                    </p>
                </div>
                <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
                    <a href="https://stastudent.no/wp-content/uploads/2025/11/her.pdf" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="sta-btn sta-btn-outline" 
                       style="padding:0.45rem 1rem; font-size:0.8rem; display:inline-flex; align-items:center; gap:0.4rem;">
                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        <span><?php echo esc_html(sta_t('Statutter (PDF)', 'Statutes (PDF)')); ?></span>
                    </a>
                    <a href="mailto:sta@uia.no?subject=<?php echo rawurlencode(sta_t('Nominasjon til Irene Bredals Hederspris', 'Nomination for Irene Bredal Honour Award')); ?>" 
                       class="sta-btn sta-btn-orange" 
                       style="padding:0.45rem 1.15rem; font-size:0.8rem; display:inline-flex; align-items:center; gap:0.4rem; font-weight:700;">
                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <span><?php echo esc_html(sta_t('Send nominasjon', 'Submit Nomination')); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 2024/2025 Recipient: Irene Bredal -->
    <div style="max-width:56rem; margin:0 auto;">
        <h2 style="font-size:1.6rem; font-weight:700; color:#fff; text-align:center; margin-bottom:2rem;">
            <?php echo esc_html(sta_t('Hedersprismottaker 2024/2025: Irene Bredal', 'Award Recipient 2024/2025: Irene Bredal')); ?>
        </h2>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:2rem; align-items:center; background:rgba(14,76,71,0.4); border:1px solid rgba(158,207,201,0.3); border-radius:1rem; padding:2rem;">
            <div>
                <div style="position:relative; aspect-ratio:3/4; width:100%; max-width:320px; margin:0 auto; border-radius:0.75rem; overflow:hidden; border:1px solid rgba(158,207,201,0.3); box-shadow:0 10px 25px rgba(0,0,0,0.3); background:var(--sta-card-bg);">
                    <img src="https://stastudent.no/wp-content/uploads/2025/06/Kopi-av-Kopi-av-Overskrift.png" 
                         alt="<?php echo esc_attr(sta_t('Irene Bredals Hederspris plakat', 'Irene Bredal Honour Award poster')); ?>" 
                         style="width:100%; height:100%; object-fit:cover;"
                         loading="lazy">
                </div>
            </div>

            <div style="display:flex; flex-direction:column; gap:1.25rem;">
                <blockquote style="font-size:1rem; color:var(--sta-teal-100); font-style:italic; line-height:1.75; border-left:4px solid var(--sta-orange); padding-left:1.25rem; margin:0;">
                    &laquo;<?php echo esc_html(sta_t('Irene har i mer enn 18 år kjempet for studentens beste på UiA og bekledd de fleste verv det er mulig for en engasjert student å ha. Som leder for STA satte hun organisasjonen på kartet. Senere som organisasjonskonsulent har hun bidratt til at ulike styrer og studenter har fått styrket sin rolle på UiA. Irene er på mange måter selve bildet på studentengasjementet på UiA.', 'For over 18 years, Irene has fought tirelessly for students at UiA, occupying almost every volunteer position imaginable. As President of STA, she put the organization on the map. Later as Organizational Consultant, she helped successive boards and student reps strengthen their voices. Irene embodies student engagement at UiA.')); ?>&raquo;
                </blockquote>

                <p style="font-size:0.85rem; color:var(--sta-teal-200); line-height:1.6; margin:0;">
                    <?php echo esc_html(sta_t('Irene Bredal var den aller første mottakeren av prisen, som også ble oppkalt etter henne i anerkjennelse av hennes uvurderlige innsats gjennom nesten to tiår for studentene ved UiA.', 'Irene Bredal was the very first recipient of the award, which was named in her honour in recognition of her invaluable contribution spanning nearly two decades.')); ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
