<?php
/**
 * Custom Meta Boxes for STA Content Types
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Meta Boxes
 */
function sta_register_meta_boxes() {
    // 1. Association details
    add_meta_box(
        'sta_association_details',
        __('Linjeforening detaljer og sosiale lenker', 'sta-core'),
        'sta_render_association_meta_box',
        'sta_association',
        'normal',
        'high'
    );

    // 2. Activity details
    add_meta_box(
        'sta_activity_details',
        __('Studentaktivitet detaljer og sosiale lenker', 'sta-core'),
        'sta_render_activity_meta_box',
        'sta_activity',
        'normal',
        'high'
    );

    // 3. Board member details
    add_meta_box(
        'sta_board_details',
        __('Styremedlem informasjon og kontakt', 'sta-core'),
        'sta_render_board_meta_box',
        'sta_board_member',
        'normal',
        'high'
    );

    // 4. Parliament representative details
    add_meta_box(
        'sta_parliament_details',
        __('Representasjon og status', 'sta-core'),
        'sta_render_parliament_meta_box',
        'sta_parliament_rep',
        'normal',
        'high'
    );

    // 5. Position & Vacancy details
    add_meta_box(
        'sta_position_details',
        __('Verv detaljer, ledig status og offisielle lenker', 'sta-core'),
        'sta_render_position_meta_box',
        'sta_position',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sta_register_meta_boxes');

/**
 * Helper to render an input field
 */
function sta_render_field($label, $name, $value, $type = 'text', $help = '') {
    echo '<p style="margin-bottom:12px;">';
    echo '<label style="display:block;font-weight:600;margin-bottom:4px;" for="' . esc_attr($name) . '">' . esc_html($label) . '</label>';
    if ($type === 'textarea') {
        echo '<textarea style="width:100%;min-height:80px;" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '">' . esc_textarea($value) . '</textarea>';
    } elseif ($type === 'checkbox') {
        echo '<label><input type="checkbox" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" value="1" ' . checked($value, '1', false) . '> ' . esc_html($help) . '</label>';
        return;
    } else {
        echo '<input style="width:100%;max-width:600px;" type="' . esc_attr($type) . '" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" value="' . esc_attr($value) . '">';
    }
    if ($help) {
        echo '<span style="display:block;color:#666;font-size:12px;margin-top:2px;">' . esc_html($help) . '</span>';
    }
    echo '</p>';
}

/**
 * 1. Render Association Meta Box
 */
function sta_render_association_meta_box($post) {
    wp_nonce_field('sta_save_meta_box_data', 'sta_meta_box_nonce');

    $desc_no    = get_post_meta($post->ID, '_sta_desc_no', true);
    $desc_en    = get_post_meta($post->ID, '_sta_desc_en', true);
    $logo_url   = get_post_meta($post->ID, '_sta_logo_url', true);
    $email      = get_post_meta($post->ID, '_sta_email', true);
    $website    = get_post_meta($post->ID, '_sta_website', true);
    $instagram  = get_post_meta($post->ID, '_sta_instagram', true);
    $facebook   = get_post_meta($post->ID, '_sta_facebook', true);
    $discord    = get_post_meta($post->ID, '_sta_discord', true);

    sta_render_field('Beskrivelse (norsk)', '_sta_desc_no', $desc_no, 'textarea');
    sta_render_field('Description (English)', '_sta_desc_en', $desc_en, 'textarea');
    sta_render_field('Logo bilde-URL (eller bruk uthevet bilde)', '_sta_logo_url', $logo_url, 'url');
    sta_render_field('Kontakt e-post', '_sta_email', $email, 'email');
    sta_render_field('Nettside URL', '_sta_website', $website, 'url');
    sta_render_field('Instagram URL', '_sta_instagram', $instagram, 'url');
    sta_render_field('Facebook URL', '_sta_facebook', $facebook, 'url');
    sta_render_field('Discord URL', '_sta_discord', $discord, 'url');
}

/**
 * 2. Render Activity Meta Box
 */
function sta_render_activity_meta_box($post) {
    wp_nonce_field('sta_save_meta_box_data', 'sta_meta_box_nonce');

    $desc_no    = get_post_meta($post->ID, '_sta_desc_no', true);
    $desc_en    = get_post_meta($post->ID, '_sta_desc_en', true);
    $image_url  = get_post_meta($post->ID, '_sta_image_url', true);
    $email      = get_post_meta($post->ID, '_sta_email', true);
    $website    = get_post_meta($post->ID, '_sta_website', true);
    $instagram  = get_post_meta($post->ID, '_sta_instagram', true);
    $facebook   = get_post_meta($post->ID, '_sta_facebook', true);
    $discord    = get_post_meta($post->ID, '_sta_discord', true);

    sta_render_field('Beskrivelse (norsk)', '_sta_desc_no', $desc_no, 'textarea');
    sta_render_field('Description (English)', '_sta_desc_en', $desc_en, 'textarea');
    sta_render_field('Bilde/Logo URL (eller bruk uthevet bilde)', '_sta_image_url', $image_url, 'url');
    sta_render_field('Kontakt e-post', '_sta_email', $email, 'email');
    sta_render_field('Nettside URL', '_sta_website', $website, 'url');
    sta_render_field('Instagram URL', '_sta_instagram', $instagram, 'url');
    sta_render_field('Facebook URL', '_sta_facebook', $facebook, 'url');
    sta_render_field('Discord URL', '_sta_discord', $discord, 'url');
}

/**
 * 3. Render Board Member Meta Box
 */
function sta_render_board_meta_box($post) {
    wp_nonce_field('sta_save_meta_box_data', 'sta_meta_box_nonce');

    $role_no          = get_post_meta($post->ID, '_sta_role_no', true);
    $role_en          = get_post_meta($post->ID, '_sta_role_en', true);
    $email            = get_post_meta($post->ID, '_sta_email', true);
    $phone            = get_post_meta($post->ID, '_sta_phone', true);
    $campus           = get_post_meta($post->ID, '_sta_campus', true);
    $image_url        = get_post_meta($post->ID, '_sta_image_url', true);
    $resp_no          = get_post_meta($post->ID, '_sta_responsibilities_no', true);
    $resp_en          = get_post_meta($post->ID, '_sta_responsibilities_en', true);

    sta_render_field('Rolle/Tittel (norsk)', '_sta_role_no', $role_no, 'text', 'F.eks. Studentleder, Nestleder');
    sta_render_field('Role/Title (English)', '_sta_role_en', $role_en, 'text', 'E.g. Student President, Deputy President');
    sta_render_field('E-post', '_sta_email', $email, 'email');
    sta_render_field('Telefon', '_sta_phone', $phone, 'text');
    sta_render_field('Campus', '_sta_campus', $campus, 'text', 'F.eks. Felles, Kristiansand, Grimstad');
    sta_render_field('Portrettbilde URL (eller bruk uthevet bilde)', '_sta_image_url', $image_url, 'url');
    sta_render_field('Hovedansvar (norsk)', '_sta_responsibilities_no', $resp_no, 'textarea');
    sta_render_field('Responsibilities (English)', '_sta_responsibilities_en', $resp_en, 'textarea');
}

/**
 * 4. Render Parliament Representative Meta Box
 */
function sta_render_parliament_meta_box($post) {
    wp_nonce_field('sta_save_meta_box_data', 'sta_meta_box_nonce');

    $rep_type  = get_post_meta($post->ID, '_sta_rep_type', true);
    $is_vacant = get_post_meta($post->ID, '_sta_is_vacant', true);

    echo '<p style="margin-bottom:12px;">';
    echo '<label style="display:block;font-weight:600;margin-bottom:4px;" for="_sta_rep_type">Representanttype</label>';
    echo '<select name="_sta_rep_type" id="_sta_rep_type" style="width:100%;max-width:300px;">';
    echo '<option value="representative" ' . selected($rep_type, 'representative', false) . '>Fast representant</option>';
    echo '<option value="substitute" ' . selected($rep_type, 'substitute', false) . '>Vararepresentant</option>';
    echo '</select>';
    echo '</p>';

    sta_render_field('Er vervet ledig?', '_sta_is_vacant', $is_vacant, 'checkbox', 'Kryss av dersom plassen er ledig / ubesatt');
}

/**
 * 5. Render Position Meta Box
 */
function sta_render_position_meta_box($post) {
    wp_nonce_field('sta_save_meta_box_data', 'sta_meta_box_nonce');

    $title_en       = get_post_meta($post->ID, '_sta_title_en', true);
    $desc_no        = get_post_meta($post->ID, '_sta_desc_no', true);
    $desc_en        = get_post_meta($post->ID, '_sta_desc_en', true);
    $is_vacant      = get_post_meta($post->ID, '_sta_is_vacant', true);
    $positions_no   = get_post_meta($post->ID, '_sta_positions_no', true);
    $positions_en   = get_post_meta($post->ID, '_sta_positions_en', true);
    $info_url       = get_post_meta($post->ID, '_sta_info_url', true);
    $info_label_no  = get_post_meta($post->ID, '_sta_info_label_no', true);
    $info_label_en  = get_post_meta($post->ID, '_sta_info_label_en', true);
    $eligibility_no = get_post_meta($post->ID, '_sta_eligibility_no', true);
    $eligibility_en = get_post_meta($post->ID, '_sta_eligibility_en', true);

    sta_render_field('Er dette vervet ledig akkurat nå?', '_sta_is_vacant', $is_vacant, 'checkbox', 'Kryss av for å vise vervet under "Ledige verv"');
    sta_render_field('Tittel på engelsk', '_sta_title_en', $title_en, 'text');
    sta_render_field('Beskrivelse (norsk)', '_sta_desc_no', $desc_no, 'textarea');
    sta_render_field('Description (English)', '_sta_desc_en', $desc_en, 'textarea');
    sta_render_field('Antall plasser / roller (norsk)', '_sta_positions_no', $positions_no, 'text', 'F.eks. "1., 2. og 3. vararepresentant"');
    sta_render_field('Positions count / roles (English)', '_sta_positions_en', $positions_en, 'text');
    sta_render_field('Offisiell UiA-side eller informasjonslenke', '_sta_info_url', $info_url, 'url', 'F.eks. https://www.uia.no/om-uia/styrer-og-utvalg/...');
    sta_render_field('Lenketekst (norsk)', '_sta_info_label_no', $info_label_no, 'text');
    sta_render_field('Link text (English)', '_sta_info_label_en', $info_label_en, 'text');
    sta_render_field('Hvem kan stille / Valgbarhet (norsk)', '_sta_eligibility_no', $eligibility_no, 'text');
    sta_render_field('Eligibility (English)', '_sta_eligibility_en', $eligibility_en, 'text');
}

/**
 * Save Meta Box Data
 */
function sta_save_meta_box_data($post_id) {
    if (!isset($_POST['sta_meta_box_nonce']) || !wp_verify_nonce($_POST['sta_meta_box_nonce'], 'sta_save_meta_box_data')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // List of text fields to sanitize and save
    $text_fields = array(
        '_sta_role_no', '_sta_role_en', '_sta_phone', '_sta_campus',
        '_sta_title_en', '_sta_positions_no', '_sta_positions_en',
        '_sta_info_label_no', '_sta_info_label_en',
        '_sta_eligibility_no', '_sta_eligibility_en', '_sta_rep_type'
    );

    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }

    // List of URL fields
    $url_fields = array('_sta_logo_url', '_sta_image_url', '_sta_website', '_sta_instagram', '_sta_facebook', '_sta_discord', '_sta_info_url');
    foreach ($url_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, esc_url_raw($_POST[$field]));
        }
    }

    // List of email fields
    if (isset($_POST['_sta_email'])) {
        update_post_meta($post_id, '_sta_email', sanitize_email($_POST['_sta_email']));
    }

    // List of textarea fields
    $textarea_fields = array('_sta_desc_no', '_sta_desc_en', '_sta_responsibilities_no', '_sta_responsibilities_en');
    foreach ($textarea_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_textarea_field($_POST[$field]));
        }
    }

    // Checkbox fields
    $is_vacant = isset($_POST['_sta_is_vacant']) ? '1' : '0';
    update_post_meta($post_id, '_sta_is_vacant', $is_vacant);
}
add_action('save_post', 'sta_save_meta_box_data');
