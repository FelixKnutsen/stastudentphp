<?php
/**
 * Edit form for STA content: renders the fields from schema.php, validates required fields and
 * saves them (sanitised) as post meta. Also stores the person ↔ group/committee relationship.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('add_meta_boxes', function ($post_type) {
    $types = sta_cms_types();
    if (!isset($types[$post_type])) {
        return;
    }
    $def = $types[$post_type];
    add_meta_box('sta_cms_fields', 'Innhold', 'sta_cms_render_fields_box', $post_type, 'normal', 'high');
    add_meta_box('sta_cms_where', 'Hvor vises dette?', 'sta_cms_render_where_box', $post_type, 'side', 'high');
    if ($post_type === 'sta_board_member') {
        add_meta_box('sta_cms_memberships', 'Medlem av', 'sta_cms_render_memberships_box', $post_type, 'side', 'default');
    }
    // Plain-language labels for core boxes.
    remove_meta_box('pageparentdiv', $post_type, 'side');
    add_meta_box('sta_cms_order', 'Rekkefølge', 'sta_cms_render_order_box', $post_type, 'side', 'default');
}, 20);

/** Title placeholder per type («Fullt navn» instead of «Legg til tittel»). */
add_filter('enter_title_here', function ($text, $post) {
    $types = sta_cms_types();
    return isset($types[$post->post_type]) ? $types[$post->post_type]['title_label'] : $text;
}, 10, 2);

function sta_cms_render_order_box($post) {
    printf(
        '<p><label for="sta_menu_order">Lavere tall vises først.</label></p><input type="number" id="sta_menu_order" name="menu_order" value="%d" style="width:100px;"><p class="description">Du kan også dra og slippe i listen.</p>',
        (int) $post->menu_order
    );
}

/** A page by path; falls back to the last part of the path (pages may sit at the top level). */
function sta_cms_find_page($path) {
    $page = get_page_by_path($path);
    if (!$page && strpos($path, '/') !== false) {
        $page = get_page_by_path(basename($path));
    }
    return $page;
}

function sta_cms_page_links($post_type) {
    $types = sta_cms_types();
    $links = array();
    foreach ($types[$post_type]['pages'] ?? array() as $path) {
        $page  = $path === '' ? null : sta_cms_find_page($path);
        $title = $path === '' ? 'Forsiden' : ($page ? get_the_title($page) : $path);
        $url   = $path === '' ? home_url('/') : ($page ? get_permalink($page) : home_url('/' . $path . '/'));
        $links[] = '<a href="' . esc_url($url) . '" target="_blank" rel="noopener">' . esc_html($title) . ' ↗</a>';
    }
    return $links;
}

function sta_cms_render_where_box($post) {
    $types = sta_cms_types();
    $def   = $types[$post->post_type];
    echo '<p>' . esc_html($def['intro']) . '</p>';
    $links = sta_cms_page_links($post->post_type);
    if ($links) {
        echo '<p><strong>Side:</strong> ' . implode(', ', $links) . '</p>'; // Escaped in sta_cms_page_links().
    }
    echo '<p class="description">Endringer vises på nettsiden med en gang du trykker «Publiser» eller «Oppdater». Utkast vises ikke.</p>';
}

function sta_cms_render_memberships_box($post) {
    $groups = get_posts(array(
        'post_type'      => array('sta_group', 'sta_position'),
        'post_status'    => array('publish', 'draft', 'pending', 'private'),
        'posts_per_page' => -1,
        'meta_key'       => '_sta_member_ids',
        'meta_value'     => (string) $post->ID,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ));
    if (!$groups) {
        echo '<p>Ikke med i noen gruppe ennå. Personen vises ikke på nettsiden før hen legges til i en gruppe.</p>';
    } else {
        echo '<ul style="margin:0;">';
        foreach ($groups as $group) {
            $role = '';
            foreach ((array) get_post_meta($group->ID, '_sta_members', true) as $row) {
                if ((int) ($row['person'] ?? 0) === $post->ID) {
                    $role = $row['role_no'] ?? '';
                }
            }
            printf(
                '<li><a href="%s">%s</a>%s%s</li>',
                esc_url(get_edit_post_link($group->ID)),
                esc_html(get_the_title($group)),
                $role !== '' ? ' – ' . esc_html($role) : '',
                $group->post_status !== 'publish' ? ' <em>(utkast, vises ikke)</em>' : ''
            );
        }
        echo '</ul>';
    }
    printf('<p><a href="%s">Gå til Grupper</a></p>', esc_url(admin_url('edit.php?post_type=sta_group')));
}

