<?php
/**
 * Admin Settings for STA Organization (Partners, Contact Info, Venues)
 */

if (!defined('ABSPATH')) {
    exit;
}

function sta_register_admin_menu() {
    add_menu_page(
        __('STA Innstillinger', 'sta-core'),
        __('STA Innstillinger', 'sta-core'),
        'manage_options',
        'sta-settings',
        'sta_render_admin_settings_page',
        'dashicons-admin-generic',
        30
    );
}
add_action('admin_menu', 'sta_register_admin_menu');

function sta_register_settings() {
    register_setting('sta_options_group', 'sta_contact_info', array(
        'type' => 'array',
        'sanitize_callback' => 'sta_sanitize_contact_info'
    ));

    register_setting('sta_options_group', 'sta_partner_logos', array(
        'type' => 'array',
        'sanitize_callback' => 'sta_sanitize_partner_logos'
    ));
}
add_action('admin_init', 'sta_register_settings');

function sta_sanitize_contact_info($input) {
    $clean = array();
    $clean['address_krs'] = isset($input['address_krs']) ? sanitize_text_field($input['address_krs']) : '';
    $clean['address_grim'] = isset($input['address_grim']) ? sanitize_text_field($input['address_grim']) : '';
    $clean['org_number']  = isset($input['org_number']) ? sanitize_text_field($input['org_number']) : '';
    $clean['phone']       = isset($input['phone']) ? sanitize_text_field($input['phone']) : '';
    $clean['email']       = isset($input['email']) ? sanitize_email($input['email']) : '';
    $clean['facebook']    = isset($input['facebook']) ? esc_url_raw($input['facebook']) : '';
    $clean['instagram']   = isset($input['instagram']) ? esc_url_raw($input['instagram']) : '';
    $clean['linkedin']    = isset($input['linkedin']) ? esc_url_raw($input['linkedin']) : '';
    $clean['twitter']     = isset($input['twitter']) ? esc_url_raw($input['twitter']) : '';
    return $clean;
}

function sta_sanitize_partner_logos($input) {
    if (!is_array($input)) {
        return array();
    }
    $clean = array();
    foreach ($input as $key => $partner) {
        $clean[$key] = array(
            'name'     => sanitize_text_field($partner['name'] ?? ''),
            'name_en'  => sanitize_text_field($partner['name_en'] ?? ''),
            'url'      => esc_url_raw($partner['url'] ?? ''),
            'logo_url' => esc_url_raw($partner['logo_url'] ?? ''),
            'alt'      => sanitize_text_field($partner['alt'] ?? '')
        );
    }
    return $clean;
}

