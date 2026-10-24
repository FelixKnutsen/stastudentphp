// Browser-console helpers used with elementor_converter.js (see SITE_RESTORATION_PROGRESS.md).
// Reference tab (stastudentphp.vercel.app): STABag(slug, opts) converts the page and appends
// {el, sig} to window.name, which survives navigation to the staging tab.
window.STABag = async function (slug, opts) {
  const r = await STAConvert(opts || {});
  const bag = window.name.startsWith('STABAG|') ? JSON.parse(window.name.slice(7)) : {};
  bag[slug] = { el: STA_RESULT.elements, sig: STASig() };
  window.name = 'STABAG|' + JSON.stringify(bag);
  return r;
};
// Staging tab: unpack the bag into localStorage.
window.STAUnbag = function () {
  if (!window.name.startsWith('STABAG|')) return [];
  const bag = JSON.parse(window.name.slice(7));
  for (const [k, v] of Object.entries(bag)) {
    localStorage.setItem('sta_el_' + k, JSON.stringify(v.el));
    localStorage.setItem('sta_sig_' + k, JSON.stringify(v.sig));
  }
  window.name = '';
  return Object.keys(bag);
};
// Elementor editor (post.php?post=ID&action=elementor): replace the document, set Page Layout to
// Elementor Full Width and publish through Elementor. The previous elements are kept in localStorage.
window.STAImport = async function (slug, opts) {
  opts = opts || {};
  const data = JSON.parse(localStorage.getItem('sta_el_' + slug));
  const doc = elementor.documents.getCurrent();
  if (!localStorage.getItem('sta_backup_' + doc.id)) localStorage.setItem('sta_backup_' + doc.id, JSON.stringify(elementor.elements.toJSON({ remove: ['default'] })));
  $e.run('document/elements/empty', { force: true });
  const root = elementor.getPreviewContainer();
  data.forEach((m, i) => $e.run('document/elements/create', { container: root, model: m, options: { at: i, edit: false } }));
  $e.run('document/elements/settings', { container: doc.container, settings: { template: 'elementor_header_footer' }, options: { external: true } });
  if (opts.save !== false) await $e.run('document/save/publish');
  return { pid: doc.id, tpl: doc.container.settings.get('template') };
};
// Text-node layout signature of <main>: position, size, font and colour per text run.
window.STASig = function () {
  const main = document.querySelector('main'); const mr = main.getBoundingClientRect(); const out = {};
  const walker = document.createTreeWalker(main, NodeFilter.SHOW_TEXT); let n;
  while ((n = walker.nextNode())) {
    const t = n.textContent.replace(/\s+/g, ' ').trim(); if (t.length < 3) continue;
    const el = n.parentElement; if (el.closest('svg,script,style')) continue;
    const cs = getComputedStyle(el); if (cs.display === 'none' || cs.visibility === 'hidden') continue;
    const r = document.createRange(); r.selectNodeContents(n); const b = r.getBoundingClientRect(); if (!b.width) continue;
    let k = t.slice(0, 40); while (out[k]) k += '+';
    out[k] = [Math.round(b.top - mr.top), Math.round(b.left - mr.left), Math.round(b.width), Math.round(b.height), cs.fontSize, cs.fontWeight, cs.color];
  }
  return { h: Math.round(mr.height), items: out };
};
// Compare the current page with a stored reference signature.
window.STADiff = function (ref) {
  const cur = STASig(); const rows = []; const miss = []; let maxd = 0;
  for (const [k, a] of Object.entries(ref.items)) {
    const b = cur.items[k]; if (!b) { miss.push('-' + k); continue; }
    const d = [b[0] - a[0], b[1] - a[1], b[2] - a[2], b[3] - a[3]];
    const style = (a[4] !== b[4] ? ' fs ' + a[4] + '>' + b[4] : '') + (a[5] !== b[5] ? ' fw ' + a[5] + '>' + b[5] : '') + (a[6] !== b[6] ? ' col ' + a[6] + '>' + b[6] : '');
    const bad = Math.max(...d.map(Math.abs)); maxd = Math.max(maxd, bad);
    if (bad > 4 || style) rows.push(k.slice(0, 28) + ' d=' + d.join(',') + style);
  }
  for (const k of Object.keys(cur.items)) if (!ref.items[k]) miss.push('+' + k.slice(0, 30));
  return { refH: ref.h, curH: cur.h, maxd, rows, miss };
};
