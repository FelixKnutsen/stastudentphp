// Rebuilds "Om oss" (page 13186) as native Elementor containers in the redesign style.
// Run in the Elementor editor of page 13186. Expects window.__om = {img, maps} taken from
// the previous page data (photo setting and the two OpenStreetMap HTML widgets).
// After the first run these were adjusted in the editor: CTA text shrinks (_flex_size custom 1/1);
// committee cards became a centred wrapped row (32% / 48% / 100%) with the icon beside the name;
// "Møt styret" -> "Les mer"; internal links changed to /reliant/?page_id=30, 161, 14071 and 325
// because staging has pretty permalinks off.
(async () => {
    const id = () => elementorCommon.helpers.getUniqueId();
    const px = (n) => ({ unit: 'px', size: n, sizes: [] });
    const box = (t, r, b, l) => ({ unit: 'px', top: String(t), right: String(r), bottom: String(b), left: String(l), isLinked: false });
    const gap = (n) => ({ unit: 'px', column: String(n), row: String(n), isLinked: true, size: n });
    const WHITE = '#FFFFFF', BODY = 'rgb(205, 231, 228)', ORANGE = 'rgb(239, 115, 48)', LINE = 'rgba(158, 207, 201, 0.25)';
    const card = { background_background: 'classic', background_color: 'rgba(255, 255, 255, 0.04)', border_border: 'solid', border_width: box(1, 1, 1, 1), border_color: LINE, border_radius: box(16, 16, 16, 16) };

    const con = (settings, elements) => ({ id: id(), elType: 'container', isInner: false, settings: Object.assign({ content_width: 'full', flex_direction: 'column', flex_gap: gap(0), padding: box(0, 0, 0, 0) }, settings), elements });
    const grid = (cols, tab, mob, settings, elements) => con(Object.assign({ container_type: 'grid', grid_columns_grid: { unit: 'fr', size: cols, sizes: [] }, grid_columns_grid_tablet: { unit: 'fr', size: tab, sizes: [] }, grid_columns_grid_mobile: { unit: 'fr', size: mob, sizes: [] }, grid_rows_grid: { unit: 'fr', size: 1, sizes: [] }, grid_gaps: gap(20), grid_auto_flow: 'row' }, settings), elements);
    const widget = (type, settings) => ({ id: id(), elType: 'widget', widgetType: type, settings, elements: [] });
    const heading = (title, tag, size, sizeMob, extra) => widget('heading', Object.assign({ title, header_size: tag, title_color: WHITE, typography_typography: 'custom', typography_font_size: px(size), typography_font_size_mobile: px(sizeMob), typography_font_weight: tag === 'h1' ? '900' : '800', typography_line_height: { unit: 'em', size: 1.2, sizes: [] }, _margin: box(0, 0, 0, 0) }, extra || {}));
    const text = (html, extra) => widget('text-editor', Object.assign({ editor: html, text_color: BODY, typography_typography: 'custom', typography_font_size: px(17), typography_font_size_mobile: px(16), typography_line_height: { unit: 'em', size: 1.65, sizes: [] } }, extra || {}));
    const icon = (value) => widget('icon', { selected_icon: { value, library: 'fa-solid' }, view: 'stacked', shape: 'circle', primary_color: 'rgba(239, 115, 48, 0.15)', secondary_color: ORANGE, size: px(26), icon_padding: px(16), align: 'center', _margin: box(0, 0, 16, 0) });
    const sectionTitle = (title, lead) => con({ flex_align_items: 'center', margin: box(72, 0, 28, 0), flex_gap: gap(10) }, [heading(title, 'h2', 32, 26, { align: 'center' })].concat(lead ? [text('<p>' + lead + '</p>', { align: 'center' })] : []));

    const linkCard = (iconValue, title, url, label) => con(Object.assign({}, card, { html_tag: 'a', link: { url, is_external: '', nofollow: '' }, flex_align_items: 'center', flex_justify_content: 'flex-start', padding: box(32, 24, 28, 24), css_classes: 'sta-about-card' }), [
        icon(iconValue),
        heading(title, 'h3', 21, 19, { align: 'center' }),
        widget('heading', { title: label + ' →', header_size: 'span', title_color: ORANGE, align: 'center', typography_typography: 'custom', typography_font_size: px(15), typography_font_weight: '700', _margin: box(12, 0, 0, 0) }),
    ]);
    const committee = (iconValue, title) => con(Object.assign({}, card, { flex_align_items: 'center', flex_justify_content: 'flex-start', padding: box(28, 16, 24, 16) }), [
        icon(iconValue),
        heading(title, 'h3', 17, 16, { align: 'center' }),
    ]);
    const campus = (title, address, mapHtml) => con(Object.assign({}, card, { padding: box(24, 24, 24, 24), flex_gap: gap(12) }), [
        heading(title, 'h3', 22, 20),
        widget('icon-list', { icon_list: [{ _id: id(), text: address, selected_icon: { value: 'fas fa-map-marker-alt', library: 'fa-solid' } }], icon_color: ORANGE, text_color: BODY, icon_size: px(15), icon_typography_typography: 'custom', icon_typography_font_size: px(16) }),
        widget('html', { html: mapHtml }),
    ]);

    // Map HTML: keep the official OpenStreetMap embed + "Vis større kart" link, make it responsive and rounded.
    const map = (html) => {
        const d = new DOMParser().parseFromString(html, 'text/html');
        const f = d.querySelector('iframe'), a = d.querySelector('a');
        return '<div class="sta-map"><iframe src="' + f.getAttribute('src') + '" title="Kart" loading="lazy" style="display:block;width:100%;height:300px;border:0;border-radius:12px;"></iframe>'
            + (a ? '<p style="margin:12px 0 0;"><a href="' + a.getAttribute('href') + '" target="_blank" rel="noopener" style="color:' + ORANGE + ';font-weight:700;text-decoration:none;">Vis større kart →</a></p>' : '') + '</div>';
    };

    const om = window.__om;
    const root = con({ content_width: 'boxed', boxed_width: px(1232), padding: box(56, 24, 80, 24), padding_mobile: box(40, 16, 64, 16) }, [
        // Hero
        con({ width: { unit: 'px', size: 800, sizes: [] }, width_mobile: { unit: '%', size: 100, sizes: [] }, _flex_align_self: 'center', flex_align_items: 'center', html_tag: 'header' }, [
            widget('heading', { title: 'Om oss', header_size: 'span', _element_width: 'auto', _flex_align_self: 'center', align: 'center', title_color: WHITE, typography_typography: 'custom', typography_font_size: px(12.8), typography_font_weight: '700', typography_letter_spacing: px(0.6), typography_text_transform: 'uppercase', _padding: box(5.6, 13.6, 5.6, 13.6), _background_background: 'classic', _background_color: ORANGE, _border_radius: box(9999, 9999, 9999, 9999) }),
            heading('Hvem er STA?', 'h1', 56, 32, { align: 'center', _margin: box(20, 0, 0, 0) }),
            text('<p>Visste du at alle studenter ved Universitetet i Agder er medlem av Studentorganisasjonen i Agder (STA)?</p>', { align: 'center', typography_font_size: px(19.2), typography_font_size_mobile: px(17), _margin: box(20, 0, 0, 0) }),
        ]),
        // Photo + text
        con(Object.assign({}, card, { margin: box(48, 0, 0, 0), padding: box(0, 0, 0, 0), flex_direction: 'row', flex_direction_mobile: 'column', flex_align_items: 'stretch', overflow: 'hidden' }), [
            con({ width: { unit: '%', size: 45, sizes: [] }, width_tablet: { unit: '%', size: 40, sizes: [] }, width_mobile: { unit: '%', size: 100, sizes: [] } }, [
                widget('image', { image: om.img, image_size: 'large', width: { unit: '%', size: 100, sizes: [] }, height: { unit: '%', size: 100, sizes: [] }, height_mobile: { unit: 'px', size: 260, sizes: [] }, object_fit: 'cover', object_fit_mobile: 'cover', _element_width: 'inherit', _flex_size: 'grow', css_classes: 'sta-about-photo' }),
            ]),
            con({ width: { unit: '%', size: 55, sizes: [] }, width_tablet: { unit: '%', size: 60, sizes: [] }, width_mobile: { unit: '%', size: 100, sizes: [] }, padding: box(40, 40, 40, 40), padding_mobile: box(24, 20, 24, 20), flex_justify_content: 'center', flex_gap: gap(16) }, [
                text('<p>Medlemskapet gjelder også for de fleste studentaktiviteter og linjeforeninger. På denne måten binder STA alle studentene sammen, med mål om å skape en best mulig studenthverdag.</p>'),
                text('<p>STA arbeider for studentenes faglige, sosiale og økonomiske interesser. Dette betyr for eksempel at undervisningen skal være av høy kvalitet. Det betyr også at ingen studenter på UiA skal føle på utenforskap eller mobbing, og at STA jobber for at det skal være mulig å leve som heltidsstudent på midlene fra Lånekassen. I tillegg ivaretar STA studentdemokratiet på UiA. STA-styret er valgt av Studentparlamentet, og har ansvar for å gjennomføre politikken som parlamentet har vedtatt.</p>'),
            ]),
        ]),
        // Call to action
        con({ margin: box(24, 0, 0, 0), padding: box(28, 32, 28, 32), padding_mobile: box(24, 20, 24, 20), flex_direction: 'row', flex_direction_mobile: 'column', flex_align_items: 'center', flex_align_items_mobile: 'flex-start', flex_justify_content: 'space-between', flex_gap: gap(24), background_background: 'classic', background_color: 'rgba(239, 115, 48, 0.10)', border_border: 'solid', border_width: box(1, 1, 1, 1), border_color: 'rgba(239, 115, 48, 0.45)', border_radius: box(16, 16, 16, 16) }, [
            text('<p><strong style="color:#fff;">Er du misfornøyd med åpningstidene i kantina, eller at en obligatorisk studietur koster penger?</strong> Ta kontakt med oss, så skal vi kjempe for studentenes sak!</p>', { _flex_size: 'grow', typography_font_size: px(18) }),
            widget('button', { text: 'Kontakt oss', link: { url: 'mailto:sta@uia.no', is_external: '', nofollow: '' }, selected_icon: { value: 'fas fa-envelope', library: 'fa-solid' }, icon_indent: px(8), background_color: ORANGE, button_text_color: WHITE, border_radius: box(9999, 9999, 9999, 9999), text_padding: box(14, 28, 14, 28), typography_typography: 'custom', typography_font_size: px(16), typography_font_weight: '700', _flex_size: 'none' }),
        ]),
        // Studentdemokratiet
        sectionTitle('Studentdemokratiet', ''),
        grid(3, 3, 1, {}, [
            linkCard('fas fa-user', 'STA-styret', '/reliant/sta-styret/', 'Møt styret'),
            linkCard('fas fa-users', 'Studentparlamentet', '/reliant/studentparlamentet/', 'Les mer'),
            linkCard('fas fa-vote-yea', 'Valgkomiteen', '/reliant/valgkomiteen/', 'Les mer'),
        ]),
        // Utvalg
        sectionTitle('Utvalg', ''),
        grid(5, 3, 2, {}, [
            committee('fas fa-leaf', 'Bærekraft- og miljøpolitisk utvalg'),
            committee('fas fa-globe', 'Internasjonalt utvalg'),
            committee('fas fa-microscope', 'Fag- og forskningspolitisk utvalg'),
            committee('fas fa-chalkboard-teacher', 'Læringsmiljøpolitisk utvalg'),
            committee('fas fa-camera-retro', 'Kommunikasjonsutvalget'),
        ]),
        con({ flex_align_items: 'center', margin: box(24, 0, 0, 0) }, [
            widget('button', { text: 'Les om alle råd, styrer og utvalg', link: { url: '/reliant/rad-styrer-og-utvalg/', is_external: '', nofollow: '' }, selected_icon: { value: 'fas fa-arrow-right', library: 'fa-solid' }, icon_align: 'row-reverse', icon_indent: px(8), background_color: 'rgba(0,0,0,0)', button_text_color: WHITE, border_border: 'solid', border_width: box(1, 1, 1, 1), border_color: LINE, border_radius: box(9999, 9999, 9999, 9999), text_padding: box(12, 24, 12, 24), typography_typography: 'custom', typography_font_size: px(15), typography_font_weight: '700' }),
        ]),
        // Campus
        sectionTitle('Her finner du oss', ''),
        grid(2, 2, 1, {}, [
            campus('Campus Kristiansand', 'Universitetsveien 24, 4630 Kristiansand S', map(om.maps[0])),
            campus('Campus Grimstad', 'Jon Lilletuns vei 9, 4879 Grimstad', map(om.maps[1])),
        ]),
    ]);

    const preview = elementor.getPreviewContainer();
    $e.run('document/elements/delete', { containers: preview.children.slice() });
    $e.run('document/elements/create', { container: preview, model: root, options: { at: 0 } });
    $e.run('document/elements/settings', { container: elementor.settings.page.getEditedView().getContainer(), settings: { template: 'elementor_header_footer' } });
    return 'built ' + preview.children.length;
})();
