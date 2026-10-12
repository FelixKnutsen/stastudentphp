<?php
/**
 * Template Name: Personvernserklæring (Privacy Policy)
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
$is_en = sta_is_en();
?>

<div class="sta-container" style="padding-top:3.5rem; padding-bottom:5rem;">
    <article style="max-width:840px; margin:0 auto;">
        <header style="margin-bottom:2.5rem;">
            <span class="sta-badge"><?php echo esc_html(sta_t('Personvern og GDPR', 'Privacy & GDPR')); ?></span>
            <h1 class="sta-hero-title" style="font-size:2.75rem; margin-top:0.5rem;">
                <?php echo esc_html(sta_t('Personvernserklæring', 'Privacy Policy')); ?>
            </h1>
        </header>

        <div class="sta-article-content" style="font-size:1.1rem; line-height:1.8; color:var(--sta-teal-50);">
            <h2>1. <?php echo esc_html(sta_t('Behandlingsansvarlig', 'Data Controller')); ?></h2>
            <p>
                <?php echo esc_html(sta_t('Studentorganisasjonen i Agder (STA), organisasjonsnummer 981 596 412, Universitetsveien 24, 4630 Kristiansand S, er behandlingsansvarlig for behandling av personopplysninger på stastudent.no.', 'The Student Organisation of Agder (STA), organisation number 981 596 412, Universitetsveien 24, 4630 Kristiansand S, is the data controller for personal data processed on stastudent.no.')); ?>
            </p>

            <h2>2. <?php echo esc_html(sta_t('Webstatistikk og informasjonskapsler (Koko Analytics)', 'Web Analytics & Cookies')); ?></h2>
            <p>
                <?php echo esc_html(sta_t('Vi benytter Koko Analytics for anonymisert besøksstatistikk. Koko Analytics sporer ikke personlig identifiserbar informasjon, deler ikke data med tredjeparter og bruker ikke invaderende sporingskapsler.', 'We use Koko Analytics for privacy-friendly visitor statistics. Koko Analytics does not track personally identifiable information, does not share data with third parties, and avoids tracking cookies.')); ?>
            </p>

            <h2>3. <?php echo esc_html(sta_t('Sikkerhet og logger', 'Security & Logs')); ?></h2>
            <p>
                <?php echo esc_html(sta_t('Nettstedet benytter sikkerhetslogger (Solid Security) for å forhindre uautorisert adgang og angrep. IP-adresser i sikkerhetslogger oppbevares i inntil 60 dager før de slettes automatisk.', 'The website employs security logging to prevent unauthorized access and cyber attacks. IP addresses in security logs are retained for up to 60 days before automatic deletion.')); ?>
            </p>

            <h2>4. <?php echo esc_html(sta_t('Dine rettigheter og kontakt', 'Your Rights & Contact')); ?></h2>
            <p>
                <?php echo esc_html(sta_t('Du har rett til innsyn, retting og sletting av personopplysninger vi eventuelt oppbevarer om deg. Henvendelser rettes til sta@uia.no.', 'You have the right to request access to, rectification of, or erasure of any personal data we hold about you. Contact sta@uia.no for any inquiries.')); ?>
            </p>
        </div>
    </article>
</div>

<?php
get_footer();
