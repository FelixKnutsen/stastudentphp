// Pre-processing for https://stastudentphp.vercel.app/rad-styrer-og-utvalg/ before conversion.
// The reference export was rendered by an older template that read $cat['name'] (missing key), which
// printed PHP warnings and left the three category headings empty. Remove the warning markup and restore
// the category titles from the data file that rendered the page (git d13b941:sta-theme/data/committees.json).
window.STA_RSU_PREP = function (doc) {
  const main = doc.querySelector('main');
  [...main.querySelectorAll('b')].filter((b) => /^(Warning|Notice|Deprecated)$/.test(b.textContent.trim())).forEach((b) => {
    const nodes = [];
    let n = b.previousSibling;
    while (n && n.nodeType === 3 && !n.textContent.trim()) n = n.previousSibling;
    if (n && n.nodeName === 'BR') nodes.push(n);
    n = b;
    while (n) { nodes.push(n); if (n !== b && n.nodeName === 'BR') break; n = n.nextSibling; }
    nodes.forEach((x) => x.remove());
  });
  const titles = ['STA-Verv', 'STAs Politiske Utvalg', 'Universitetsorganer (UiA)'];
  [...main.querySelectorAll('h2')].filter((h) => !h.textContent.trim()).forEach((h, i) => { if (titles[i]) h.textContent = titles[i]; });
};