function sta_cms_render_fields_box($post) {
    $types = sta_cms_types();
    $def   = $types[$post->post_type];
    wp_nonce_field('sta_cms_save_' . $post->post_type, 'sta_cms_nonce');
    echo '<p class="sta-cms-required-note"><span class="sta-cms-req">*</span> = må fylles ut før du kan publisere.';
    $required = array();
    foreach ($def['required_taxonomies'] ?? array() as $tax) {
        $required[] = ucfirst(sta_cms_taxonomies()[$tax]['singular']);
    }
    if (!empty($def['required_thumbnail'])) {
        $required[] = $def['thumbnail'];
        echo '<input type="hidden" id="sta-cms-thumb-required" data-label="' . esc_attr($def['thumbnail']) . '">';
    }
    if ($required) {
        echo ' Velg også: ' . esc_html(implode(', ', $required)) . ' (i boksene til høyre).';
    }
    echo '</p>';
    echo '<table class="form-table sta-cms-form" role="presentation"><tbody>';
    foreach ($def['fields'] as $field) {
        sta_cms_render_field($post, $field);
    }
    echo '</tbody></table>';
}

function sta_cms_render_field($post, $field) {
    $key   = $field['key'];
    $type  = $field['type'];
    $value = get_post_meta($post->ID, $key, true);
    $req   = !empty($field['required']);
    $id    = 'sta_f' . $key;
    $attrs = $req ? ' data-required="1" data-label="' . esc_attr($field['label']) . '"' : '';
    echo '<tr class="sta-cms-row sta-cms-type-' . esc_attr($type) . '"' . $attrs . '>';
    echo '<th scope="row"><label for="' . esc_attr($id) . '">' . esc_html($field['label']) . ($req ? ' <span class="sta-cms-req">*</span>' : '') . '</label></th><td>';
    switch ($type) {
        case 'textarea':
            printf('<textarea id="%1$s" name="%2$s" rows="4" class="large-text">%3$s</textarea>', esc_attr($id), esc_attr($key), esc_textarea($value));
            break;
        case 'editor':
            wp_editor((string) $value, $id, array(
                'textarea_name' => $key,
                'textarea_rows' => 8,
                'media_buttons' => false,
                'teeny'         => true,
                'quicktags'     => false,
            ));
            break;
        case 'checkbox':
            printf('<label><input type="checkbox" id="%1$s" name="%2$s" value="1" %3$s> %4$s</label>', esc_attr($id), esc_attr($key), checked($value, '1', false), esc_html($field['help'] ?? ''));
            echo '</td></tr>';
            return;
        case 'select':
            printf('<select id="%1$s" name="%2$s">', esc_attr($id), esc_attr($key));
            foreach ($field['options'] as $opt => $label) {
                printf('<option value="%s" %s>%s</option>', esc_attr($opt), selected((string) $value, (string) $opt, false), esc_html($label));
            }
            echo '</select>';
            break;
        case 'image_url':
            printf(
                '<div class="sta-cms-media" data-kind="image"><input type="url" id="%1$s" name="%2$s" value="%3$s" class="regular-text sta-cms-media-url"> <button type="button" class="button sta-cms-media-pick">Velg bilde</button> <button type="button" class="button-link sta-cms-media-clear">Fjern</button><div class="sta-cms-media-preview">%4$s</div></div>',
                esc_attr($id), esc_attr($key), esc_attr($value),
                $value ? '<img src="' . esc_url($value) . '" alt="">' : ''
            );
            break;
        case 'file':
            $file_id  = (int) get_post_meta($post->ID, $key . '_id', true);
            $file_url = (string) get_post_meta($post->ID, $key . '_url', true);
            printf(
                '<div class="sta-cms-media" data-kind="file"><input type="hidden" name="%1$s_id" value="%2$d" class="sta-cms-media-id"><input type="url" id="%3$s" name="%1$s_url" value="%4$s" class="large-text sta-cms-media-url" placeholder="https://"> <p><button type="button" class="button sta-cms-media-pick">Velg fil</button> <button type="button" class="button-link sta-cms-media-clear">Fjern</button></p><div class="sta-cms-media-preview">%5$s</div></div>',
                esc_attr($key), $file_id, esc_attr($id), esc_attr($file_url),
                $file_url ? '<a href="' . esc_url($file_url) . '" target="_blank" rel="noopener">' . esc_html(wp_basename($file_url)) . ' ↗</a>' : ''
            );
            break;
        case 'members':
            sta_cms_render_members($post, $field);
            break;
        default:
            $html_type = in_array($type, array('email', 'tel', 'url', 'number', 'date'), true) ? $type : 'text';
            printf(
                '<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" class="%5$s"%6$s>',
                esc_attr($html_type), esc_attr($id), esc_attr($key), esc_attr($value),
                $html_type === 'number' ? 'small-text' : 'regular-text',
                $html_type === 'number' ? ' min="0" step="1"' : ''
            );
    }
    if (!empty($field['help'])) {
        echo '<p class="description">' . esc_html($field['help']) . '</p>';
    }
    echo '</td></tr>';
}

