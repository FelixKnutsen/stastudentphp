<?php
/**
 * Template Name: Trygg Organisasjon (Safe Organization)
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

$principles_nn = array(
    'Alle skal kjenne seg velkomne og respekterte i STA. Dette tyder at ein skal halde seg unna utsegner og/eller fysisk åtferd som blir opplevd som ubehageleg, diskriminerande, trakasserande eller krenkjande for andre.',
    'Alle har eit ansvar for å bidra til eit inkluderande miljø, både ved formelle og uformelle arrangement.',
    'Alle skal vise respekt for grensene til kvarandre.',
    'Press rundt bruk av alkohol og andre rusmiddel skal ikkje førekome. Ved alle arrangement i regi av ein studentaktivitet eller linjeforeining skal ein ha 1–2 tillitspersonar.',
    'Alle har eit ansvar for å hindre mobbing. STA har nulltoleranse for mobbing.',
    'Alle skal vise respekt for ytringane til kvarandre og møte kvarandre på ein konstruktiv måte.'
);

$principles = $is_en ? $principles_en : ($is_nn ? $principles_nn : $principles_no);
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <header class="sta-page-header">
        <span class="sta-badge"><?php echo esc_html(sta_t('Retningslinjer', 'Guidelines', 'Retningslinjer')); ?></span>
        <h1 class="sta-hero-title">
            <?php echo esc_html(sta_t('Trygg Organisasjon', 'Safe Organization', 'Trygg organisasjon')); ?>
        </h1>
        <p class="sta-hero-subtitle" style="max-width:800px; margin-left:auto; margin-right:auto;">
            <?php echo esc_html(sta_t('Det skal være gøy, lærerikt og motiverende å engasjere seg i STA.', 'Engaging in STA should be enjoyable, educational, and inspiring.', 'Det skal vere moro, lærerikt og motiverande å engasjere seg i STA.')); ?>
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
                        <?php echo esc_html(sta_t('Nulltoleranse for trakassering', 'Zero Tolerance for Harassment', 'Nulltoleranse for trakassering')); ?>
                    </h2>
                    <p style="font-size:0.75rem; color:var(--sta-orange); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin:0.25rem 0 0 0;">
                        <?php echo esc_html(sta_t('Vedtatt av Studentparlamentet', 'Adopted by the Student Parliament', 'Vedteke av Studentparlamentet')); ?>
                    </p>
                </div>
            </div>

            <p style="font-size:1rem; color:var(--sta-teal-100); line-height:1.7; margin:0;">
                <?php echo esc_html(sta_t(
                    'Studentorganisasjonen i Agder har nulltoleranse for alle former for mobbing, seksuell trakassering, rasisme og diskriminering. Alle arrangementer, møter og sosiale sammenkomster i regi av STA eller våre medlemsforeninger skal være trygge arenaer for alle studenter uavhengig av bakgrunn, kjønn eller legning.',
                    'The Student Organisation of Agder has zero tolerance for all forms of bullying, sexual harassment, racism, and discrimination. All events, meetings, and gatherings organized by STA or our member associations must be safe and welcoming spaces for all students.',
                    'Studentorganisasjonen i Agder har nulltoleranse for alle former for mobbing, seksuell trakassering, rasisme og diskriminering. Alle arrangement, møte og sosiale samankomstar i regi av STA eller medlemsforeiningane våre skal vere trygge arenaer for alle studentar uavhengig av bakgrunn, kjønn eller legning.'
                )); ?>
            </p>
        </div>

        <!-- 6 Core Principles Grid -->
        <div>
            <h2 style="font-size:1.5rem; font-weight:700; color:#fff; margin-bottom:1.5rem;">
                <?php echo esc_html(sta_t('Prinsipper for trygg organisasjon', 'Principles for a Safe Organization', 'Prinsipp for ein trygg organisasjon')); ?>
            </h2>

            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.25rem;">
                <?php foreach ($principles as $idx => $principle): ?>
                    <div class="sta-card" style="padding:1.5rem; display:flex; gap:1rem; align-items:flex-start;">
                        <span style="width:2rem; height:2rem; border-radius:50%; background:rgba(239,115,48,0.2); color:var(--sta-orange); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:0.85rem; flex-shrink:0;">
                            <?php echo $idx + 1; ?>
                        </span>
                        <p style="font-size:0.9rem; color:var(--sta-teal-100); line-height:1.6; margin:0;">
                            <?php echo esc_html($principle); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Help & Contact Box -->
        <div style="padding:2rem; border-radius:1rem; background:rgba(14,76,71,0.6); border:1px solid rgba(239,115,48,0.4);">
            <h3 style="font-size:1.25rem; font-weight:700; color:#fff; margin:0 0 0.75rem 0;">
                <?php echo esc_html(sta_t('Har du opplevd noe ubehagelig?', 'Have you experienced something uncomfortable?', 'Har du opplevd noko ubehageleg?')); ?>
            </h3>
            <p style="font-size:0.95rem; color:var(--sta-teal-100); line-height:1.7; margin:0 0 1.5rem 0;">
                <?php echo esc_html(sta_t(
                    'Dersom du har opplevd eller vært vitne til kritikkverdige forhold i tilknytning til STA eller studentforeninger, kan du trygt ta kontakt med oss eller benytte UiAs offisielle Si ifra-system.',
                    'If you have experienced or witnessed unacceptable behavior related to STA or student associations, you can contact us in full confidence or utilize UiAs official Speak Up system.',
                    'Dersom du har opplevd eller vore vitne til kritikkverdige tilhøve knytt til STA eller studentforeiningar, kan du trygt ta kontakt med oss eller nytte UiA sitt offisielle Sei ifrå-system.'
                )); ?>
            </p>
            <div style="display:flex; flex-wrap:wrap; gap:1rem;">
                <a href="mailto:sta@uia.no?subject=<?php echo rawurlencode(sta_t('Trygg organisasjon henvendelse', 'Safe organization inquiry', 'Trygg organisasjon førespurnad')); ?>" 
                   class="sta-btn sta-btn-orange" 
                   style="font-size:0.9rem; padding:0.65rem 1.25rem;">
                    <?php echo esc_html(sta_t('Kontakt STA på sta@uia.no', 'Contact STA at sta@uia.no', 'Kontakt STA på sta@uia.no')); ?>
                </a>
                <a href="https://www.uia.no/om-uia/kontakt/si-ifra/" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="sta-btn sta-btn-outline" 
                   style="font-size:0.9rem; padding:0.65rem 1.25rem;">
                    <span><?php echo esc_html(sta_t('UiAs Si ifra-system', 'UiA Speak Up System', 'UiA sitt Sei ifrå-system')); ?></span>
                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
