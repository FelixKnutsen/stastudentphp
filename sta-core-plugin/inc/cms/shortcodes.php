<?php
/**
 * Small shortcodes for STA content inside page text.
 *   [sta_contact field="email|phone|org_number|address_krs|address_grim" link="1"]
 * Values come from STA Innstillinger → Kontaktinformasjon (also used in the footer).
 */

if (!defined('ABSPATH')) {
    exit;
}

/** Contact info from STA Innstillinger, with the same defaults as the footer. */
function sta_cms_contact_info() {
    return array_merge(array(
        'address_krs'  => 'Universitetsveien 24, 4630 Kristiansand S',
        'address_grim' => 'Jon Lilletuns vei 9, 4879 Grimstad',
        'org_number'   => '981 596 412',
        'phone'        => '+47 38 14 20 10',
        'email'        => 'sta@uia.no',
    ), array_filter((array) get_option('sta_contact_info', array())));
}

add_shortcode('sta_contact', function ($atts) {
    $atts  = shortcode_atts(array('field' => 'email', 'link' => '0'), $atts, 'sta_contact');
    $info  = sta_cms_contact_info();
    $field = sanitize_key($atts['field']);
    $value = (string) ($info[$field] ?? '');
    if ($value === '') {
        return '';
    }
    if ($atts['link'] === '1' && $field === 'email') {
        return '<a href="mailto:' . esc_attr(sanitize_email($value)) . '">' . esc_html($value) . '</a>';
    }
    if ($atts['link'] === '1' && $field === 'phone') {
        return '<a href="tel:' . esc_attr(preg_replace('/[^0-9+]/', '', $value)) . '">' . esc_html($value) . '</a>';
    }
    return esc_html($value);
});
