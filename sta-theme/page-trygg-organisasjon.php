<?php
/**
 * Template Name: Trygg Organisasjon (Safe Organization)
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
$is_en = sta_is_en();

$principles_no = array(
    'Alle skal føle seg velkommen og respektert i STA. Dette betyr at man skal avstå fra uttalelser og/eller fysisk atferd som oppleves som ubehagelig, diskriminerende, trakasserende eller krenkende for andre.',
    'Alle har et ansvar for å bidra til et inkluderende miljø, både ved formelle og uformelle arrangement.',
    'Alle skal vise respekt for hverandres grenser.',
    'Press rundt bruk av alkohol og andre rusmidler skal ikke finne sted. Ved alle arrangement i regi av en studentaktivitet eller linjeforening skal man ha 1–2 tillitspersoner.',
    'Alle har et ansvar i å forhindre mobbing. STA har nulltoleranse for mobbing.',
    'Alle skal vise respekt for hverandres ytringer og møte hverandre på en konstruktiv måte.'
);

$principles_en = array(
    'Everyone should feel welcome and respected in STA. This means abstaining from statements and physical behavior that can be perceived as uncomfortable, discriminatory, harassing, or offensive to others.',
    'Everyone shares responsibility for contributing to an inclusive environment, both at formal and informal events.',
    'Everyone must respect each other\'s personal boundaries.',
    'Peer pressure regarding alcohol and other substances is prohibited. All events organized by student activities or academic associations must designate 1–2 trusted contacts.',
    'Everyone has a duty to prevent bullying. STA operates with zero tolerance for bullying.',
    'Everyone must show respect for differing opinions and engage with one another constructively.'
);

$principles = $is_en ? $principles_en : $principles_no;
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <header class="sta-page-header">
        <span class="sta-badge"><?php echo esc_html(sta_t('Retningslinjer', 'Guidelines')); ?></span>
        <h1 class="sta-hero-title">
            <?php echo esc_html(sta_t('Trygg Organisasjon', 'Safe Organization')); ?>
        </h1>
        <p class="sta-hero-subtitle" style="max-width:800px; margin-left:auto; margin-right:auto;">
            <?php echo esc_html(sta_t('Det skal være gøy, lærerikt og motiverende å engasjere seg i STA.', 'Engaging in STA should be enjoyable, educational, and inspiring.')); ?>
        </p>
    </header>

    <div style="max-width:56rem; margin:0 auto; display:flex; flex-direction:column; gap:2.5rem;">
        <!-- Intro statement Card -->
        <div class="sta-card" style="padding:2rem;">
            <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1.25rem;">
                <div style="width:3rem; height:3rem; border-radius:0.75rem; background:rgba(239,115,48,0.2); color:var(--sta-orange); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="m9 12 2 2 4-4"></path></svg>
                </div>
                <div>
                    <h2 style="font-size:1.35rem; font-weight:700; color:#fff; margin:0;">
                        <?php echo esc_html(sta_t('Nulltoleranse for trakassering', 'Zero Tolerance for Harassment')); ?>
                    </h2>
                    <p style="font-size:0.75rem; color:var(--sta-orange); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin:0.25rem 0 0 0;">
                        <?php echo esc_html(sta_t('Vedtatt av Studentparlamentet', 'Adopted by the Student Parliament')); ?>
                    </p>
                </div>
            </div>

            <p style="font-size:0.95rem; color:var(--sta-teal-100); line-height:1.7; margin-bottom:1rem;">
                <?php echo esc_html(sta_t('Det er av stor verdi at du bidrar til fellesskapet som skaper politikkutvikling, gjennomslag og gode opplevelser for mange andre engasjerte studenter. Sammen skal vi sikre et godt miljø med takhøyde for å diskutere og si meningene våre. Dette gjelder også de sosiale og uformelle delene av alle STA-arrangement.', 'Your contributions to our community foster policy development, real impact, and great memories for many student volunteers. Together, we ensure a welcoming environment where everyone feels safe to voice their opinions. This applies equally to formal meetings and informal social gatherings.')); ?>
            </p>
            <p style="font-size:0.95rem; color:var(--sta-teal-100); line-height:1.7; margin:0;">
                <?php echo wp_kses_post(sta_t('Som engasjert i STA har vi <strong>ingen toleranse for trakassering eller diskriminering</strong>. Vi tar vare på hverandre og viser gjensidig respekt. Det er et felles ansvar å sørge for at møtearenaene vi deltar på er trygge, og at alle ønskes velkommen.', 'As part of STA, we have <strong>zero tolerance for harassment and discrimination</strong>. We care for each other and show mutual respect. It is our shared duty to make all arenas safe and welcoming.')); ?>
            </p>
        </div>

        <!-- 6 Principles -->
        <div>
            <h2 style="font-size:1.5rem; font-weight:700; color:#fff; margin-bottom:1.5rem;">
                <?php echo esc_html(sta_t('De 6 prinsippene for Trygg Organisasjon', 'The 6 Principles for a Safe Organization')); ?>
            </h2>
            <div style="display:flex; flex-direction:column; gap:1rem;">
                <?php foreach ($principles as $idx => $principle): ?>
                    <div class="sta-card" style="padding:1.25rem 1.5rem; display:flex; align-items:flex-start; gap:1.25rem;">
                        <span style="width:2rem; height:2rem; border-radius:50%; background:var(--sta-orange); color:#fff; font-weight:700; font-size:0.85rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:0.15rem;">
                            <?php echo $idx + 1; ?>
                        </span>
                        <p style="font-size:0.95rem; color:var(--sta-teal-100); line-height:1.65; margin:0;">
                            <?php echo esc_html($principle); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Whistleblower procedures Card -->
        <div class="sta-card" style="padding:2rem; border-color:rgba(239,115,48,0.4); background:rgba(14,76,71,0.6);">
            <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.25rem;">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                <h2 style="font-size:1.35rem; font-weight:700; color:#fff; margin:0;">
                    <?php echo esc_html(sta_t('Varslingsrutiner', 'Whistleblowing Procedures')); ?>
                </h2>
            </div>

            <p style="font-size:0.95rem; color:var(--sta-teal-100); line-height:1.7; margin-bottom:0.75rem;">
                <?php echo esc_html(sta_t('Et antatt brudd på prinsippene kan meldes inn til alle medlemmene i STA-styret og STAs organisasjonskonsulent. Det er leder av STA-styret som følger dette opp, og involverer STAs Kontrollkomite ved behov.', 'Any suspected breach of these principles can be reported to any member of the STA Board or STAs Organizational Consultant. The Student President follows up and involves STAs Control Committee when needed.')); ?>
            </p>
            <p style="font-size:0.95rem; color:var(--sta-teal-100); line-height:1.7; margin-bottom:0.75rem;">
                <strong><?php echo esc_html(sta_t('Innmelding av brudd på prinsipper skal ikke medføre negative konsekvenser for innmelder.', 'Reporting a breach will not entail negative repercussions for the reporting person.')); ?></strong>
            </p>
            <p style="font-size:0.95rem; color:var(--sta-teal-100); line-height:1.7; margin-bottom:1.5rem;">
                <?php echo wp_kses_post(sta_t('Om varslingen gjelder ett eller flere medlemmer av STA-styret, skal denne sendes til STAs organisasjonskonsulent på <a href="mailto:sta@uia.no" style="color:var(--sta-orange); text-decoration:underline; font-weight:600;">sta@uia.no</a>. STAs organisasjonskonsulent sitter i en egen varslingsgruppe sammen med Advokatfirma Tofte AS, som sammen vil behandle varslingen objektivt.', 'If the report concerns members of the STA Board, it should be sent directly to STAs Organizational Consultant at <a href="mailto:sta@uia.no" style="color:var(--sta-orange); text-decoration:underline; font-weight:600;">sta@uia.no</a>. The consultant cooperates with Law Firm Tofte AS to evaluate reports objectively.')); ?>
            </p>

            <div style="display:flex; flex-wrap:wrap; gap:1rem; padding-top:0.5rem;">
                <a href="mailto:sta@uia.no?subject=<?php echo rawurlencode(sta_t('Konfidensiell varsling om brudd på retningslinjer', 'Confidential whistleblowing report')); ?>" 
                   class="sta-btn sta-btn-orange" 
                   style="display:inline-flex; align-items:center; gap:0.5rem; font-weight:700;">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <span><?php echo esc_html(sta_t('Send konfidensielt varsel', 'Send confidential report')); ?></span>
                </a>
                <a href="https://www.uia.no/om-uia/kontakt/si-ifra/" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="sta-btn sta-btn-outline" 
                   style="display:inline-flex; align-items:center; gap:0.5rem;">
                    <span><?php echo esc_html(sta_t('UiAs Si ifra-portal', 'UiA Speak Up Portal')); ?></span>
                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
