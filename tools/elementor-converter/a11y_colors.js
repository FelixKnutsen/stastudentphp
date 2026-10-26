// WCAG contrast fix for Elementor pages (run in the Elementor editor of a page, then it publishes).
// Brand orange #EF7330 fails with white text (2.9:1) and as text on the teal cards (3.0-3.5:1):
//   orange fills behind white text  -> #C44F14 (4.7:1 with white), hover #A8420F
//   orange text on teal             -> #FFAB78 (4.5-5.5:1)
//   light-teal text #69B3AB         -> #9ED2CC
// Icons and borders keep the brand orange (non-text, decorative).
(async () => {
    for (let i = 0; i < 40 && !(window.elementor && elementor.elements && elementor.elements.length); i++) await new Promise((r) => setTimeout(r, 500));
    await new Promise((r) => setTimeout(r, 1000));
    const ORE = /^(#ef7330|rgb\(239, ?115, ?48\)|rgba\(239, ?115, ?48, ?1\))$/i;
    const TEAL = /^(#69b3ab|rgb\(105, ?179, ?171\))$/i;
    const BG = new Set(['button.button_background_color', 'button.background_color', 'heading._background_color', 'container.background_color']);
    const BGH = new Set(['button.button_background_hover_color']);
    const TX = new Set(['button.button_text_color', 'button.hover_color', 'text-editor.text_color', 'heading.title_color', 'heading.text_color', 'toggle.title_color', 'toggle.tab_active_color']);
    const TT = new Set(['heading.title_color', 'text-editor.text_color']);
    const fixInline = (s) => s
        .replace(/(background(?:-color)?\s*:\s*)(#ef7330|rgb\(239,\s*115,\s*48\))/gi, '$1#C44F14')
        .replace(/((?:^|[^-\w])color\s*:\s*)(#ef7330|rgb\(239,\s*115,\s*48\))/gi, '$1#FFAB78');
    let n = 0;
    const walk = (c) => {
        const t = c.model.get('widgetType') || c.model.get('elType');
        const ch = {};
        for (const [k, v] of Object.entries(c.settings.attributes)) {
            if (typeof v !== 'string') continue;
            const kk = t + '.' + k;
            if (ORE.test(v.trim())) {
                if (BG.has(kk)) ch[k] = '#C44F14';
                else if (BGH.has(kk)) ch[k] = '#A8420F';
                else if (TX.has(kk)) ch[k] = '#FFAB78';
            } else if (TEAL.test(v.trim()) && TT.has(kk)) {
                ch[k] = '#9ED2CC';
            } else if (/239,\s*115,\s*48|#ef7330/i.test(v) && /[<:]/.test(v)) {
                const nv = fixInline(v);
                if (nv !== v) ch[k] = nv;
            }
        }
        if (Object.keys(ch).length) {
            $e.run('document/elements/settings', { container: c, settings: ch });
            n += Object.keys(ch).length;
        }
        (c.children || []).forEach(walk);
    };
    elementor.getPreviewContainer().children.forEach(walk);
    if (n) {
        await $e.run('document/save/publish');
        await new Promise((r) => setTimeout(r, 2500));
    }
    return elementor.config.document.id + ': changed ' + n + ', unsaved=' + elementor.documents.getCurrent().editor.isChanged;
})();
