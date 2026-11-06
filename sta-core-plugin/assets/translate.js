/* Oversettelser screen: finds the text on a page and lets editors fill in Nynorsk and English. */
(function () {
    'use strict';

    var decoder = document.createElement('textarea');
    function key(s) {
        decoder.innerHTML = s;
        return decoder.value.replace(/\s+/g, ' ').trim();
    }

    /* Same split as the frontend: text between tags inside <main>, readable attributes, page title. */
    function collect(html) {
        var start = html.indexOf('<main'), end = html.lastIndexOf('</main>');
        if (start < 0 || end < 0) {
            return [];
        }
        var main = html.slice(start, end).replace(/<script[\s\S]*?<\/script>|<style[\s\S]*?<\/style>/gi, '');
        var out = [], m, re = />([^<]+)</g, ra = /\s(?:alt|title|aria-label|placeholder)="([^"]*)"/g;
        while ((m = re.exec(main))) { out.push(key(m[1])); }
        while ((m = ra.exec(main))) { out.push(key(m[1])); }
        var t = html.match(/<title>([^<]*)<\/title>/i);
        if (t) { out.push(key(t[1])); }
        return out.filter(function (s) {
            return /[A-Za-zÆØÅæøå]{2}/.test(s) && !/^(https?:|www\.)|@/.test(s) && !/(\.\.\.|…)$/.test(s);
        });
    }

    function post(action, data) {
        var body = new URLSearchParams();
        body.set('action', action);
        body.set('nonce', staTr.nonce);
        Object.keys(data).forEach(function (k) { body.set(k, data[k]); });
        return fetch(staTr.ajax, { method: 'POST', credentials: 'include', body: body }).then(function (r) { return r.json(); });
    }

    function read(url) {
        return fetch(url, { credentials: 'include' }).then(function (r) {
            if (!r.ok) {
                throw new Error('siden svarte med feil ' + r.status);
            }
            return r.text();
        }).then(collect);
    }

    /* Reads the page in all three languages; Bokmål last so the language cookie ends up on Bokmål. */
    async function lookup(data) {
        var nn = await read(data.urlNn);
        var en = await read(data.urlEn);
        var no = await read(data.url);
        return post('sta_tr_lookup', {
            page_id: data.page,
            texts: JSON.stringify(no),
            same_nn: JSON.stringify(no.filter(function (t) { return nn.indexOf(t) !== -1; })),
            same_en: JSON.stringify(no.filter(function (t) { return en.indexOf(t) !== -1; }))
        });
    }

    /* ---- overview ---- */
    var scan = document.getElementById('sta-tr-scan');
    if (scan) {
        scan.addEventListener('click', async function () {
            var rows = document.querySelectorAll('.sta-tr-overview tbody tr');
            var status = document.getElementById('sta-tr-scan-status');
            scan.disabled = true;
            for (var i = 0; i < rows.length; i++) {
                status.textContent = 'Sjekker side ' + (i + 1) + ' av ' + rows.length + ' …';
                try {
                    var r = await lookup(rows[i].dataset);
                    if (r.success) {
                        rows[i].querySelector('.sta-tr-nn').textContent = r.data.label.nn;
                        rows[i].querySelector('.sta-tr-en').textContent = r.data.label.en;
                    }
                } catch (e) {
                    rows[i].querySelector('.sta-tr-nn').textContent = 'Kunne ikke lese siden';
                }
            }
            status.textContent = 'Ferdig.';
            scan.disabled = false;
        });
    }

    /* ---- one page ---- */
    var root = document.getElementById('sta-tr-page');
    if (!root) {
        return;
    }
    var rows = [], dirty = {};

    function el(tag, cls, text) {
        var e = document.createElement(tag);
        if (cls) { e.className = cls; }
        if (text !== undefined) { e.textContent = text; }
        return e;
    }

    function cell(row, lang) {
        var td = el('td');
        var ta = el('textarea');
        ta.rows = Math.min(6, Math.max(2, Math.ceil(row.text.length / 50)));
        ta.value = row[lang];
        ta.setAttribute('aria-label', (lang === 'nn' ? 'Nynorsk' : 'Engelsk') + ' for: ' + row.text);
        ta.addEventListener('input', function () {
            dirty[row.text] = dirty[row.text] || { text: row.text };
            dirty[row.text][lang] = ta.value;
            updateSave();
        });
        td.appendChild(ta);
        var actions = el('div', 'sta-tr-actions');
        var same = el('button', 'button-link', 'Lik bokmål');
        same.type = 'button';
        same.addEventListener('click', function () {
            ta.value = row.text;
            ta.dispatchEvent(new Event('input'));
        });
        actions.appendChild(same);
        if (row[lang + '_outdated']) {
            td.insertBefore(el('p', 'sta-tr-note is-outdated', 'Teksten er endret. Tidligere bokmål: «' + row[lang + '_outdated'] + '». Sjekk at oversettelsen stemmer.'), ta);
            var ok = el('button', 'button-link', 'Fortsatt riktig');
            ok.type = 'button';
            ok.addEventListener('click', function () {
                dirty[row.text] = dirty[row.text] || { text: row.text };
                dirty[row.text][lang + '_ok'] = 1;
                ok.textContent = 'Merket – husk å lagre';
                updateSave();
            });
            actions.appendChild(document.createTextNode(' · '));
            actions.appendChild(ok);
        }
        td.appendChild(actions);
        return td;
    }

    function needsWork(row) {
        return !row.managed && (row.nn_missing || row.en_missing || row.nn_outdated || row.en_outdated);
    }

    var saveBtns = [], onlyMissing = true, tbody, msg;

    function updateSave() {
        var n = Object.keys(dirty).length;
        saveBtns.forEach(function (b) {
            b.disabled = !n;
            b.textContent = n ? 'Lagre (' + n + ')' : 'Lagre';
        });
    }

    function render() {
        tbody.innerHTML = '';
        var shown = 0;
        rows.forEach(function (row) {
            if (row.managed || (onlyMissing && !needsWork(row) && !dirty[row.text])) {
                return;
            }
            shown++;
            var tr = el('tr');
            tr.appendChild(el('td', 'sta-tr-text', row.text));
            tr.appendChild(cell(row, 'nn'));
            tr.appendChild(cell(row, 'en'));
            tbody.appendChild(tr);
        });
        if (!shown) {
            var tr = el('tr');
            var td = el('td', '', 'Alt på denne siden er oversatt.');
            td.colSpan = 3;
            tr.appendChild(td);
            tbody.appendChild(tr);
        }
    }

    function saveButton() {
        var b = el('button', 'button button-primary', 'Lagre');
        b.type = 'button';
        b.disabled = true;
        b.addEventListener('click', function () {
            var items = Object.keys(dirty).map(function (k) { return dirty[k]; });
            saveBtns.forEach(function (x) { x.disabled = true; x.textContent = 'Lagrer …'; });
            post('sta_tr_save', { items: JSON.stringify(items) }).then(function (r) {
                if (!r.success) {
                    throw new Error(r.data || 'ukjent feil');
                }
                items.forEach(function (item) {
                    rows.forEach(function (row) {
                        if (row.text !== item.text) { return; }
                        ['nn', 'en'].forEach(function (lang) {
                            if (item[lang] !== undefined) { row[lang] = item[lang]; row[lang + '_missing'] = !item[lang]; }
                            if (item[lang] !== undefined || item[lang + '_ok']) { row[lang + '_outdated'] = ''; }
                        });
                    });
                });
                dirty = {};
                msg.textContent = 'Lagret. Endringene vises nå på nettsiden.';
                msg.className = 'notice notice-success inline sta-tr-msg';
                render();
                updateSave();
            }).catch(function (e) {
                msg.textContent = 'Kunne ikke lagre: ' + e.message + '. Teksten du skrev er ikke borte; prøv igjen.';
                msg.className = 'notice notice-error inline sta-tr-msg';
                updateSave();
            });
        });
        saveBtns.push(b);
        return b;
    }

    root.textContent = 'Leser teksten på siden …';
    lookup(root.dataset).then(function (r) {
        if (!r.success) {
            throw new Error(r.data || 'ukjent feil');
        }
        rows = r.data.rows;
        root.textContent = '';
        msg = el('p', 'sta-tr-msg');
        root.appendChild(msg);
        var bar = el('p');
        var label = el('label');
        var check = el('input');
        check.type = 'checkbox';
        check.checked = true;
        check.addEventListener('change', function () { onlyMissing = check.checked; render(); });
        label.appendChild(check);
        label.appendChild(document.createTextNode(' Vis bare det som mangler eller må sjekkes'));
        bar.appendChild(saveButton());
        bar.appendChild(document.createTextNode('  '));
        bar.appendChild(label);
        root.appendChild(bar);
        var managed = rows.filter(function (row) { return row.managed; });
        if (managed.length) {
            root.appendChild(el('p', 'description', managed.length + ' tekster på siden kommer fra STA-innhold og oversettes der (de vises ikke her).'));
        }
        var table = el('table', 'widefat striped sta-tr-table');
        var thead = el('thead');
        var head = el('tr');
        ['Bokmål (fra siden)', 'Nynorsk', 'Engelsk'].forEach(function (t) { head.appendChild(el('th', '', t)); });
        thead.appendChild(head);
        table.appendChild(thead);
        tbody = el('tbody');
        table.appendChild(tbody);
        root.appendChild(table);
        var bottom = el('p');
        bottom.appendChild(saveButton());
        root.appendChild(bottom);
        render();
    }).catch(function (e) {
        root.textContent = 'Kunne ikke lese siden: ' + e.message;
    });

    window.addEventListener('beforeunload', function (e) {
        if (Object.keys(dirty).length) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
}());
