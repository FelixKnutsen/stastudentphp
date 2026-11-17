/**
 * Client-Side Instant Filter & Search for Directories (Associations & Activities)
 */

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('.sta-directory-search-input');
    const filterButtons = document.querySelectorAll('.sta-filter-btn');
    const cards = document.querySelectorAll('.sta-directory-card');
    const countDisplay = document.querySelector('.sta-directory-count');
    const emptyState = document.querySelector('.sta-directory-empty');

    if (!cards.length) return;

    // Search uses the card text as it is before the «Les mer» buttons are added.
    cards.forEach(function (card) {
        card.setAttribute('data-search', (card.textContent || '').replace(/\s+/g, ' ').toLowerCase());
    });

    /* Long descriptions: show five lines with a «Les mer» button; short ones are shown in full. */
    const grid = document.querySelector('.sta-directory-grid');
    const moreLabel = (grid && grid.getAttribute('data-more')) || 'Les mer';
    const lessLabel = (grid && grid.getAttribute('data-less')) || 'Vis mindre';
    const descs = [];
    document.querySelectorAll('.sta-dir-desc').forEach(function (p, i) {
        p.id = p.id || 'sta-dir-desc-' + (i + 1);
        p.classList.add('is-clamped');
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'sta-desc-toggle';
        btn.hidden = true;
        btn.textContent = moreLabel;
        btn.setAttribute('aria-expanded', 'false');
        btn.setAttribute('aria-controls', p.id);
        btn.addEventListener('click', function () {
            const open = btn.getAttribute('aria-expanded') === 'true';
            p.classList.toggle('is-clamped', open);
            btn.setAttribute('aria-expanded', open ? 'false' : 'true');
            btn.textContent = open ? moreLabel : lessLabel;
        });
        p.insertAdjacentElement('afterend', btn);
        descs.push({ p: p, btn: btn });
    });
    function measureDescs() {
        descs.forEach(function (d) {
            if (d.btn.getAttribute('aria-expanded') === 'true' || !d.p.offsetParent) return;
            d.btn.hidden = !(d.p.scrollHeight > d.p.clientHeight + 1);
        });
    }
    measureDescs();
    let resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(measureDescs, 150);
    });
    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(measureDescs);
    }

    let currentCategory = 'all';
    let currentSearch = '';
    const isEn = document.documentElement.lang && document.documentElement.lang.startsWith('en');

    function filterCards() {
        let visibleCount = 0;
        const searchNormalized = currentSearch.trim().toLowerCase();

        cards.forEach(function (card) {
            const cardCategory = card.getAttribute('data-category') || '';
            const cardFullText = card.getAttribute('data-search') || '';

            const matchesCategory = currentCategory === 'all' || cardCategory === currentCategory;
            const matchesSearch = !searchNormalized || cardFullText.includes(searchNormalized);

            if (matchesCategory && matchesSearch) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (countDisplay) {
            const total = cards.length;
            const unit = countDisplay.getAttribute('data-unit') || (isEn ? 'entries' : 'oppføringer');
            if (isEn) {
                countDisplay.textContent = `Showing ${visibleCount} of ${total} ${unit}`;
            } else {
                countDisplay.textContent = `Viser ${visibleCount} av ${total} ${unit}`;
            }
        }

        if (emptyState) {
            emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
        }
        measureDescs();
    }

    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            currentSearch = e.target.value;
            filterCards();
        });
    }

    filterButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            filterButtons.forEach(function (b) {
                b.classList.remove('is-active');
                b.setAttribute('aria-pressed', 'false');
            });
            btn.classList.add('is-active');
            btn.setAttribute('aria-pressed', 'true');
            currentCategory = btn.getAttribute('data-filter') || 'all';
            filterCards();
        });
    });
});
