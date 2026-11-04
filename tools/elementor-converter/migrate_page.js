// Copies one page's Elementor content from staging to production (run in the production
// Elementor editor of that page). Staging and production data were read beforehand into the
// browser's IndexedDB 'sta_migration' (keys stg_<id> and prod_<id>) with get_document_config.
//   - staging URLs (/reliant/…) → live URLs
//   - VT logo: staging media 17702 (2026/10) → production media 17455 (2026/11)
//   - Sakspapirer/Protokoller: keep production's newer document file for links with the same text
//   - page template from staging; content saved with Publish (Elementor revision kept)
window.__c = null;
(async () => {
    for (let i = 0; i < 80 && !(window.elementor && elementor.elements && elementor.getPreviewContainer && elementor.getPreviewContainer()); i++) await new Promise((r) => setTimeout(r, 500));
    await new Promise((r) => setTimeout(r, 1500));
    const PID = elementor.config.document.id;
    const db = await new Promise((res, rej) => { const q = indexedDB.open('sta_migration', 1); q.onsuccess = () => res(q.result); q.onerror = rej; });
    const get = (k) => new Promise((res, rej) => { const r = db.transaction('docs').objectStore('docs').get(k); r.onsuccess = () => res(r.result); r.onerror = rej; });
    const stg = await get('stg_' + PID);
    const prod = await get('prod_' + PID);
    let s = JSON.stringify(stg.elements);
    s = s.replace(/https?:\/\/stastudent\.no\/reliant\//g, 'https://stastudent.no/');
    s = s.replace(/(\\"|")\/reliant\//g, '$1/');
    s = s.replace(/2026\/10\/vt-logo-to-linjer/g, '2026/11/vt-logo-to-linjer').replace(/"id":17702([,}])/g, '"id":17455$1').replace(/"id":"17702"/g, '"id":"17455"');
    let overrides = 0;
    if (PID === 6443 || PID === 6445) {
        const map = {};
        for (const m of JSON.stringify(prod.elements).matchAll(/href=\\"([^\\"]+)\\"[^>]*>([^<]*)</g)) {
            if (/\/wp-content\/uploads\//.test(m[1])) map[m[2].trim()] = m[1];
        }
        s = s.replace(/href=\\"([^\\"]+)\\"([^>]*)>([^<]*)</g, (all, h, rest, t) => {
            const p = map[t.trim()];
            if (p && p !== h && /\/wp-content\/uploads\//.test(h)) { overrides++; return 'href=\\"' + p + '\\"' + rest + '>' + t + '<'; }
            return all;
        });
    }
    const leftover = (s.match(/reliant/g) || []).length;
    const els = JSON.parse(s);
    const p = elementor.getPreviewContainer();
    $e.run('document/elements/delete', { containers: p.children.slice() });
    els.forEach((m, i) => $e.run('document/elements/create', { container: p, model: m, options: { at: i } }));
    if (stg.template && stg.template !== elementor.settings.page.model.get('template')) {
        $e.run('document/elements/settings', { container: elementor.settings.page.getEditedView().getContainer(), settings: { template: stg.template } });
        await new Promise((r) => setTimeout(r, 3000));
    }
    await new Promise((r) => setTimeout(r, 1500));
    await $e.run('document/save/publish');
    await new Promise((r) => setTimeout(r, 3500));
    window.__c = JSON.stringify({ id: PID, top: elementor.elements.length, expected: els.length, template: elementor.settings.page.model.get('template'), leftoverReliant: leftover, overrides, unsaved: elementor.documents.getCurrent().editor.isChanged });
})();