function sta_cms_render_members($post, $field) {
    $rows    = get_post_meta($post->ID, $field['key'], true);
    $rows    = is_array($rows) ? $rows : array();
    $people  = get_posts(array(
        'post_type'      => 'sta_board_member',
        'post_status'    => array('publish', 'draft', 'pending', 'private'),
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ));
    $options = function ($selected) use ($people) {
        $html = '<option value="">— Velg person —</option>';
        foreach ($people as $person) {
            $html .= sprintf(
                '<option value="%d" %s>%s%s</option>',
                $person->ID,
                selected((int) $selected, $person->ID, false),
                esc_html(get_the_title($person)),
                $person->post_status !== 'publish' ? ' (utkast – vises ikke)' : ''
            );
        }
        return $html;
    };
    $row_html = function ($row) use ($options) {
        return '<tr class="sta-cms-member">'
            . '<td class="sta-cms-handle" title="Dra for å endre rekkefølgen">☰</td>'
            . '<td><select name="sta_members[person][]">' . $options($row['person'] ?? 0) . '</select></td>'
            . '<td><input type="text" name="sta_members[role_no][]" value="' . esc_attr($row['role_no'] ?? '') . '" placeholder="Rolle i gruppen (valgfritt)"></td>'
            . '<td><input type="text" name="sta_members[role_en][]" value="' . esc_attr($row['role_en'] ?? '') . '" placeholder="Rolle på engelsk (valgfritt)"></td>'
            . '<td><button type="button" class="button-link sta-cms-member-remove" aria-label="Fjern medlem">Fjern</button></td>'
            . '</tr>';
    };
    echo '<input type="hidden" name="sta_members_present" value="1">';
    echo '<table class="widefat striped sta-cms-members"><thead><tr><th></th><th>Person</th><th>Rolle</th><th>Rolle (engelsk)</th><th></th></tr></thead><tbody>';
    foreach ($rows as $row) {
        echo $row_html($row); // Escaped in $row_html.
    }
    echo '</tbody></table>';
    echo '<script type="text/template" class="sta-cms-member-template">' . $row_html(array()) . '</script>';
    printf(
        '<p><button type="button" class="button sta-cms-member-add">+ Legg til medlem</button> <a href="%s" target="_blank" rel="noopener">Opprett ny person ↗</a> <span class="description">(lagre gruppen og last siden på nytt etterpå for å se den nye personen i listen)</span></p>',
        esc_url(admin_url('post-new.php?post_type=sta_board_member'))
    );
}

/**
 * Required fields: if something required is missing when an editor publishes, the entry is
 * saved as a draft instead (nothing half-finished reaches the website) and a message lists
 * what is missing. The edit form also checks this in the browser before submitting.
 */