function sta_render_admin_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
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
        'twitter'      => 'https://twitter.com/stastudent',
    ));

    $partners = get_option('sta_partner_logos', array(
        'nso' => array(
            'name'     => 'Norsk studentorganisasjon (NSO)',
            'name_en'  => 'The National union of students in Norway (NSO)',
            'url'      => 'https://student.no',
            'logo_url' => 'https://stastudent.no/wp-content/uploads/2024/05/NSO-logo.png',
            'alt'      => 'NSO logo'
        ),
        'uia' => array(
            'name'     => 'Universitetet i Agder (UiA)',
            'name_en'  => 'University of Agder (UiA)',
            'url'      => 'https://www.uia.no',
            'logo_url' => 'https://stastudent.no/wp-content/uploads/2020/11/uia-horisontal-med-navn-Positiv.png',
            'alt'      => 'UiA logo'
        ),
        'sia' => array(
            'name'     => 'Studentsamskipnaden i Agder (SiA)',
            'name_en'  => 'Student Welfare Organization (SiA)',
            'url'      => 'https://sia.no',
            'logo_url' => 'https://stastudent.no/wp-content/uploads/2020/11/sialogo.png',
            'alt'      => 'SiA logo'
        ),
        'saih' => array(
            'name'     => "Studentenes og Akademikernes Internasjonale Hjelpefond (SAIH)",
            'name_en'  => "Students' and Academics' International Assistance Fund (SAIH)",
            'url'      => 'https://www.saih.no/no',
            'logo_url' => 'https://stastudent.no/wp-content/uploads/2024/05/SAIH-logo-PNG-1-1024x1024.png',
            'alt'      => 'SAIH logo'
        )
    ));
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('STA Hovedinnstillinger', 'sta-core'); ?></h1>
        <p><?php esc_html_e('Her administrerer du organisasjonsinformasjon, kontaktpunkter og samarbeidspartnere som vises på nettsiden.', 'sta-core'); ?></p>

        <form method="post" action="options.php">
            <?php
            settings_fields('sta_options_group');
            do_settings_sections('sta_options_group');
            ?>

            <h2><?php esc_html_e('1. Kontaktinformasjon', 'sta-core'); ?></h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="sta_address_krs"><?php esc_html_e('Adresse Kristiansand', 'sta-core'); ?></label></th>
                    <td><input type="text" id="sta_address_krs" name="sta_contact_info[address_krs]" value="<?php echo esc_attr($contact['address_krs'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sta_address_grim"><?php esc_html_e('Adresse Grimstad', 'sta-core'); ?></label></th>
                    <td><input type="text" id="sta_address_grim" name="sta_contact_info[address_grim]" value="<?php echo esc_attr($contact['address_grim'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sta_org_num"><?php esc_html_e('Organisasjonsnummer', 'sta-core'); ?></label></th>
                    <td><input type="text" id="sta_org_num" name="sta_contact_info[org_number]" value="<?php echo esc_attr($contact['org_number'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sta_phone"><?php esc_html_e('Telefon', 'sta-core'); ?></label></th>
                    <td><input type="text" id="sta_phone" name="sta_contact_info[phone]" value="<?php echo esc_attr($contact['phone'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sta_email"><?php esc_html_e('Offisiell e-post', 'sta-core'); ?></label></th>
                    <td><input type="email" id="sta_email" name="sta_contact_info[email]" value="<?php echo esc_attr($contact['email'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sta_fb"><?php esc_html_e('Facebook URL', 'sta-core'); ?></label></th>
                    <td><input type="url" id="sta_fb" name="sta_contact_info[facebook]" value="<?php echo esc_attr($contact['facebook'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sta_ig"><?php esc_html_e('Instagram URL', 'sta-core'); ?></label></th>
                    <td><input type="url" id="sta_ig" name="sta_contact_info[instagram]" value="<?php echo esc_attr($contact['instagram'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sta_li"><?php esc_html_e('LinkedIn URL', 'sta-core'); ?></label></th>
                    <td><input type="url" id="sta_li" name="sta_contact_info[linkedin]" value="<?php echo esc_attr($contact['linkedin'] ?? ''); ?>" class="regular-text"></td>
                </tr>
            </table>

            <hr style="margin:24px 0;">

            <h2><?php esc_html_e('2. Samarbeidspartnere (Logoer og lenker)', 'sta-core'); ?></h2>
            <p><?php esc_html_e('Administrer de 4 offisielle samarbeidspartnerne som vises i bunnseksjonen på forsiden.', 'sta-core'); ?></p>

            <?php foreach ($partners as $slug => $partner): ?>
                <div style="background:#fff; border:1px solid #ccd0d4; padding:16px; margin-bottom:16px; max-width:800px; border-radius:4px;">
                    <h3 style="margin-top:0;"><?php echo esc_html($partner['name']); ?></h3>
                    <table class="form-table" style="margin-top:0;">
                        <tr>
                            <th scope="row"><?php esc_html_e('Navn (norsk)', 'sta-core'); ?></th>
                            <td><input type="text" name="sta_partner_logos[<?php echo esc_attr($slug); ?>][name]" value="<?php echo esc_attr($partner['name']); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Name (English)', 'sta-core'); ?></th>
                            <td><input type="text" name="sta_partner_logos[<?php echo esc_attr($slug); ?>][name_en]" value="<?php echo esc_attr($partner['name_en']); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Nettside URL', 'sta-core'); ?></th>
                            <td><input type="url" name="sta_partner_logos[<?php echo esc_attr($slug); ?>][url]" value="<?php echo esc_attr($partner['url']); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Logo bilde-URL', 'sta-core'); ?></th>
                            <td>
                                <input type="url" name="sta_partner_logos[<?php echo esc_attr($slug); ?>][logo_url]" value="<?php echo esc_attr($partner['logo_url']); ?>" class="regular-text">
                                <?php if (!empty($partner['logo_url'])): ?>
                                    <div style="margin-top:8px; background:#f0f0f1; display:inline-block; padding:8px; border-radius:4px;">
                                        <img src="<?php echo esc_url($partner['logo_url']); ?>" alt="<?php echo esc_attr($partner['alt']); ?>" style="max-height:40px; display:block;">
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php endforeach; ?>

            <?php submit_button(__('Lagre alle innstillinger', 'sta-core')); ?>
        </form>
    </div>
    <?php
}
