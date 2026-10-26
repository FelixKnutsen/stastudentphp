<?php
/**
 * Footer template for STA Theme
 */
if (!defined('ABSPATH')) {
    exit;
}

$contact = get_option('sta_contact_info', array(
    'address_krs'  => 'Universitetsveien 24, 4630 Kristiansand S',
    'address_grim' => 'Jon Lilletuns vei 9, 4879 Grimstad',
    'org_number'   => '981 596 412',
    'phone'        => '+47 38 14 20 10',
    'email'        => 'sta@uia.no',
    'facebook'     => 'https://www.facebook.com/stastudent/',
    'instagram'    => 'https://www.instagram.com/stagder/',
    'linkedin'     => 'https://www.linkedin.com/company/studentorganisasjonen-i-agder',
));

$is_en = sta_is_en();
$is_nn = sta_is_nn();
?>
</main><!-- #content -->

<footer class="sta-footer" role="contentinfo" style="background:#031816; border-top:1px solid rgba(14,76,71,0.8); color:#fff; padding:3.5rem 0 2rem 0; margin-top:auto;">
    <div class="sta-container">
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:2.5rem; margin-bottom:3rem;">
            <!-- Brand & Mission Column -->
            <div style="grid-column: span 1;">
                <div style="margin-bottom:1.25rem;">
                    <a href="<?php echo esc_url(sta_get_localized_url('/')); ?>" aria-label="<?php echo esc_attr(sta_t('Studentorganisasjonen i Agder - Til forsiden', 'Student Organisation of Agder - Home', 'Studentorganisasjonen i Agder - Til framsida')); ?>" style="display:inline-block; text-decoration:none;">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/sta-logo.svg'); ?>" 
                             alt="<?php echo esc_attr(sta_t('Studentorganisasjonen i Agder (STA)', 'Student Organisation of Agder (STA)', 'Studentorganisasjonen i Agder (STA)')); ?>" 
                             height="44" 
                             width="213" 
                             style="height:44px; width:auto; display:block; transition:transform 0.2s ease;"
                             loading="lazy">
                    </a>
                </div>
                <p style="font-size:0.875rem; color:var(--sta-teal-100); line-height:1.6; margin-bottom:1.25rem;">
                    <?php echo esc_html(sta_t('Studentorganisasjonen i Agder (STA) representerer alle studentene ved Universitetet i Agder. Vi jobber for studentenes faglige, sosiale og økonomiske interesser i Kristiansand og Grimstad.', 'The Student Organisation of Agder (STA) represents all students at the University of Agder. We work for students\' academic, social, and economic interests in Kristiansand and Grimstad.', 'Studentorganisasjonen i Agder (STA) representerer alle studentane ved Universitetet i Agder. Vi jobbar for dei faglege, sosiale og økonomiske interessene til studentane i Kristiansand og Grimstad.')); ?>
                </p>
                <!-- Social Links -->
                <div style="display:flex; flex-wrap:wrap; align-items:center; gap:0.5rem 1rem; font-size:0.85rem; white-space:nowrap;">
                    <a href="<?php echo esc_url($contact['facebook']); ?>" target="_blank" rel="noopener noreferrer" style="color:var(--sta-teal-200); text-decoration:none; transition:color 0.2s;">
                        Facebook
                    </a>
                    <span aria-hidden="true" style="color:var(--sta-teal-700);">&bull;</span>
                    <a href="<?php echo esc_url($contact['instagram']); ?>" target="_blank" rel="noopener noreferrer" style="color:var(--sta-teal-200); text-decoration:none; transition:color 0.2s;">
                        Instagram
                    </a>
                    <span aria-hidden="true" style="color:var(--sta-teal-700);">&bull;</span>
                    <a href="<?php echo esc_url($contact['linkedin']); ?>" target="_blank" rel="noopener noreferrer" style="color:var(--sta-teal-200); text-decoration:none; transition:color 0.2s;">
                        LinkedIn
                    </a>
                </div>
            </div>

            <!-- Col 1: Organisation -->
            <div class="sta-footer-group">
                <h4 style="font-size:0.85rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:var(--sta-teal-200); margin-bottom:0.75rem;">
                    <?php echo esc_html(sta_t('Organisasjon', 'Organisation', 'Organisasjon')); ?>
                </h4>
                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:0.5rem; font-size:0.875rem;">
                    <li><a href="<?php echo esc_url(sta_get_localized_url('/sta-styret')); ?>" style="color:var(--sta-teal-100); text-decoration:none;"><?php echo esc_html(sta_t('STA-Styret', 'The STA-board', 'STA-styret')); ?></a></li>
                    <li><a href="<?php echo esc_url(sta_get_localized_url('/studentparlamentet')); ?>" style="color:var(--sta-teal-100); text-decoration:none;"><?php echo esc_html(sta_t('Studentparlamentet', 'The Student Parliament', 'Studentparlamentet')); ?></a></li>
                    <li><a href="<?php echo esc_url(sta_get_localized_url('/ledige-verv')); ?>" style="color:var(--sta-teal-100); text-decoration:none;"><?php echo esc_html(sta_t('Ledige verv', 'Vacant positions', 'Ledige verv')); ?></a></li>
                    <li><a href="<?php echo esc_url(sta_get_localized_url('/dokumenter')); ?>" style="color:var(--sta-teal-100); text-decoration:none;"><?php echo esc_html(sta_t('Dokumenter', 'Documents', 'Dokument')); ?></a></li>
                    <li><a href="<?php echo esc_url(sta_get_localized_url('/rad-styrer-og-utvalg')); ?>" style="color:var(--sta-teal-100); text-decoration:none;"><?php echo esc_html(sta_t('Råd, styrer og utvalg', 'Boards and committees', 'Råd, styre og utval')); ?></a></li>
                </ul>
            </div>

            <!-- Col 2: Resources -->
            <div class="sta-footer-group">
                <h4 style="font-size:0.85rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:var(--sta-teal-200); margin-bottom:0.75rem;">
                    <?php echo esc_html(sta_t('Ressurser', 'Resources', 'Ressursar')); ?>
                </h4>
                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:0.5rem; font-size:0.875rem;">
                    <li><a href="<?php echo esc_url(sta_get_localized_url('/for-tillitsvalgte')); ?>" style="color:var(--sta-teal-100); text-decoration:none;"><?php echo esc_html(sta_t('For tillitsvalgte', 'Student representatives', 'For tillitsvalde')); ?></a></li>
                    <li><a href="<?php echo esc_url(sta_get_localized_url('/linjeforeninger')); ?>" style="color:var(--sta-teal-100); text-decoration:none;"><?php echo esc_html(sta_t('Linjeforeninger', 'Academic associations', 'Linjeforeiningar')); ?></a></li>
                    <li><a href="<?php echo esc_url(sta_get_localized_url('/studentaktiviteter')); ?>" style="color:var(--sta-teal-100); text-decoration:none;"><?php echo esc_html(sta_t('Studentaktiviteter', 'Student Activities', 'Studentaktivitetar')); ?></a></li>
                    <li><a href="<?php echo esc_url(sta_get_localized_url('/lokaler')); ?>" style="color:var(--sta-teal-100); text-decoration:none;"><?php echo esc_html(sta_t('Lokaler (Alibiet / Bluebox)', 'Our premises', 'Lokale (Alibiet / Bluebox)')); ?></a></li>
                    <li><a href="<?php echo esc_url(sta_get_localized_url('/irene-bredals-hederspris')); ?>" style="color:var(--sta-teal-100); text-decoration:none;"><?php echo esc_html(sta_t('Irene Bredals Hederspris', 'Irene Bredals Honour Award', 'Irene Bredals Heiderspris')); ?></a></li>
                </ul>
            </div>

            <!-- Col 3: Guidelines -->
            <div class="sta-footer-group">
                <h4 style="font-size:0.85rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:var(--sta-teal-200); margin-bottom:0.75rem;">
                    <?php echo esc_html(sta_t('Retningslinjer', 'Guidelines', 'Retningslinjer')); ?>
                </h4>
                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:0.5rem; font-size:0.875rem;">
                    <li><a href="<?php echo esc_url(sta_get_localized_url('/personvernserklaering')); ?>" style="color:var(--sta-teal-100); text-decoration:none;"><?php echo esc_html(sta_t('Personvernserklæring', 'Privacy policy', 'Personvernerklæring')); ?></a></li>
                    <li><a href="<?php echo esc_url(sta_get_localized_url('/trygg-organisasjon')); ?>" style="color:var(--sta-teal-100); text-decoration:none;"><?php echo esc_html(sta_t('Trygg Organisasjon', 'A Safe Organization', 'Trygg organisasjon')); ?></a></li>
                    <li><a href="https://uiakarriereuka.no" target="_blank" rel="noopener noreferrer" style="color:var(--sta-teal-100); text-decoration:none; display:inline-flex; align-items:center; gap:0.25rem;">
                        <span><?php echo esc_html(sta_t('UiA Karriereuka', 'UiA Career Week', 'UiA Karriereveka')); ?></span>
                        <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    </a></li>
                </ul>
            </div>
        </div>

        <!-- Contact Info Block -->
        <div style="padding-top:2rem; border-top:1px solid rgba(14,76,71,0.8); display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:1.5rem; font-size:0.8rem; color:var(--sta-teal-200); margin-bottom:2rem;">
            <div style="display:flex; align-items:flex-start; gap:0.5rem;">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:0.15rem;" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <div>
                    <p style="font-weight:700; color:#fff; margin:0 0 0.15rem 0;"><?php echo esc_html(sta_t('Adresse Kristiansand:', 'Address Kristiansand:', 'Adresse Kristiansand:')); ?></p>
                    <p style="margin:0;"><?php echo esc_html($contact['address_krs']); ?></p>
                </div>
            </div>
            <div style="display:flex; align-items:flex-start; gap:0.5rem;">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:0.15rem;" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <div>
                    <p style="font-weight:700; color:#fff; margin:0 0 0.15rem 0;"><?php echo esc_html(sta_t('Adresse Grimstad:', 'Address Grimstad:', 'Adresse Grimstad:')); ?></p>
                    <p style="margin:0;"><?php echo esc_html($contact['address_grim']); ?></p>
                </div>
            </div>
            <div style="display:flex; align-items:flex-start; gap:0.5rem;">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="var(--sta-orange)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:0.15rem;" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                <div>
                    <p style="font-weight:700; color:#fff; margin:0 0 0.15rem 0;"><?php echo esc_html(sta_t('Kontakt oss:', 'Contact us:', 'Kontakt oss:')); ?></p>
                    <p style="margin:0;">
                        <a href="mailto:<?php echo esc_attr($contact['email']); ?>" style="color:var(--sta-teal-200); text-decoration:underline;"><?php echo esc_html($contact['email']); ?></a>
                        &nbsp;|&nbsp;
                        <span><?php echo esc_html($contact['phone']); ?></span>
                    </p>
                    <p style="margin:0.25rem 0 0 0; font-size:0.75rem; color:var(--sta-teal-200);">
                        Org.nr: <?php echo esc_html($contact['org_number']); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Bottom Copyright & Links -->
        <div style="padding-top:1.5rem; border-top:1px solid rgba(14,76,71,0.5); display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:1rem; font-size:0.75rem; color:var(--sta-teal-200);">
            <div>
                &copy; <?php echo date('Y'); ?> Studentorganisasjonen i Agder (STA). <?php echo esc_html(sta_t('Alle rettigheter forbeholdt.', 'All rights reserved.', 'Alle rettar reserverte.')); ?>
            </div>
            <div style="display:flex; align-items:center; gap:1.25rem;">
                <a href="<?php echo esc_url(sta_get_localized_url('/personvernserklaering')); ?>" style="color:var(--sta-teal-200); text-decoration:none;">
                    <?php echo esc_html(sta_t('Personvernserklæring', 'Privacy Policy', 'Personvernerklæring')); ?>
                </a>
                <a href="<?php echo esc_url(sta_get_localized_url('/trygg-organisasjon')); ?>" style="color:var(--sta-teal-200); text-decoration:none;">
                    <?php echo esc_html(sta_t('Trygg Organisasjon', 'Safe Organization', 'Trygg organisasjon')); ?>
                </a>
                <a href="#main-content" style="color:var(--sta-orange-text); text-decoration:none; font-weight:600;">
                    <?php echo esc_html(sta_t('Til toppen &uarr;', 'Back to top &uarr;', 'Til toppen &uarr;')); ?>
                </a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