add_filter('wp_insert_post_data', function ($data, $postarr) {
    $type = $data['post_type'];
    $types = sta_cms_types();
    if (!isset($types[$type]) || empty($_POST['sta_cms_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['sta_cms_nonce'])), 'sta_cms_save_' . $type)) {
        return $data;
    }
    if (!in_array($data['post_status'], array('publish', 'future'), true)) {
        return $data;
    }
    $missing = sta_cms_missing_required($type, $data, $_POST); // phpcs:ignore WordPress.Security.NonceVerification -- verified above.
    if ($missing) {
        $data['post_status'] = 'draft';
        set_transient('sta_cms_missing_' . get_current_user_id(), $missing, 120);
    }
    return $data;
}, 10, 2);

function sta_cms_missing_required($type, $data, $input) {
    $def = sta_cms_types()[$type];
    $missing = array();
    if (trim((string) $data['post_title']) === '') {
        $missing[] = $def['title_label'];
    }
    foreach ($def['fields'] as $field) {
        if (empty($field['required'])) {
            continue;
        }
        $key = $field['key'];
        $val = $field['type'] === 'file' ? ($input[$key . '_url'] ?? '') : ($input[$key] ?? '');
        if (trim((string) wp_unslash($val)) === '') {
            $missing[] = $field['label'];
        }
    }
    foreach ($def['required_taxonomies'] ?? array() as $tax) {
        $ids = array_filter(array_map('intval', (array) ($input['tax_input'][$tax] ?? array())));
        if (!$ids) {
            $missing[] = ucfirst(sta_cms_taxonomies()[$tax]['singular']);
        }
    }
    if (!empty($def['required_thumbnail']) && (int) ($input['_thumbnail_id'] ?? 0) <= 0) {
        $missing[] = $def['thumbnail'];
    }
    return $missing;
}

add_action('admin_notices', function () {
    $missing = get_transient('sta_cms_missing_' . get_current_user_id());
    if (!$missing) {
        return;
    }
    delete_transient('sta_cms_missing_' . get_current_user_id());
    printf(
        '<div class="notice notice-error"><p><strong>Ikke publisert – lagret som utkast.</strong> Fyll ut: %s. Trykk deretter «Publiser» igjen.</p></div>',
        esc_html(implode(', ', $missing))
    );
});

/** Saves the fields (sanitised per type). Runs only for the STA edit form, with a valid nonce. */
add_action('save_post', function ($post_id, $post) {
    $types = sta_cms_types();
    if (!isset($types[$post->post_type]) || wp_is_post_revision($post_id) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)) {
        return;
    }
    if (empty($_POST['sta_cms_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['sta_cms_nonce'])), 'sta_cms_save_' . $post->post_type)) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    foreach ($types[$post->post_type]['fields'] as $field) {
        sta_cms_save_field($post_id, $field);
    }
}, 10, 2);

/** Stores the member rows of a group/committee plus a reverse index (person → groups). */
function sta_cms_store_members($post_id, $rows) {
    update_post_meta($post_id, '_sta_members', array_values($rows));
    delete_post_meta($post_id, '_sta_member_ids');
    foreach (array_unique(wp_list_pluck($rows, 'person')) as $person) {
        add_post_meta($post_id, '_sta_member_ids', (string) $person);
    }
}

function sta_cms_save_field($post_id, $field) {
    $key = $field['key'];
    // phpcs:disable WordPress.Security.NonceVerification -- nonce verified in the save_post callback.
    switch ($field['type']) {
        case 'checkbox':
            update_post_meta($post_id, $key, isset($_POST[$key]) ? '1' : '0');
            return;
        case 'members':
            if (empty($_POST['sta_members_present'])) {
                return;
            }
            $in    = isset($_POST['sta_members']) && is_array($_POST['sta_members']) ? wp_unslash($_POST['sta_members']) : array();
            $ids   = array_map('intval', (array) ($in['person'] ?? array()));
            $rows  = array();
            foreach ($ids as $i => $person) {
                if ($person <= 0 || get_post_type($person) !== 'sta_board_member') {
                    continue;
                }
                $rows[] = array(
                    'person'  => $person,
                    'role_no' => sanitize_text_field($in['role_no'][$i] ?? ''),
                    'role_en' => sanitize_text_field($in['role_en'][$i] ?? ''),
                );
            }
            sta_cms_store_members($post_id, $rows);
            return;
        case 'file':
            $url = isset($_POST[$key . '_url']) ? esc_url_raw(wp_unslash($_POST[$key . '_url'])) : '';
            $id  = isset($_POST[$key . '_id']) ? absint($_POST[$key . '_id']) : 0;
            if ($id && wp_get_attachment_url($id) !== $url) {
                $id = attachment_url_to_postid($url);
            }
            update_post_meta($post_id, $key . '_url', $url);
            update_post_meta($post_id, $key . '_id', $id);
            return;
    }
    if (!isset($_POST[$key])) {
        return;
    }
    $raw = wp_unslash($_POST[$key]);
    // phpcs:enable
    switch ($field['type']) {
        case 'textarea':
            $value = sanitize_textarea_field($raw);
            break;
        case 'editor':
            $value = wp_kses_post($raw);
            break;
        case 'email':
            $value = sanitize_email($raw);
            break;
        case 'url':
        case 'image_url':
            $value = esc_url_raw(trim($raw));
            break;
        case 'number':
            $value = $raw === '' ? '' : (string) absint($raw);
            break;
        case 'date':
            $value = preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw) ? $raw : '';
            break;
        case 'select':
            $value = array_key_exists($raw, $field['options']) ? $raw : '';
            break;
        default:
            $value = sanitize_text_field($raw);
    }
    update_post_meta($post_id, $key, $value);
}
