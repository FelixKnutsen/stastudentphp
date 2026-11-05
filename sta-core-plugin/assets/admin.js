/* STA-innhold: edit form helpers, delete confirmation and drag-and-drop ordering. */
(function ($) {
    'use strict';

    /* Media library picker (images store a URL, files store id + URL). */
    $(document).on('click', '.sta-cms-media-pick', function (e) {
        e.preventDefault();
        var box = $(this).closest('.sta-cms-media');
        var isImage = box.data('kind') === 'image';
        var frame = wp.media({
            title: isImage ? 'Velg bilde' : 'Velg fil',
            button: { text: 'Bruk denne' },
            library: isImage ? { type: 'image' } : {},
            multiple: false
        });
        frame.on('select', function () {
            var file = frame.state().get('selection').first().toJSON();
            box.find('.sta-cms-media-url').val(file.url).trigger('change');
            box.find('.sta-cms-media-id').val(file.id);
            box.find('.sta-cms-media-preview').html(isImage
                ? $('<img alt="">').attr('src', (file.sizes && file.sizes.medium ? file.sizes.medium.url : file.url))
                : $('<a target="_blank" rel="noopener">').attr('href', file.url).text(file.filename + ' ↗'));
        });
        frame.open();
    });
    $(document).on('click', '.sta-cms-media-clear', function (e) {
        e.preventDefault();
        var box = $(this).closest('.sta-cms-media');
        box.find('.sta-cms-media-url').val('');
        box.find('.sta-cms-media-id').val('0');
        box.find('.sta-cms-media-preview').empty();
    });

    /* Members (person + role) rows. */
    function initMembers() {
        $('.sta-cms-members tbody').sortable({ handle: '.sta-cms-handle', axis: 'y' });
    }
    $(document).on('click', '.sta-cms-member-add', function (e) {
        e.preventDefault();
        var wrap = $(this).closest('td');
        wrap.find('.sta-cms-members tbody').append(wrap.find('.sta-cms-member-template').html());
        wrap.find('.sta-cms-members tbody tr:last select').trigger('focus');
    });
    $(document).on('click', '.sta-cms-member-remove', function (e) {
        e.preventDefault();
        var row = $(this).closest('tr');
        var name = row.find('select option:selected').text();
        if (!row.find('select').val() || window.confirm('Fjerne ' + name + ' fra denne gruppen? Personen slettes ikke.')) {
            row.remove();
        }
    });

    /* Required fields: checked before «Publiser» (the server checks again). */
    function missingFields() {
        var missing = [];
        var title = $('#title');
        if (title.length && !$.trim(title.val())) {
            missing.push({ el: $('#titlewrap'), label: title.attr('placeholder') || 'Tittel' });
        }
        $('.sta-cms-row[data-required="1"]').each(function () {
            var row = $(this);
            var input = row.find('input[type!=hidden], select, textarea').first();
            var filled = row.hasClass('sta-cms-type-file') ? $.trim(row.find('.sta-cms-media-url').val()) : $.trim(input.val());
            if (!filled) {
                missing.push({ el: row, label: row.data('label') });
            }
        });
        $('.sta-cms-terms').each(function () {
            var box = $(this);
            if (box.data('required') && !box.find('input[type=radio]:checked').length) {
                missing.push({ el: box.closest('.postbox'), label: box.data('label') });
            }
        });
        var thumb = $('#sta-cms-thumb-required');
        if (thumb.length && !(parseInt($('#_thumbnail_id').val(), 10) > 0)) {
            missing.push({ el: $('#postimagediv'), label: thumb.data('label') });
        }
        return missing;
    }
    $(document).on('click', '#publish', function (e) {
        if (!$('.sta-cms-form').length) {
            return;
        }
        $('.sta-cms-missing').removeClass('sta-cms-missing');
        $('#sta-cms-error').remove();
        var missing = missingFields();
        if (!missing.length) {
            return;
        }
        e.preventDefault();
        missing.forEach(function (m) { m.el.addClass('sta-cms-missing'); });
        $('<div id="sta-cms-error" class="notice notice-error"><p></p></div>')
            .find('p').text('Fyll ut før du publiserer: ' + missing.map(function (m) { return m.label; }).join(', ') + '. (Du kan lagre som utkast uten å fylle ut alt.)').end()
            .insertAfter('.wrap h1.wp-heading-inline, .wrap > h1').first();
        $('html, body').animate({ scrollTop: missing[0].el.offset().top - 80 }, 200);
    });

    /* Delete confirmation. */
    $(document).on('click', 'a.submitdelete, .row-actions .trash a', function (e) {
        var row = $(this).closest('tr');
        var title = row.length ? row.find('.row-title').text() : $('#title').val();
        if (!window.confirm('Flytte «' + $.trim(title) + '» til papirkurven?\n\nDen forsvinner fra nettsiden med en gang, men kan gjenopprettes fra Papirkurv.')) {
            e.preventDefault();
        }
    });
    $(document).on('click', '.row-actions .delete a', function (e) {
        if (!window.confirm('Slette permanent? Dette kan ikke angres.')) {
            e.preventDefault();
        }
    });
    $(document).on('submit', '#posts-filter', function (e) {
        var action = $('#bulk-action-selector-top').val() !== '-1' ? $('#bulk-action-selector-top').val() : $('#bulk-action-selector-bottom').val();
        var count = $('#the-list input[name="post[]"]:checked').length;
        if (action === 'trash' && count && !window.confirm('Flytte ' + count + ' oppføring(er) til papirkurven? De forsvinner fra nettsiden, men kan gjenopprettes.')) {
            e.preventDefault();
        }
        if (action === 'delete' && count && !window.confirm('Slette ' + count + ' oppføring(er) permanent? Dette kan ikke angres.')) {
            e.preventDefault();
        }
    });

    /* Drag-and-drop ordering on list screens. */
    function initSorting() {
        var list = $('#the-list');
        if (!staCms.sortable || !list.find('.sta-cms-drag').length) {
            return;
        }
        $('<p class="sta-cms-sort-hint">Dra i ☰ for å endre rekkefølgen på nettsiden. Endringen lagres automatisk.</p>').insertBefore('#posts-filter');
        list.sortable({
            handle: '.sta-cms-drag',
            axis: 'y',
            helper: function (e, tr) {
                var cells = tr.children();
                var clone = tr.clone();
                clone.children().each(function (i) { $(this).width(cells.eq(i).width()); });
                return clone;
            },
            update: function () {
                var ids = list.find('input[name="post[]"]').map(function () { return this.value; }).get();
                var hint = $('.sta-cms-sort-hint').text('Lagrer rekkefølgen …');
                $.post(staCms.ajax, { action: 'sta_cms_reorder', nonce: staCms.nonce, ids: ids, offset: staCms.offset })
                    .done(function (r) { hint.text(r && r.success ? 'Rekkefølgen er lagret og vises nå på nettsiden.' : 'Kunne ikke lagre rekkefølgen: ' + ((r && r.data) || 'ukjent feil')); })
                    .fail(function () { hint.text('Kunne ikke lagre rekkefølgen. Last siden på nytt og prøv igjen.'); });
            }
        });
    }

    $(function () {
        initMembers();
        initSorting();
    });
}(jQuery));
