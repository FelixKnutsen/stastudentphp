<?php
/**
 * Template Name: Prinsipprogram
 * Template for /prinsipprogram detailing the status of STA's Principles Programme
 * and presenting both the current thematic policy model and the historical 2023–2025 archive.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
$is_en = sta_is_en();
$is_nn = sta_is_nn();
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <!-- Breadcrumb navigation -->
    <div style="margin-bottom:2rem;">
        <a href="<?php echo esc_url(sta_get_localized_url('/dokumenter')); ?>" style="color:var(--sta-teal-200); font-size:0.9rem; display:inline-flex; align-items:center; gap:0.35rem; text-decoration:none;">
            &larr; <?php echo esc_html(sta_t('Tilbake til Dokumenter', 'Back to Documents', 'Attende til Dokument')); ?>
        </a>
    </div>

    <!-- Page Header -->
    <header class="sta-page-header" style="text-align:center; max-width:840px; margin:0 auto 3rem;">
        <span class="sta-badge"><?php echo esc_html(sta_t('Politisk styringsmodell', 'Policy Governance Model', 'Politisk styringsmodell')); ?></span>
        <h1 class="sta-hero-title" style="font-size:2.75rem; margin-top:0.5rem;">
            <?php echo esc_html(sta_t('Prinsipprogram', 'Principles Programme', 'Prinsipprogram')); ?>
        </h1>
        <p style="color:var(--sta-teal-100); font-size:1.15rem; margin-top:0.75rem; line-height:1.6;">
            <?php echo esc_html(sta_t('Informasjon om STAs prinsipprogram og hvordan studentpolitikken ved Universitetet i Agder i dag styres og vedtas.', 'Information about STA\'s principles programme and how student policy at the University of Agder is governed today.', 'Informasjon om STA sitt prinsipprogram og korleis studentpolitikken ved Universitetet i Agder i dag blir styrt og vedteken.')); ?>
        </p>
    </header>

    <!-- Notice Card: Current Policy Status -->
    <div style="max-width:900px; margin:0 auto 4rem; background:rgba(239,115,48,0.1); border:1px solid rgba(239,115,48,0.4); border-radius:var(--sta-radius); padding:2rem; box-shadow:0 12px 32px rgba(0,0,0,0.25);">
        <div style="display:flex; align-items:flex-start; gap:1.25rem;">
            <div style="width:3rem; height:3rem; border-radius:0.75rem; background:rgba(239,115,48,0.25); color:var(--sta-orange); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            </div>
            <div>
                <h2 style="font-size:1.35rem; font-weight:800; color:#fff; margin:0 0 0.75rem 0;">
                    <?php echo esc_html(sta_t('Gjeldende status: Ingen eget prinsipprogram', 'Current Status: No Standalone Principles Programme', 'Gjeldande status: Ikkje eige prinsipprogram')); ?>
                </h2>
                <p style="font-size:1rem; color:var(--sta-teal-50); line-height:1.7; margin:0 0 1rem 0;">
                    <?php echo esc_html(sta_t(
                        'Studentparlamentet i STA har besluttet at organisasjonen ikke skal ha et eget overordnet prinsipprogram. STAs politiske plattform og studentenes stemme forankres og styres i dag direkte gjennom organisasjonens fire tematiske politiske styringsdokumenter, den årlige handlingsplanen og fortløpende resolusjoner vedtatt av parlamentet.',
                        'The Student Parliament in STA has resolved that the organization will no longer maintain a standalone Principles Programme. STA\'s political platform is now anchored and governed directly through four thematic policy documents, the annual action plan, and ongoing resolutions adopted by the parliament.',
                        'Studentparlamentet i STA har vedteke at organisasjonen ikkje skal ha eit eige overordna prinsipprogram. STA sin politiske plattform og stemma til studentane blir i dag forankra og styrt direkte gjennom organisasjonen sine fire tematiske politiske styringsdokument, den årlege handlingsplanen og fortløpande resolusjonar vedtekne av parlamentet.'
                    )); ?>
                </p>
                <a href="<?php echo esc_url(sta_get_localized_url('/dokumenter')); ?>" class="sta-btn sta-btn-orange" style="display:inline-flex; align-items:center; gap:0.5rem; font-size:0.9rem; padding:0.65rem 1.25rem;">
                    <span><?php echo esc_html(sta_t('Se gjeldende politiske dokumenter', 'View Current Policy Documents', 'Sjå gjeldande politiske dokument')); ?></span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Active Policy Documents Grid -->
    <div style="max-width:960px; margin:0 auto 4.5rem;">
        <h2 style="font-size:1.5rem; font-weight:800; color:#fff; margin-bottom:1.5rem; text-align:center;">
            <?php echo esc_html(sta_t('Gjeldende politiske styringsdokumenter', 'Current Policy Documents in Force', 'Gjeldande politiske styringsdokument')); ?>
        </h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.25rem;">
            <div class="sta-card" style="padding:1.5rem; display:flex; flex-direction:column; justify-content:space-between;">
                <div>
                    <h3 style="font-size:1.15rem; font-weight:700; color:#fff; margin:0 0 0.5rem 0;">Studentvelferd</h3>
                    <p style="font-size:0.875rem; color:var(--sta-teal-100); line-height:1.6; margin:0;">
                        <?php echo esc_html(sta_t('Politiske prinsipper og krav for studentøkonomi, bolig, helsetilbud og velferd.', 'Policy principles and priorities for student finances, housing, healthcare and welfare.', 'Politiske prinsipp og krav for studentøkonomi, bustad, helsetilbod og velferd.')); ?>
                    </p>
                </div>
                <div style="margin-top:1.25rem; padding-top:0.75rem; border-top:1px solid rgba(158,207,201,0.18);">
                    <a href="https://stastudent.no/wp-content/uploads/2026/05/Politisk-dokument-for-Studentvelferd-2026.pdf" target="_blank" rel="noopener noreferrer" style="color:var(--sta-orange); font-size:0.85rem; font-weight:700; display:inline-flex; align-items:center; gap:0.35rem; text-decoration:none;">
                        <span><?php echo esc_html(sta_t('Last ned PDF', 'Download PDF', 'Last ned PDF')); ?></span>
                        <svg viewBox="0 0 24 24" width="13" height="13" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    </a>
                </div>
            </div>

            <div class="sta-card" style="padding:1.5rem; display:flex; flex-direction:column; justify-content:space-between;">
                <div>
                    <h3 style="font-size:1.15rem; font-weight:700; color:#fff; margin:0 0 0.5rem 0;">Læringsmiljø og bærekraft</h3>
                    <p style="font-size:0.875rem; color:var(--sta-teal-100); line-height:1.6; margin:0;">
                        <?php echo esc_html(sta_t('Retningslinjer for fysisk, digitalt og psykososialt læringsmiljø samt klima og miljø ved UiA.', 'Guidelines for physical, digital and psychosocial study environment, sustainability and climate.', 'Retningslinjer for fysisk, digitalt og psykososialt læringsmiljø samt klima og miljø ved UiA.')); ?>
                    </p>
                </div>
                <div style="margin-top:1.25rem; padding-top:0.75rem; border-top:1px solid rgba(158,207,201,0.18);">
                    <a href="https://stastudent.no/wp-content/uploads/2026/05/Politisk-dokument-for-Laeringsmiljo-og-Baerekraft.pdf" target="_blank" rel="noopener noreferrer" style="color:var(--sta-orange); font-size:0.85rem; font-weight:700; display:inline-flex; align-items:center; gap:0.35rem; text-decoration:none;">
                        <span><?php echo esc_html(sta_t('Last ned PDF', 'Download PDF', 'Last ned PDF')); ?></span>
                        <svg viewBox="0 0 24 24" width="13" height="13" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    </a>
                </div>
            </div>

            <div class="sta-card" style="padding:1.5rem; display:flex; flex-direction:column; justify-content:space-between;">
                <div>
                    <h3 style="font-size:1.15rem; font-weight:700; color:#fff; margin:0 0 0.5rem 0;">Utdanningskvalitet</h3>
                    <p style="font-size:0.875rem; color:var(--sta-teal-100); line-height:1.6; margin:0;">
                        <?php echo esc_html(sta_t('Vår politikk for undervisning, vurderingsformer, pensum, sensorordninger og forskning.', 'Our policies on pedagogy, assessment methods, curricula, grading and academic research.', 'Politikken vår for undervisning, vurderingsformer, pensum, sensurordningar og forsking.')); ?>
                    </p>
                </div>
                <div style="margin-top:1.25rem; padding-top:0.75rem; border-top:1px solid rgba(158,207,201,0.18);">
                    <a href="https://stastudent.no/wp-content/uploads/2026/05/Politisk-dokument-for-Utdanningskvalitet-2026.pdf" target="_blank" rel="noopener noreferrer" style="color:var(--sta-orange); font-size:0.85rem; font-weight:700; display:inline-flex; align-items:center; gap:0.35rem; text-decoration:none;">
                        <span><?php echo esc_html(sta_t('Last ned PDF', 'Download PDF', 'Last ned PDF')); ?></span>
                        <svg viewBox="0 0 24 24" width="13" height="13" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    </a>
                </div>
            </div>

            <div class="sta-card" style="padding:1.5rem; display:flex; flex-direction:column; justify-content:space-between;">
                <div>
                    <h3 style="font-size:1.15rem; font-weight:700; color:#fff; margin:0 0 0.5rem 0;">Mangfold og internasjonalisering</h3>
                    <p style="font-size:0.875rem; color:var(--sta-teal-100); line-height:1.6; margin:0;">
                        <?php echo esc_html(sta_t('Likestilling, universell utforming, inkludering og vilkår for utveksling og internasjonale studenter.', 'Gender equality, universal design, inclusion and conditions for exchange and international students.', 'Likestilling, universell utforming, inkludering og vilkår for utveksling og internasjonale studentar.')); ?>
                    </p>
                </div>
                <div style="margin-top:1.25rem; padding-top:0.75rem; border-top:1px solid rgba(158,207,201,0.18);">
                    <a href="https://stastudent.no/wp-content/uploads/2026/05/Politisk-dokument-for-Mangfold-og-Internasjonalisering.pdf" target="_blank" rel="noopener noreferrer" style="color:var(--sta-orange); font-size:0.85rem; font-weight:700; display:inline-flex; align-items:center; gap:0.35rem; text-decoration:none;">
                        <span><?php echo esc_html(sta_t('Last ned PDF', 'Download PDF', 'Last ned PDF')); ?></span>
                        <svg viewBox="0 0 24 24" width="13" height="13" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Historical Archive Section: Prinsipprogrammet 2023-2025 -->
    <section style="max-width:900px; margin:0 auto; padding-top:2rem; border-top:1px solid rgba(158,207,201,0.2);">
        <div style="text-align:center; margin-bottom:2.5rem;">
            <span class="sta-badge" style="background:rgba(255,255,255,0.08); border-color:rgba(255,255,255,0.2); color:var(--sta-teal-100);">
                <?php echo esc_html(sta_t('Historisk arkiv', 'Historical Archive', 'Historisk arkiv')); ?>
            </span>
            <h2 style="font-size:2rem; font-weight:800; color:#fff; margin:0.5rem 0;">
                <?php echo esc_html(sta_t('Prinsipprogrammet 2023–2025 (Historisk)', 'The Principles Programme 2023–2025 (Historical)', 'Prinsipprogrammet 2023–2025 (Historisk)')); ?>
            </h2>
            <p style="font-size:0.95rem; color:var(--sta-teal-200); margin:0;">
                <?php echo esc_html(sta_t('Vedtatt av Studentparlamentet 22. november 2023. Gikk ut i 2025.', 'Adopted by the Student Parliament 22 November 2023. Expired in 2025.', 'Vedteke av Studentparlamentet 22. november 2023. Gjekk ut i 2025.')); ?>
            </p>
        </div>

        <div style="display:flex; flex-direction:column; gap:1.75rem;">
            <!-- Pillar 1 -->
            <div class="sta-card" style="padding:2rem;">
                <h3 style="font-size:1.35rem; font-weight:800; color:var(--sta-orange); margin:0 0 0.75rem 0;">
                    1. Studiesituasjon
                </h3>
                <div style="font-size:0.95rem; color:var(--sta-teal-50); line-height:1.75;">
                    <p style="margin:0 0 1rem 0;">
                        Alle skal ha lik rett, mulighet og tilgang til høyere utdanning. Det skal aktivt arbeides for å utjevne forskjeller som hindrer enkeltindividets mulighet til å delta i, og fullføre høyere utdanning. Sektoren skal være universelt utformet og offentlig høyere utdanning skal være gratis slik at heltidsstudenten realiseres.
                    </p>
                    <p style="margin:0 0 1rem 0;">
                        Alle studenter skal ha en trygg studiehverdag uten trakassering og diskriminering. Studentenes læringsmiljø skal ha et sterkt rettslig vern, på lik linje med arbeidstakers arbeidsmiljø. Alle som studerer på Universitetet i Agder (UiA) skal oppleve mestring, og ha et læringsmiljø som gir rom for å lykkes med studiene.
                    </p>
                    <p style="margin:0;">
                        Studentenes psykiske og fysiske helse er et offentlig samfunnsansvar. Det skal tilrettelegges for at alle studenter kan delta i studentfrivilligheten. Studentenes velferdstilbud og økonomiske støtteordninger skal skape trygge og forutsigbare rammer for studenten uavhengig av studiesituasjonen.
                    </p>
                </div>
            </div>

            <!-- Pillar 2 -->
            <div class="sta-card" style="padding:2rem;">
                <h3 style="font-size:1.35rem; font-weight:800; color:var(--sta-orange); margin:0 0 0.75rem 0;">
                    2. Kvalitetskultur og dannelse
                </h3>
                <div style="font-size:0.95rem; color:var(--sta-teal-50); line-height:1.75;">
                    <p style="margin:0 0 1rem 0;">
                        Høyere utdanning skal være av høy kvalitet, internasjonalt orientert, forskningsbasert og samfunnsrelevant. Studentene skal være en aktiv del av det akademiske felleskapet og ses på som en likeverdig part i arbeidet med å sikre høy kvalitet i utdanning, og en ressurs for forskning.
                    </p>
                    <p style="margin:0 0 1rem 0;">
                        De ansatte skal ha høy faglig og pedagogisk kompetanse. Vurdering av studentenes kompetanse skal skje på en rettferdig, variert, pedagogisk og faglig begrunnet måte. Studentmedvirkning er en sentral del av utdanningen.
                    </p>
                    <p style="margin:0;">
                        Høyere utdanning skal bidra til akademisk dannelse. Den skal gi studenter kompetanse til å utvikle analytiske egenskaper, til å forstå kritisk tenkning og bidra til utvikling av faglig etisk sans. Dette skal gi studenter verktøy til å stille kritiske spørsmål i utviklingen av akademia, også i samfunnet for øvrig som deltagende borgere.
                    </p>
                </div>
            </div>

            <!-- Pillar 3 -->
            <div class="sta-card" style="padding:2rem;">
                <h3 style="font-size:1.35rem; font-weight:800; color:var(--sta-orange); margin:0 0 0.75rem 0;">
                    3. Akademias samfunnsansvar
                </h3>
                <div style="font-size:0.95rem; color:var(--sta-teal-50); line-height:1.75;">
                    <p style="margin:0 0 1rem 0;">
                        Grunnsteinen i et demokrati er å utfordre etablert kunnskap. Akademia har et fellesansvar for å informere borgere om meningsbrytende kunnskap slik at samfunnet kommer frem til de beste løsningene som sikrer samfunnets beste. Akademia skal være en aktiv deltager i den felles samfunnsdebatten. Institusjonen skal være bevisst på sitt ansvar og synliggjøre på hvilken måte de selv svarer på utfordringene samfunnet står ovenfor. Utdanning, forskning og innovasjon er viktig for å bygge opp og videreutvikle hele samfunnet.
                    </p>
                    <p style="margin:0;">
                        I norsk akademia skal det være faglig autonome institusjoner som prioriterer akademisk frihet. Det er viktig å verne rundt studenters og ansattes ytringsfrihet, rettigheter og faglig frihet.
                    </p>
                </div>
            </div>

            <!-- Pillar 4 -->
            <div class="sta-card" style="padding:2rem;">
                <h3 style="font-size:1.35rem; font-weight:800; color:var(--sta-orange); margin:0 0 0.75rem 0;">
                    4. Internasjonalisering
                </h3>
                <div style="font-size:0.95rem; color:var(--sta-teal-50); line-height:1.75;">
                    <p style="margin:0 0 1rem 0;">
                        Høyere utdanning skal være en arena for internasjonalisering av utdanning, kunnskap og kultur. Internasjonalisering skal sees på som en ressurs, og må derfor investeres i for å sikre internasjonale perspektiver hos studenter og ansatte.
                    </p>
                    <p style="margin:0 0 1rem 0;">
                        Universitetet skal arbeide for større tilrettelegging for både utveksling, men også inkludering av innvekslende studenter. Internasjonalisering er en ressurs for institusjonen og studentene som bidrar til bredere kunnskap og perspektiver. Studenter som innveksler til Norge skal ha gode forutsetninger til å delta både faglig og sosialt i studielivet.
                    </p>
                    <p style="margin:0;">
                        Studenter skal ha rett til trygghet, akademisk frihet og demokratiske rettigheter. Internasjonal solidaritet i akademia er viktig for å bygge et sterkt internasjonalt studentfelleskap og for å styrke den globale studentstemmen.
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
get_footer();
