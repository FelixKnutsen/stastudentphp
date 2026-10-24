// STA reference -> native Elementor converter.
// Runs inside a reference page (https://stastudentphp.vercel.app/<slug>/). Reads the rendered DOM and
// computed styles at desktop (current window), tablet (900px iframe) and mobile (390px iframe) and
// produces Elementor container/widget JSON with native settings (no theme classes, no HTML blobs except
// for interactive markup). Result: window.STA_RESULT = {elements, report}.
window.STAConvert = async function (opts) {
  opts = opts || {};
  const STAGING = opts.staging || 'https://stastudent.no/reliant/';
  const ROOT_SEL = opts.root || 'main';
  const HTML_SEL = opts.htmlSelectors || []; // selectors kept as HTML widgets (interactive parts)
  const ICONS = [
    ['M4 4h16c1.1', 'far fa-envelope'], ['M18 2h-3a5', 'fab fa-facebook-f'], ['M18 13v6a2', 'fas fa-external-link-alt'],
    ['M16 11.37', 'fab fa-instagram'], ['x1="2" y1="12" x2="22" y2="12"', 'fas fa-globe'], ['x1="12" y1="16" x2="12" y2="12"', 'fas fa-info-circle'],
    ['M22 11.08', 'far fa-check-circle'], ['x="4" y="2" width="16" height="20"', 'far fa-building'], ['M16 21v-2a4', 'fas fa-users'],
    ['M17 21v-2a4', 'fas fa-users'], ['M4 19.5A2.5', 'fas fa-book'], ['x2="16.65"', 'fas fa-search'], ['M22 16.92', 'fas fa-phone'],
    ['M21 10c0 7', 'fas fa-map-marker-alt'], ['m9 12 2 2 4-4', 'fas fa-shield-alt'], ['M12 22s8-4', 'fas fa-shield-alt'], ['M20.84 4.61', 'far fa-heart'],
    ['8.21 13.89', 'fas fa-award'], ['15 18 9 12 15 6', 'fas fa-chevron-left'], ['9 18 15 12 9 6', 'fas fa-chevron-right'],
    ['12 5 19 12 12 19', 'fas fa-arrow-right'], ['x="16" y="16" width="6"', 'fas fa-sitemap'], ['M16 21V5a2', 'fas fa-briefcase'],
    ['M6 22V4a2', 'fas fa-building'], ['H7l-4 4V5', 'far fa-comment-alt'], ['m12 3-1.912', 'fas fa-magic'], ['12 6 12 12 16 14', 'far fa-clock'],
    ['M21 15v4a2', 'fas fa-download'], ['7 10 12 15 17 10', 'fas fa-download'], ['M14 2H6a2', 'far fa-file-alt'], ['M13 2H6a2', 'far fa-file-alt'],
    ['x1="3" y1="21" x2="21" y2="21"', 'fas fa-landmark'], ['M2 9l10-7', 'fas fa-landmark'], ['M22 10v6M2 10l10-5', 'fas fa-graduation-cap'],
    ['20 6 9 17 4 12', 'fas fa-check'], ['x1="5" y1="12" x2="19" y2="12"', 'fas fa-minus'], ['M3 9l9-7 9 7', 'fas fa-home'],
    ['M12 2L2 7l10 5', 'fas fa-layer-group'], ['rect x="3" y="4" width="18" height="18"', 'far fa-calendar-alt'], ['M10 13a5 5 0 0 0 7.54', 'fas fa-link'],
    ['M18 8A6 6 0 0 0 6 8', 'far fa-bell'], ['polygon points="12 2 15.09', 'fas fa-star'], ['M9 18V5l12-2', 'fas fa-music'],
    ['M1 12s4-8', 'far fa-eye'], ['rect x="3" y="11" width="18" height="11"', 'fas fa-lock'], ['M3 3v18h18', 'fas fa-chart-bar'],
  ];
  const report = { widgets: {}, html: [], unmappedIcons: [], links: {} };
  const rid = () => Math.random().toString(16).slice(2, 9);
  const num = (v) => { const n = parseFloat(v); return isNaN(n) ? 0 : Math.round(n * 10) / 10; };
  const S = (v) => String(v);
  const transparent = (c) => !c || c === 'transparent' || /rgba\([^)]*,\s*0\)$/.test(c);
  const CS = (el) => el.ownerDocument.defaultView.getComputedStyle(el);
  const box = (a) => ({ unit: 'px', top: S(a[0]), right: S(a[1]), bottom: S(a[2]), left: S(a[3]), isLinked: a.every((x) => x === a[0]) });
  const sides = (cs, p) => [cs[p + 'Top'], cs[p + 'Right'], cs[p + 'Bottom'], cs[p + 'Left']].map(num);
  const radii = (cs) => [cs.borderTopLeftRadius, cs.borderTopRightRadius, cs.borderBottomRightRadius, cs.borderBottomLeftRadius].map(num);
  const tagOf = (e) => e.tagName.toLowerCase();
  const visible = (e) => e && CS(e).display !== 'none' && CS(e).visibility !== 'hidden';
  const INLINE_OK = ['br', 'strong', 'em', 'b', 'i', 'small', 'code', 'sup', 'sub', 'u', 'mark', 'abbr', 'time'];

  function mapUrl(href) {
    if (!href) return '';
    if (/^(mailto|tel):/i.test(href)) return href;
    let u; try { u = new URL(href, location.href); } catch (e) { return href; }
    const local = u.host === location.host || u.host === 'localhost:8080';
    if (!local) return u.href;
    let p = u.pathname;
    if (/^\/(wp-content|downloads)\//.test(p)) return STAGING.replace(/\/$/, '') + p;
    p = p.replace(/^\/+|\/+$/g, '');
    let out;
    if (!p) out = STAGING;
    else {
      const parts = p.split('/').filter((x) => x !== 'en' && x !== 'nn');
      if (parts[0] === 'nyheter' && parts[1]) out = STAGING + '?name=' + parts[1];
      else if (parts[0] === 'category') out = STAGING + '?sta_page=nyheter';
      else out = STAGING + '?sta_page=' + parts[parts.length - 1];
    }
    out += u.hash || '';
    report.links[href] = out;
    return out;
  }
  const absSrc = (src) => mapUrl(new URL(src, location.href).href);

  function cleanHtml(el) {
    const c = el.cloneNode(true);
    c.querySelectorAll('[class]').forEach((n) => n.removeAttribute('class'));
    c.querySelectorAll('a[href]').forEach((a) => a.setAttribute('href', mapUrl(a.getAttribute('href'))));
    return c.innerHTML.replace(/\s+/g, ' ').trim();
  }
  function iconFor(svg) {
    const h = svg.innerHTML.replace(/\s+/g, ' ');
    for (const [k, v] of ICONS) if (h.includes(k)) return { value: v, library: v.startsWith('fab') ? 'fa-brands' : v.startsWith('far') ? 'fa-regular' : 'fa-solid' };
    report.unmappedIcons.push(h.slice(0, 80));
    return null;
  }
  function isLeaf(e) {
    if (!e.textContent.trim()) return false;
    for (const d of e.querySelectorAll('*')) {
      const t = tagOf(d);
      if (t === 'svg' || t === 'img' || t === 'input' || t === 'button' || t === 'select') return false;
      if (INLINE_OK.includes(t)) continue;
      if (CS(d).display !== 'inline') return false;
    }
    return true;
  }
  function isList(e) {
    const t = tagOf(e);
    return (t === 'ul' || t === 'ol') && e.children.length > 0 && [...e.children].every((li) => tagOf(li) === 'li' && (isLeaf(li) || !li.textContent.trim()));
  }
  // element = one direct svg + text (leaf children or text nodes); e.g. <a><span>Text</span><svg/></a>
  function iconText(e) {
    const kids = [...e.children];
    const svgs = kids.filter((k) => tagOf(k) === 'svg');
    if (svgs.length !== 1 || !e.textContent.trim()) return null;
    const others = kids.filter((k) => k !== svgs[0]);
    if (!others.every((k) => isLeaf(k) || INLINE_OK.includes(tagOf(k)))) return null;
    const textEl = others.find((k) => k.textContent.trim());
    const first = kids[0] === svgs[0] && ![...e.childNodes].slice(0, [...e.childNodes].indexOf(svgs[0])).some((n) => n.textContent.trim());
    let html = '';
    e.childNodes.forEach((n) => { if (n !== svgs[0]) html += n.nodeType === 3 ? n.textContent : (n.nodeType === 1 ? cleanHtmlOuter(n) : ''); });
    return { svg: svgs[0], textEl: textEl || e, html: html.replace(/\s+/g, ' ').trim(), iconFirst: first };
  }
  function cleanHtmlOuter(n) {
    if (['span', 'div', 'p'].includes(tagOf(n))) return cleanHtml(n);
    const c = n.cloneNode(true); c.removeAttribute('class'); c.querySelectorAll('[class]').forEach((x) => x.removeAttribute('class'));
    return c.outerHTML;
  }
  function kindOf(e) {
    const t = tagOf(e);
    if (['script', 'style', 'noscript', 'template', 'link', 'meta'].includes(t)) return 'skip';
    if (HTML_SEL.some((s) => e.matches(s))) return 'html';
    if (t === 'img') return 'image';
    if (t === 'svg') return 'icon';
    if (t === 'br' || t === 'hr') return t === 'hr' ? 'divider' : 'skip';
    if (['form', 'input', 'select', 'textarea', 'iframe', 'video', 'canvas', 'button', 'table', 'dialog', 'picture'].includes(t)) return 'html';
    if (iconText(e)) return t === 'a' ? 'button' : (iconText(e).iconFirst ? 'iconlist' : 'container');
    if ((t === 'ul' || t === 'ol') && e.children.length && [...e.children].every((li) => (iconText(li) && iconText(li).iconFirst) || (li.children.length === 1 && tagOf(li.children[0]) === 'a' && iconText(li.children[0]) && iconText(li.children[0]).iconFirst))) return 'iconlistMulti';
    if (isList(e)) return 'list';
    if (isLeaf(e)) return /^h[1-6]$/.test(t) ? 'heading' : t === 'a' ? 'button' : 'text';
    if (!e.children.length) return e.textContent.trim() ? 'text' : (hasBox(e) ? 'container' : 'skip');
    return 'container';
  }
  function hasBox(e) {
    const cs = CS(e);
    return !transparent(cs.backgroundColor) || cs.backgroundImage !== 'none' || num(cs.borderTopWidth) > 0 || num(cs.height) > 2;
  }

  // ---------- style helpers (per viewport element) ----------
  function contentBox(el) {
    const r = el.getBoundingClientRect(), cs = CS(el);
    const L = r.left + num(cs.borderLeftWidth) + num(cs.paddingLeft), R = r.right - num(cs.borderRightWidth) - num(cs.paddingRight);
    const T = r.top + num(cs.borderTopWidth) + num(cs.paddingTop), B = r.bottom - num(cs.borderBottomWidth) - num(cs.paddingBottom);
    return { L, R, T, B, W: R - L };
  }
  function parentMode(el) {
    const p = el.parentElement; if (!p) return 'column';
    const cs = CS(p);
    if (cs.display.includes('grid')) return 'grid';
    if (cs.display.includes('flex')) return cs.flexDirection.startsWith('row') ? (rowAsGrid(p) ? 'grid' : 'row') : 'column';
    if (inlineRow(p)) return 'row';
    return 'column';
  }
  function kidsVisible(p) { return [...p.children].filter((k) => visible(k) && kindOf(k) !== 'skip' && CS(k).position !== 'absolute'); }
  function rowAsGrid(p) {
    const cs = CS(p); if (cs.flexWrap !== 'wrap') return 0;
    const k = kidsVisible(p); if (k.length < 2) return 0;
    const top = k[0].getBoundingClientRect().top; const n = k.filter((x) => Math.abs(x.getBoundingClientRect().top - top) < 2).length;
    const w0 = k[0].getBoundingClientRect().width;
    if (!k.every((x) => Math.abs(x.getBoundingClientRect().width - w0) < 2)) return 0;
    if (n === k.length && n * w0 < contentBox(p).W * 0.8) return 0; // single short row of fixed items: keep flex
    return n;
  }
  function inlineRow(p) {
    const k = kidsVisible(p); if (k.length < 2) return false;
    if (!k.every((x) => /^inline/.test(CS(x).display))) return false;
    const t = k[0].getBoundingClientRect().top;
    return k.some((x, i) => i > 0 && Math.abs(x.getBoundingClientRect().top - t) < 4);
  }
  const mapJ = (v) => ({ center: 'center', end: 'flex-end', 'flex-end': 'flex-end', right: 'flex-end', 'space-between': 'space-between', 'space-around': 'space-around', 'space-evenly': 'space-evenly' }[v] || 'flex-start');
  const mapA = (v) => ({ center: 'center', end: 'flex-end', 'flex-end': 'flex-end', start: 'flex-start', 'flex-start': 'flex-start', baseline: 'flex-start' }[v] || 'stretch');
  const gaps = (c, r) => ({ unit: 'px', column: S(num(c)), row: S(num(r)), isLinked: num(c) === num(r), size: num(c) });

  function background(cs, pre, s) {
    const bi = cs.backgroundImage;
    const url = (bi.match(/url\(["']?([^"')]+)["']?\)/) || [])[1];
    const grad = bi.match(/linear-gradient\((.*)\)/);
    if (url) {
      s[pre + 'background_background'] = 'classic';
      s[pre + 'background_image'] = { url: absSrc(url), id: '', size: '', source: 'library' };
      const pos = cs.backgroundPosition.split(',')[0].trim().replace(/50%/g, 'center').replace(/0%/g, 'top').replace(/100%/g, 'bottom');
      s[pre + 'background_position'] = /center center|center/.test(pos) ? 'center center' : pos;
      s[pre + 'background_size'] = cs.backgroundSize.split(',')[0].includes('cover') ? 'cover' : cs.backgroundSize.includes('contain') ? 'contain' : 'auto';
      s[pre + 'background_repeat'] = cs.backgroundRepeat.split(',')[0].trim();
      if (!transparent(cs.backgroundColor)) s[pre + 'background_color'] = cs.backgroundColor;
      if (grad) {
        const cols = grad[1].match(/rgba?\([^)]+\)/g) || [];
        if (cols.length) { s[pre + 'background_overlay_background'] = 'classic'; s[pre + 'background_overlay_color'] = cols[0]; s[pre + 'background_overlay_opacity'] = { unit: 'px', size: 1 }; }
      }
    } else if (grad) {
      const g = grad[1]; const cols = g.match(/rgba?\([^)]+\)/g) || [];
      const ang = (g.match(/^(-?[\d.]+)deg/) || [])[1];
      const stops = [...g.matchAll(/\)\s+([\d.]+)%/g)].map((m) => num(m[1]));
      s[pre + 'background_background'] = 'gradient';
      s[pre + 'background_color'] = cols[0] || 'transparent';
      s[pre + 'background_color_b'] = cols[cols.length - 1] || 'transparent';
      s[pre + 'background_gradient_angle'] = { unit: 'deg', size: ang ? num(ang) : 180 };
      s[pre + 'background_color_stop'] = { unit: '%', size: stops[0] || 0 };
      s[pre + 'background_color_b_stop'] = { unit: '%', size: stops.length > 1 ? stops[stops.length - 1] : 100 };
    } else if (!transparent(cs.backgroundColor)) {
      s[pre + 'background_background'] = 'classic';
      s[pre + 'background_color'] = cs.backgroundColor;
    }
  }
  function border(cs, pre, s) {
    const w = sides(cs, 'border').length ? [cs.borderTopWidth, cs.borderRightWidth, cs.borderBottomWidth, cs.borderLeftWidth].map(num) : [0, 0, 0, 0];
    const st = [cs.borderTopStyle, cs.borderRightStyle, cs.borderBottomStyle, cs.borderLeftStyle];
    const ws = w.map((x, i) => (st[i] === 'none' ? 0 : x));
    if (ws.some((x) => x > 0)) {
      const i = ws.findIndex((x) => x > 0);
      s[pre + 'border_border'] = st[i] === 'dashed' ? 'dashed' : st[i] === 'dotted' ? 'dotted' : 'solid';
      s[pre + 'border_width'] = box(ws);
      s[pre + 'border_color'] = [cs.borderTopColor, cs.borderRightColor, cs.borderBottomColor, cs.borderLeftColor][i];
    }
    const r = radii(cs);
    if (r.some((x) => x > 0)) s[pre + 'border_radius'] = box(r.map((x) => Math.min(x, 9999)));
    if (cs.boxShadow && cs.boxShadow !== 'none') {
      const m = cs.boxShadow.match(/(rgba?\([^)]+\))\s+(-?[\d.]+)px\s+(-?[\d.]+)px\s+([\d.]+)px\s*(-?[\d.]+)?p?x?/);
      if (m) {
        s[pre + 'box_shadow_box_shadow_type'] = 'yes';
        s[pre + 'box_shadow_box_shadow'] = { horizontal: num(m[2]), vertical: num(m[3]), blur: num(m[4]), spread: num(m[5] || 0), color: m[1] };
      }
    }
  }
  // spacing derived from rendered rects so collapsed block margins are reproduced exactly
  function spacing(el, pre, s, mode) {
    const cs = CS(el);
    s[pre + 'padding'] = box(sides(cs, 'padding'));
    const p = el.parentElement;
    let m = [0, 0, 0, 0];
    if (mode === 'column' && p) {
      const sib = kidsVisible(p); const i = sib.indexOf(el); const cb = contentBox(p); const r = el.getBoundingClientRect();
      const top = i <= 0 ? r.top - cb.T : r.top - sib[i - 1].getBoundingClientRect().bottom;
      const bot = i === sib.length - 1 ? Math.max(0, cb.B - r.bottom) : 0;
      m = [Math.max(0, Math.round(top)), 0, i === sib.length - 1 ? Math.round(bot) : 0, 0];
      if (CS(p).display.includes('flex')) m = [num(cs.marginTop), 0, num(cs.marginBottom), 0];
    } else if (mode === 'row' || mode === 'grid') {
      m = [num(cs.marginTop), num(cs.marginRight), num(cs.marginBottom), num(cs.marginLeft)];
    }
    if (mode === 'top') m = [num(cs.marginTop), 0, num(cs.marginBottom), 0];
    s[pre + 'margin'] = box(m);
  }
  // width + self alignment relative to parent
  function sizing(el, s, isWidget, mode) {
    const p = el.parentElement; const r = el.getBoundingClientRect(); const cs = CS(el);
    const wKey = isWidget ? '_element_custom_width' : 'width';
    if (cs.position === 'absolute' && p) {
      const pr = p.getBoundingClientRect();
      s._position = 'absolute';
      s._offset_orientation_h = 'start'; s._offset_x = { unit: 'px', size: Math.round(r.left - pr.left) };
      s._offset_orientation_v = 'start'; s._offset_y = { unit: 'px', size: Math.round(r.top - pr.top) };
      if (isWidget) { s._element_width = 'initial'; s[wKey] = { unit: 'px', size: Math.round(r.width) }; }
      else s.width = { unit: 'px', size: Math.round(r.width) };
      if (cs.zIndex !== 'auto') s._z_index = num(cs.zIndex);
      return;
    }
    if (!p || mode === 'top') return;
    const cb = contentBox(p);
    if (mode === 'grid') { if (isWidget) s._element_width = 'inherit'; else s.width = { unit: '%', size: 100 }; return; }
    if (mode === 'row') {
      if (num(cs.flexGrow) > 0) { s._flex_size = 'grow'; if (isWidget) s._element_width = 'auto'; return; }
      s._flex_size = 'none';
      if (isWidget) { if (!transparent(cs.backgroundColor) || num(cs.borderTopWidth) > 0) { s._element_width = 'initial'; s[wKey] = { unit: 'px', size: Math.round(r.width) }; } else s._element_width = 'auto'; }
      else s.width = { unit: 'px', size: Math.round(r.width) };
      return;
    }
    // column / block parent
    const full = Math.abs(r.width - cb.W) <= 1.5;
    if (full) { if (isWidget) s._element_width = 'inherit'; else s.width = { unit: '%', size: 100 }; s._flex_align_self = ''; return; }
    const inl = /^inline/.test(cs.display) || cs.display === 'table';
    const pcs = CS(p); const shrink = pcs.display.includes('flex') && !['normal', 'stretch'].includes(pcs.alignItems) && cs.maxWidth === 'none';
    const mw = cs.maxWidth.endsWith('px') ? Math.round(num(cs.maxWidth)) : Math.round(r.width);
    if (isWidget) {
      if (inl || r.width < 2 || shrink) s._element_width = 'auto';
      else { s._element_width = 'initial'; s[wKey] = { unit: 'px', size: Math.min(mw, Math.round(cb.W)) }; }
    } else s.width = { unit: 'px', size: Math.round(r.width) };
    const lg = r.left - cb.L, rg = cb.R - r.right;
    s._flex_align_self = Math.abs(lg - rg) < 3 ? 'center' : rg < lg ? 'flex-end' : 'flex-start';
  }
  function typo(cs, pre, s) {
    const fs = num(cs.fontSize);
    s[pre + '_typography'] = 'custom';
    s[pre + '_font_size'] = { unit: 'px', size: fs };
    s[pre + '_font_weight'] = String(cs.fontWeight);
    s[pre + '_line_height'] = { unit: 'px', size: cs.lineHeight === 'normal' ? Math.round(fs * 1.25) : num(cs.lineHeight) };
    s[pre + '_letter_spacing'] = { unit: 'px', size: cs.letterSpacing === 'normal' ? '' : num(cs.letterSpacing) };
    if (cs.textTransform !== 'none') s[pre + '_text_transform'] = cs.textTransform;
    if (cs.fontStyle === 'italic') s[pre + '_font_style'] = 'italic';
    if (cs.textDecorationLine && cs.textDecorationLine !== 'none') s[pre + '_text_decoration'] = cs.textDecorationLine;
  }
  const alignOf = (cs) => ({ center: 'center', right: 'right', end: 'right', justify: 'justify' }[cs.textAlign] || 'left');
  function vis(el, s, bp) { if (!visible(el)) s['hide_' + bp] = 'hidden-' + bp; }

  // ---------- per-viewport settings ----------
  function settingsFor(kind, el, isTop, bp) {
    const s = {};
    if (!el) return s;
    vis(el, s, bp);
    if (!visible(el)) return s;
    const cs = CS(el); const mode = isTop ? 'top' : parentMode(el);
    if (kind === 'container') {
      const disp = cs.display; const asGrid = disp.includes('flex') ? rowAsGrid(el) : 0;
      if (disp.includes('grid') || asGrid) {
        s.container_type = 'grid';
        let tracks = asGrid ? Array(asGrid).fill(1) : cs.gridTemplateColumns.split(' ').map(parseFloat).filter((x) => x > 0.5);
        if (!tracks.length) tracks = [1];
        const eq = tracks.every((t) => Math.abs(t - tracks[0]) < 2);
        const mn = Math.min(...tracks);
        s.grid_columns_grid = eq ? { unit: 'fr', size: tracks.length, sizes: [] } : { unit: 'custom', size: tracks.map((t) => +(t / mn).toFixed(2) + 'fr').join(' '), sizes: [] };
        s.grid_rows_grid = { unit: 'custom', size: 'auto', sizes: [] };
        s.grid_gaps = gaps(cs.columnGap === 'normal' ? 0 : cs.columnGap, cs.rowGap === 'normal' ? 0 : cs.rowGap);
        s.grid_auto_flow = 'row';
        if (['center', 'start', 'end', 'flex-start'].includes(cs.alignItems)) s.grid_align_items = cs.alignItems.replace('flex-', '');
      } else {
        s.container_type = 'flex';
        if (disp.includes('flex')) {
          s.flex_direction = cs.flexDirection;
          s.flex_wrap = cs.flexWrap === 'wrap' ? 'wrap' : 'nowrap';
          s.flex_justify_content = mapJ(cs.justifyContent);
          s.flex_align_items = mapA(cs.alignItems);
          s.flex_gap = gaps(cs.columnGap === 'normal' ? 0 : cs.columnGap, cs.rowGap === 'normal' ? 0 : cs.rowGap);
        } else if (inlineRow(el)) {
          const k = kidsVisible(el); const r0 = k[0].getBoundingClientRect(), r1 = k[1].getBoundingClientRect();
          s.flex_direction = 'row'; s.flex_wrap = 'wrap';
          s.flex_justify_content = { center: 'center', right: 'flex-end', end: 'flex-end' }[cs.textAlign] || 'flex-start';
          s.flex_align_items = 'center';
          const g = Math.max(0, Math.round(r1.left - r0.right));
          s.flex_gap = gaps(g, g);
        } else {
          s.flex_direction = 'column'; s.flex_wrap = 'nowrap'; s.flex_justify_content = 'flex-start';
          s.flex_align_items = 'stretch'; s.flex_gap = gaps(0, 0);
        }
      }
      spacing(el, '', s, mode);
      background(cs, '', s); border(cs, '', s);
      if (cs.overflow === 'hidden' || cs.overflowX === 'hidden') s.overflow = 'hidden';
      if (isTop) {
        if (cs.maxWidth !== 'none') { s.content_width = 'boxed'; s.boxed_width = { unit: 'px', size: Math.round(num(cs.maxWidth) - num(cs.paddingLeft) - num(cs.paddingRight)) }; }
        else s.content_width = 'full';
      } else {
        s.content_width = 'full';
        sizing(el, s, false, mode);
        const kr = kidsVisible(el).map((k) => k.getBoundingClientRect()); const rh = el.getBoundingClientRect().height; const ccb = contentBox(el);
        if (rh <= 160 && kr.length && (ccb.B - ccb.T) - (Math.max(...kr.map((x) => x.bottom)) - Math.min(...kr.map((x) => x.top))) > 2) s.min_height = { unit: 'px', size: Math.round(rh) };
      }
      const minH = num(cs.minHeight); if (minH > 0) s.min_height = { unit: 'px', size: minH };
      if (cs.aspectRatio && cs.aspectRatio !== 'auto' && el.children.length === 0) s.min_height = { unit: 'px', size: Math.round(el.getBoundingClientRect().height) };
      return s;
    }
    // widgets
    sizing(el, s, true, mode);
    if (kind === 'heading' || kind === 'text') {
      const pre = 'typography';
      typo(cs, pre, s);
      s.align = alignOf(cs);
      s[kind === 'heading' ? 'title_color' : 'text_color'] = cs.color;
      spacing(el, '_', s, mode); background(cs, '_', s); border(cs, '_', s);
      if (/^inline/.test(cs.display) && kind === 'heading') s.align = 'center';
      const rg = el.ownerDocument.createRange(); rg.selectNodeContents(el); const tr = rg.getBoundingClientRect(); const ccb = contentBox(el); const extra = (ccb.B - ccb.T) - tr.height;
      if (extra > 2 && cs.display.includes('flex')) { const pd = sides(cs, 'padding'); s._padding = box([Math.round(pd[0] + extra / 2), pd[1], Math.round(pd[2] + extra / 2), pd[3]]); }
      if (cs.display.includes('flex') && cs.justifyContent === 'center') s.align = 'center';
    } else if (kind === 'list') {
      typo(cs, 'typography', s); s.align = alignOf(cs); s.text_color = cs.color;
      spacing(el, '_', s, mode); background(cs, '_', s); border(cs, '_', s);
      s._padding = box([0, 0, 0, 0]);
    } else if (kind === 'button') {
      const it = iconText(el);
      const tcs = it && it.textEl !== el ? CS(it.textEl) : cs;
      typo(tcs, 'typography', s);
      s.button_text_color = cs.color;
      s.hover_color = cs.color;
      if (!transparent(cs.backgroundColor)) { s.background_background = 'classic'; s.background_color = cs.backgroundColor; s.button_background_hover_color = cs.backgroundColor; }
      else { s.background_background = 'classic'; s.background_color = 'rgba(0,0,0,0)'; s.button_background_hover_color = 'rgba(0,0,0,0)'; }
      const b = {}; border(cs, '', b);
      if (b.border_border) { s.border_border = b.border_border; s.border_width = b.border_width; s.border_color = b.border_color; }
      s.border_radius = b.border_radius || box([0, 0, 0, 0]);
      if (b.box_shadow_box_shadow_type) { s.button_box_shadow_box_shadow_type = 'yes'; s.button_box_shadow_box_shadow = b.box_shadow_box_shadow; }
      s.text_padding = box(sides(cs, 'padding'));
      spacing(el, '_', s, mode);
      delete s._padding;
      const p = el.parentElement; const r = el.getBoundingClientRect(); const cb = p ? contentBox(p) : null;
      if (mode === 'column' && cb) {
        const full = Math.abs(r.width - cb.W) <= 1.5 && !/^inline/.test(cs.display);
        const boxed = !transparent(cs.backgroundColor) || ['Top', 'Right', 'Bottom', 'Left'].some((x) => num(cs['border' + x + 'Width']) > 0 && cs['border' + x + 'Style'] !== 'none');
        let lg = r.left - cb.L, rg = cb.R - r.right;
        if (full && !boxed) { const rr = el.ownerDocument.createRange(); rr.selectNodeContents(el); const c = rr.getBoundingClientRect(); lg = c.left - cb.L; rg = cb.R - c.right; }
        s.align = full && (boxed || cs.justifyContent === 'space-between') ? 'justify' : Math.abs(lg - rg) < 3 ? 'center' : rg < lg ? 'right' : 'left';
        s._element_width = ''; delete s._element_custom_width; delete s._flex_align_self;
      }
      if (mode === 'row') { s._element_width = 'auto'; delete s._element_custom_width; }
      if (it) { s.icon_indent = { unit: 'px', size: num(cs.columnGap === 'normal' ? 6 : cs.columnGap) || 6 }; }
      if (cs.justifyContent === 'space-between') s.content_align = 'space-between';
    } else if (kind === 'image') {
      const r = el.getBoundingClientRect();
      s.width = { unit: '%', size: 100 };
      if (cs.objectFit === 'cover' || cs.objectFit === 'contain') { s['object-fit'] = cs.objectFit; s.height = { unit: 'px', size: Math.round(r.height) }; }
      if (cs.objectPosition && cs.objectPosition !== '50% 50%') s['object-position'] = 'center center';
      const rr = radii(cs); if (rr.some((x) => x > 0)) s.image_border_radius = box(rr);
      s.align = 'center';
      spacing(el, '_', s, mode);
      if (mode === 'column' && Math.abs(r.width - contentBox(el.parentElement).W) > 1.5) { s.width = { unit: 'px', size: Math.round(r.width) }; }
    } else if (kind === 'icon') {
      const r = el.getBoundingClientRect();
      s.primary_color = cs.color; s.size = { unit: 'px', size: Math.round(r.width) || 16 };
      s.align = 'center';
      if (mode === 'column') s.align = alignOf(CS(el.parentElement));
      spacing(el, '_', s, mode); delete s._padding;
      s._element_width = mode === 'column' ? s._element_width : 'auto';
    } else if (kind === 'iconlist' || kind === 'iconlistMulti') {
      const items = kind === 'iconlist' ? [el] : [...el.children].map((li) => (iconText(li) ? li : li.children[0]));
      const first = iconText(items[0]);
      const tcs = first.textEl !== items[0] ? CS(first.textEl) : CS(items[0]);
      typo(tcs, 'icon_typography', s);
      s.text_color = tcs.color; s.icon_color = CS(first.svg).color;
      s.icon_size = { unit: 'px', size: Math.round(first.svg.getBoundingClientRect().width) || 14 };
      const icg = CS(items[0]).columnGap; s.text_indent = { unit: 'px', size: num(icg === 'normal' ? 6 : icg) };
      if (items.length > 1) { const g = items[1].getBoundingClientRect().top - items[0].getBoundingClientRect().bottom; s.space_between = { unit: 'px', size: Math.max(0, Math.round(g)) }; }
      s.icon_self_vertical_align = 'center';
      spacing(el, '_', s, mode); background(cs, '_', s); border(cs, '_', s);
    } else if (kind === 'html' || kind === 'divider') {
      spacing(el, '_', s, mode); delete s._padding;
      if (kind === 'divider') { s.color = cs.borderTopColor; s.weight = { unit: 'px', size: num(cs.borderTopWidth) || 1 }; s.gap = { unit: 'px', size: 0 }; }
    }
    return s;
  }
  const RESP = /^(padding|margin|_padding|_margin|flex_direction|flex_wrap|flex_gap|flex_justify_content|flex_align_items|grid_columns_grid|grid_gaps|grid_align_items|width|min_height|boxed_width|_element_width|_element_custom_width|_flex_align_self|_flex_size|align|typography_font_size|typography_line_height|typography_letter_spacing|icon_typography_font_size|icon_typography_line_height|height|space_between|image_border_radius|border_radius|_border_radius|text_padding|size|icon_size|_offset_x|_offset_y|object-fit|text_indent)$/;
  function merge(d, t, m) {
    const out = Object.assign({}, d);
    for (const [bp, src, base] of [['tablet', t, d], ['mobile', m, null]]) {
      const ref = base || Object.assign({}, d, Object.fromEntries(Object.entries(t).filter(([k]) => RESP.test(k))));
      for (const k of Object.keys(src)) {
        if (k.startsWith('hide_')) { out[k] = src[k]; continue; }
        if (!RESP.test(k)) continue;
        if (JSON.stringify(src[k]) !== JSON.stringify(ref[k])) out[k + '_' + bp] = src[k];
      }
    }
    for (const k of ['width', 'flex_wrap']) if (m[k] !== undefined && out[k + '_mobile'] === undefined) out[k + '_mobile'] = m[k];
    return out;
  }

  // ---------- tree walk ----------
  function pathOf(e, root) { const p = []; while (e && e !== root) { p.unshift([...e.parentElement.children].indexOf(e)); e = e.parentElement; } return p; }
  function at(root, path) { let e = root; for (const i of path) { e = e && e.children[i]; } return e; }
  async function frame(w) {
    return new Promise((res) => {
      const f = document.createElement('iframe');
      f.style.cssText = 'position:absolute;left:-10000px;top:0;width:' + w + 'px;height:1000px;border:0;';
      f.src = location.href;
      f.onload = () => { const st = f.contentDocument.createElement('style'); st.textContent = 'html{scrollbar-width:none}html::-webkit-scrollbar{display:none}'; f.contentDocument.head.appendChild(st); setTimeout(() => res(f), 700); };
      document.body.appendChild(f);
    });
  }
  const fT = await frame(opts.tabletWidth || 900), fM = await frame(opts.mobileWidth || 390);
  if (opts.prep) [document, fT.contentDocument, fM.contentDocument].forEach((d) => opts.prep(d));
  const rootD = document.querySelector(ROOT_SEL), rootT = fT.contentDocument.querySelector(ROOT_SEL), rootM = fM.contentDocument.querySelector(ROOT_SEL);

  function build(e, depth, isTop) {
    const path = pathOf(e, rootD); const eT = at(rootT, path), eM = at(rootM, path);
    if (!visible(e) && !visible(eT) && !visible(eM)) return [];
    const ref = visible(e) ? e : visible(eT) ? eT : eM;
    const kind = kindOf(ref);
    if (kind === 'skip') return [];
    const settings = merge(settingsFor(kind, e, isTop, 'desktop'), settingsFor(kind, eT, isTop, 'tablet'), settingsFor(kind, eM, isTop, 'mobile'));
    const t = tagOf(e);
    report.widgets[kind] = (report.widgets[kind] || 0) + 1;
    if (kind === 'container') {
      if (['section', 'header', 'article', 'aside', 'nav', 'footer'].includes(t)) settings.html_tag = t;
      if (t === 'a') { settings.html_tag = 'a'; settings.link = { url: mapUrl(e.getAttribute('href')), is_external: e.target === '_blank' ? 'on' : '', nofollow: '' }; }
      const kids = [];
      e.childNodes.forEach((n) => {
        if (n.nodeType === 3 && n.textContent.trim()) {
          const cs = CS(e); const s = { editor: '<p>' + n.textContent.trim() + '</p>', text_color: cs.color, align: alignOf(cs) }; typo(cs, 'typography', s);
          kids.push({ id: rid(), elType: 'widget', widgetType: 'text-editor', isInner: false, settings: s, elements: [] });
        } else if (n.nodeType === 1) kids.push(...build(n, depth + 1, false));
      });
      return [{ id: rid(), elType: 'container', isInner: depth > 0, settings, elements: kids }];
    }
    const W = (type, extra) => [{ id: rid(), elType: 'widget', widgetType: type, isInner: false, settings: Object.assign(settings, extra), elements: [] }];
    if (kind === 'heading') return W('heading', { title: cleanHtml(e), header_size: /^h[1-6]$/.test(t) ? t : ['p', 'span', 'div'].includes(t) ? t : 'div' });
    if (kind === 'text') {
      const inner = cleanHtml(e);
      if (/^inline/.test(CS(ref).display) || t === 'span' || t === 'label') return W('heading', { title: inner, header_size: t === 'label' ? 'span' : (t === 'p' ? 'p' : 'span'), title_color: settings.text_color });
      return W('text-editor', { editor: '<p>' + inner + '</p>' });
    }
    if (kind === 'list') {
      const cs = CS(e); const li0 = e.children[0]; const lcs = li0 ? CS(li0) : cs;
      const items = [...e.children].map((li) => '<li style="margin-bottom:' + num(CS(li).marginBottom) + 'px">' + cleanHtml(li) + '</li>').join('');
      return W('text-editor', { editor: '<' + t + ' style="margin:0;padding-left:' + num(cs.paddingLeft) + 'px;list-style-type:' + cs.listStyleType + '">' + items + '</' + t + '>', text_color: lcs.color });
    }
    if (kind === 'button') {
      const it = iconText(e);
      const extra = { text: it ? it.html.replace(/<[^>]+>/g, '').trim() : e.textContent.replace(/\s+/g, ' ').trim(), link: { url: mapUrl(e.getAttribute('href')), is_external: e.target === '_blank' ? 'on' : '', nofollow: '' }, size: 'sm' };
      if (it) { const ic = iconFor(it.svg); if (ic) { extra.selected_icon = ic; extra.icon_align = it.iconFirst ? 'row' : 'row-reverse'; } }
      return W('button', extra);
    }
    if (kind === 'image') return W('image', { image: { url: absSrc(e.getAttribute('src')), id: '', size: '', alt: e.getAttribute('alt') || '', source: 'library' }, image_size: 'full' });
    if (kind === 'icon') {
      const ic = iconFor(e);
      if (ic) return W('icon', { selected_icon: ic, view: 'default' });
      report.html.push('svg');
      return W('html', { html: '<div style="color:' + CS(e).color + ';line-height:0;display:inline-block">' + e.outerHTML + '</div>' });
    }
    if (kind === 'iconlist' || kind === 'iconlistMulti') {
      const items = kind === 'iconlist' ? [e] : [...e.children].map((li) => (iconText(li) ? li : li.children[0]));
      const list = items.map((it) => {
        const x = iconText(it); const a = tagOf(it) === 'a' ? it : null;
        return { _id: rid(), text: x.html, selected_icon: iconFor(x.svg) || { value: 'fas fa-circle', library: 'fa-solid' }, link: a ? { url: mapUrl(a.getAttribute('href')), is_external: a.target === '_blank' ? 'on' : '' } : { url: '' } };
      });
      return W('icon-list', { icon_list: list, view: 'traditional' });
    }
    if (kind === 'divider') return W('divider', {});
    report.html.push(t + (e.id ? '#' + e.id : '') + (e.className && typeof e.className === 'string' ? '.' + e.className.split(' ')[0] : ''));
    const c = e.cloneNode(true); c.querySelectorAll('a[href]').forEach((a) => a.setAttribute('href', mapUrl(a.getAttribute('href'))));
    if (c.matches && c.matches('a[href]')) c.setAttribute('href', mapUrl(c.getAttribute('href')));
    return W('html', { html: c.outerHTML });
  }
  const elements = [];
  [...rootD.children].forEach((c) => elements.push(...build(c, 0, true)));
  fT.remove(); fM.remove();
  window.STA_RESULT = { elements, report };
  return { count: JSON.stringify(elements).length, report };
};
