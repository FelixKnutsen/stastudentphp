<?php
/**
 * Nynorsk page content.
 *
 * TranslatePress (free) handles one extra language, English. For Nynorsk the theme keeps its
 * own dictionary (option sta_nn_translations: Bokmål text => Nynorsk text) and swaps the text
 * in the finished page when the visitor has chosen Nynorsk. Menus and footer are translated in
 * the templates with sta_t(). Edit the dictionary under Innstillinger → Nynorsk-tekst.
 */

if (!defined('ABSPATH')) {
    exit;
}

/** Normalised lookup key: entities decoded, whitespace collapsed. */
function sta_nn_key($text) {
    return trim(preg_replace('/\s+/u', ' ', html_entity_decode((string) $text, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
}

function sta_nn_dictionary() {
    $dict = get_option('sta_nn_translations', array());
    return is_array($dict) ? $dict : array();
}

/** Replace text nodes and readable attributes that have a Nynorsk translation. */
function sta_nn_translate_html($html) {
    $dict = array_filter(sta_nn_dictionary(), 'strlen');
    if (!$dict || $html === '') {
        return $html;
    }
    $lookup = function ($text) use ($dict) {
        $key = sta_nn_key($text);
        return ($key !== '' && isset($dict[$key])) ? $dict[$key] : null;
    };
    // Scripts, styles and form text stay untouched.
    $parts = preg_split('#(<script\b.*?</script>|<style\b.*?</style>|<textarea\b.*?</textarea>)#is', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
    foreach ($parts as $i => $part) {
        if ($i % 2 === 1) {
            continue;
        }
        $part = preg_replace_callback('#>([^<]+)<#u', function ($m) use ($lookup) {
            $nn = $lookup($m[1]);
            if ($nn === null) {
                return $m[0];
            }
            preg_match('/^\s*/u', $m[1], $lead);
            preg_match('/\s*$/u', $m[1], $trail);
            return '>' . $lead[0] . esc_html($nn) . $trail[0] . '<';
        }, $part);
        $part = preg_replace_callback('#\s(alt|title|aria-label|placeholder)="([^"]*)"#u', function ($m) use ($lookup) {
            $nn = $lookup($m[2]);
            return $nn === null ? $m[0] : ' ' . $m[1] . '="' . esc_attr($nn) . '"';
        }, $part);
        $part = preg_replace_callback('#(<meta\s+(?:name|property)="(?:description|og:title|og:description|twitter:title|twitter:description)"\s+content=")([^"]*)(")#u', function ($m) use ($lookup) {
            $nn = $lookup($m[2]);
            return $nn === null ? $m[0] : $m[1] . esc_attr($nn) . $m[3];
        }, $part);
        $parts[$i] = $part;
    }
    return implode('', $parts);
}

add_action('template_redirect', function () {
    if (is_admin() || wp_doing_ajax() || is_feed() || !sta_is_nn() || isset($_GET['elementor-preview'])) {
        return;
    }
    ob_start('sta_nn_translate_html');
}, 0);

/**
 * Admin-only endpoint for bulk edits.
 * POST /wp-json/sta/v1/nynorsk {"mode":"list"} → dictionary
 * POST /wp-json/sta/v1/nynorsk {"mode":"save","pairs":[{"original":"...","translated":"..."}]}
 */
add_action('rest_api_init', function () {
    register_rest_route('sta/v1', '/nynorsk', array(
        'methods'             => 'POST',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
        'callback'            => function (WP_REST_Request $request) {
            $dict = sta_nn_dictionary();
            if ($request->get_param('mode') === 'save') {
                $saved = 0;
                foreach ((array) $request->get_param('pairs') as $pair) {
                    $original = isset($pair['original']) ? sta_nn_key($pair['original']) : '';
                    if ($original === '') {
                        continue;
                    }
                    $dict[$original] = isset($pair['translated']) ? trim((string) $pair['translated']) : '';
                    $saved++;
                }
                update_option('sta_nn_translations', $dict, false);
                return array('saved' => $saved, 'total' => count($dict));
            }
            return array('total' => count($dict), 'rows' => $dict);
        },
    ));
});

/** Innstillinger → Nynorsk-tekst: list, search and edit the dictionary; scan pages for new text. */
add_action('admin_menu', function () {
    add_options_page('Nynorsk-tekst', 'Nynorsk-tekst', 'manage_options', 'sta-nynorsk', 'sta_nn_admin_page');
});

function sta_nn_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    $notice = '';
    if (isset($_POST['sta_nn_json']) && check_admin_referer('sta_nn_save')) {
        $data = json_decode(wp_unslash($_POST['sta_nn_json']), true);
        if (is_array($data)) {
            $clean = array();
            foreach ($data as $original => $translated) {
                $key = sta_nn_key($original);
                if ($key !== '') {
                    $clean[$key] = trim((string) $translated);
                }
            }
            update_option('sta_nn_translations', $clean, false);
            $notice = sprintf('Lagra %d tekstar. Tøm hurtigbufferen (Purge cache) for å sjå endringane.', count($clean));
        } else {
            $notice = 'Kunne ikkje lese dataene. Ingenting vart lagra.';
        }
    }
    $dict = sta_nn_dictionary();
    $pages = get_pages(array('post_status' => 'publish'));
    $page_urls = array();
    foreach ($pages as $page) {
        $page_urls[] = add_query_arg(array('page_id' => $page->ID, 'lang' => 'no'), home_url('/'));
    }
    ?>
    <div class="wrap">
        <h1>Nynorsk-tekst</h1>
        <?php if ($notice) : ?><div class="notice notice-success"><p><?php echo esc_html($notice); ?></p></div><?php endif; ?>
        <p>Sidene blir skrivne på bokmål i Elementor. Her står nynorsk-versjonen av kvar tekst. Tekst utan omsetjing blir vist på bokmål når nokon har valt nynorsk.</p>
        <p><button type="button" class="button" id="sta-nn-scan">Finn ny tekst på sidene</button>
            <label style="margin-left:1rem;"><input type="checkbox" id="sta-nn-only-missing"> Vis berre tekst utan omsetjing</label>
            <input type="search" id="sta-nn-search" placeholder="Søk" style="margin-left:1rem;">
            <span id="sta-nn-status" style="margin-left:1rem;"></span></p>
        <form method="post" id="sta-nn-form">
            <?php wp_nonce_field('sta_nn_save'); ?>
            <input type="hidden" name="sta_nn_json" id="sta-nn-json">
            <table class="widefat striped" id="sta-nn-table">
                <thead><tr><th style="width:48%">Bokmål (frå sida)</th><th>Nynorsk</th></tr></thead>
                <tbody></tbody>
            </table>
            <p><button type="submit" class="button button-primary">Lagre</button></p>
        </form>
        <h2>Eksport / import</h2>
        <p>Kopier teksten under for å ta vare på omsetjingane, eller lim inn ei tidlegare eksport og trykk Lagre.</p>
        <textarea id="sta-nn-export" rows="6" style="width:100%;font-family:monospace;"><?php echo esc_textarea(wp_json_encode($dict, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)); ?></textarea>
        <p><button type="button" class="button" id="sta-nn-import">Bruk teksten over i tabellen</button></p>
    </div>
    <script>
    (function () {
        var dict = <?php echo wp_json_encode((object) $dict, JSON_UNESCAPED_UNICODE); ?>;
        var pages = <?php echo wp_json_encode($page_urls); ?>;
        var tbody = document.querySelector('#sta-nn-table tbody');
        var decoder = document.createElement('textarea');
        function key(s) { decoder.innerHTML = s; return decoder.value.replace(/\s+/g, ' ').trim(); }
        function render() {
            var onlyMissing = document.getElementById('sta-nn-only-missing').checked;
            var q = document.getElementById('sta-nn-search').value.toLowerCase();
            tbody.innerHTML = '';
            Object.keys(dict).sort().forEach(function (k) {
                if (onlyMissing && dict[k]) return;
                if (q && (k + ' ' + dict[k]).toLowerCase().indexOf(q) === -1) return;
                var tr = document.createElement('tr');
                var td1 = document.createElement('td'); td1.textContent = k;
                var td2 = document.createElement('td');
                var ta = document.createElement('textarea'); ta.rows = 2; ta.style.width = '100%'; ta.value = dict[k] || '';
                ta.setAttribute('aria-label', 'Nynorsk for: ' + k);
                ta.addEventListener('input', function () { dict[k] = ta.value; });
                td2.appendChild(ta); tr.appendChild(td1); tr.appendChild(td2); tbody.appendChild(tr);
            });
        }
        // Same split as the frontend: text between tags inside <main>, plus readable attributes.
        function collect(html) {
            var start = html.indexOf('<main'), end = html.lastIndexOf('</main>');
            if (start < 0 || end < 0) return [];
            var main = html.slice(start, end).replace(/<script[\s\S]*?<\/script>|<style[\s\S]*?<\/style>/gi, '');
            var out = [], m, re = />([^<]+)</g, ra = /\s(?:alt|title|aria-label|placeholder)="([^"]*)"/g;
            while ((m = re.exec(main))) out.push(key(m[1]));
            while ((m = ra.exec(main))) out.push(key(m[1]));
            var t = html.match(/<title>([^<]*)<\/title>/i); if (t) out.push(key(t[1]));
            return out.filter(function (s) { return /[A-Za-zÆØÅæøå]{2}/.test(s) && !/^(https?:|www\.)|@/.test(s); });
        }
        document.getElementById('sta-nn-scan').addEventListener('click', async function () {
            var status = document.getElementById('sta-nn-status'), added = 0;
            for (var i = 0; i < pages.length; i++) {
                status.textContent = 'Les side ' + (i + 1) + ' av ' + pages.length + ' …';
                var html = await (await fetch(pages[i], { credentials: 'include' })).text();
                collect(html).forEach(function (s) { if (!(s in dict)) { dict[s] = ''; added++; } });
            }
            status.textContent = added + ' nye tekstar funne. Trykk Lagre for å ta vare på dei.';
            document.getElementById('sta-nn-only-missing').checked = true;
            render();
        });
        document.getElementById('sta-nn-only-missing').addEventListener('change', render);
        document.getElementById('sta-nn-search').addEventListener('input', render);
        document.getElementById('sta-nn-import').addEventListener('click', function () {
            try { dict = JSON.parse(document.getElementById('sta-nn-export').value); render(); }
            catch (e) { alert('Teksten er ikkje gyldig JSON.'); }
        });
        document.getElementById('sta-nn-form').addEventListener('submit', function () {
            document.getElementById('sta-nn-json').value = JSON.stringify(dict);
        });
        render();
    })();
    </script>
    <?php
}
