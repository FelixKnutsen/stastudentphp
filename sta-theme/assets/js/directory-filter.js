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

    let currentCategory = 'all';
    let currentSearch = '';
    const isEn = document.documentElement.lang && document.documentElement.lang.startsWith('en');

    function filterCards() {
        let visibleCount = 0;
        const searchNormalized = currentSearch.trim().toLowerCase();

        cards.forEach(function (card) {
            const cardCategory = card.getAttribute('data-category') || '';
            const cardFullText = (card.textContent || '').toLowerCase();

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
