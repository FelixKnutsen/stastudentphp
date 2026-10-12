/**
 * Accessible Navigation, Dropdown, Search Modal & Drawer for STA Theme
 */

document.addEventListener('DOMContentLoaded', function () {
    // -------------------------------------------------------------------------
    // 1. Desktop Dropdowns (with 150ms hover buffer bridge fix)
    // -------------------------------------------------------------------------
    const dropdownContainers = document.querySelectorAll('.sta-dropdown-container');

    dropdownContainers.forEach(function (container) {
        const toggleBtn = container.querySelector('.sta-dropdown-toggle');
        const menu = container.querySelector('.sta-dropdown-menu');
        let closeTimeout = null;
        let justClosedViaEscape = false;

        if (!toggleBtn || !menu) return;

        function openDropdown() {
            if (justClosedViaEscape) return;
            if (closeTimeout) {
                clearTimeout(closeTimeout);
                closeTimeout = null;
            }
            container.classList.add('is-open');
            toggleBtn.setAttribute('aria-expanded', 'true');
        }

        function scheduleClose() {
            justClosedViaEscape = false;
            if (closeTimeout) clearTimeout(closeTimeout);
            closeTimeout = setTimeout(function () {
                container.classList.remove('is-open');
                toggleBtn.setAttribute('aria-expanded', 'false');
            }, 150);
        }

        container.addEventListener('mouseenter', openDropdown);
        container.addEventListener('mouseleave', scheduleClose);

        toggleBtn.addEventListener('click', function (e) {
            e.preventDefault();
            justClosedViaEscape = false;
            const isOpen = container.classList.contains('is-open');
            if (isOpen) {
                container.classList.remove('is-open');
                toggleBtn.setAttribute('aria-expanded', 'false');
            } else {
                openDropdown();
            }
        });

        container.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                e.preventDefault();
                justClosedViaEscape = true;
                container.classList.remove('is-open');
                toggleBtn.setAttribute('aria-expanded', 'false');
                toggleBtn.focus();
            } else if ((e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') && document.activeElement === toggleBtn) {
                e.preventDefault();
                justClosedViaEscape = false;
                openDropdown();
                const firstLink = menu.querySelector('a');
                if (firstLink && e.key === 'ArrowDown') {
                    firstLink.focus();
                }
            }
        });
    });

    document.addEventListener('click', function (e) {
        dropdownContainers.forEach(function (container) {
            if (!container.contains(e.target)) {
                container.classList.remove('is-open');
                const toggle = container.querySelector('.sta-dropdown-toggle');
                if (toggle) toggle.setAttribute('aria-expanded', 'false');
            }
        });
    });

    // -------------------------------------------------------------------------
    // 2. Search Modal
    // -------------------------------------------------------------------------
    const searchTrigger = document.getElementById('sta-search-trigger');
    const searchModal = document.getElementById('sta-search-modal');
    const searchClose = document.getElementById('sta-modal-close-btn');
    const searchInput = document.getElementById('sta-modal-input');

    function openSearchModal() {
        if (!searchModal) return;
        searchModal.classList.add('is-active');
        document.body.classList.add('sta-no-scroll');
        if (searchInput) {
            setTimeout(function () { searchInput.focus(); }, 100);
        }
    }

    function closeSearchModal() {
        if (!searchModal) return;
        searchModal.classList.remove('is-active');
        document.body.classList.remove('sta-no-scroll');
        if (searchTrigger) searchTrigger.focus();
    }

    if (searchTrigger) searchTrigger.addEventListener('click', openSearchModal);
    if (searchClose) searchClose.addEventListener('click', closeSearchModal);

    if (searchModal) {
        searchModal.addEventListener('click', function (e) {
            if (e.target === searchModal) {
                closeSearchModal();
            }
        });
    }

    // -------------------------------------------------------------------------
    // 3. Mobile Navigation Drawer
    // -------------------------------------------------------------------------
    const mobileTrigger = document.getElementById('sta-mobile-menu-trigger');
    const mobileDrawer = document.getElementById('sta-mobile-drawer');
    const mobileClose = document.getElementById('sta-mobile-drawer-close');
    const mobileBackdrop = document.getElementById('sta-mobile-backdrop');

    function openMobileDrawer() {
        if (!mobileDrawer) return;
        mobileDrawer.classList.add('is-active');
        if (mobileBackdrop) mobileBackdrop.classList.add('is-active');
        document.body.classList.add('sta-no-scroll');
        if (mobileTrigger) mobileTrigger.setAttribute('aria-expanded', 'true');
        if (mobileClose) {
            setTimeout(function () { mobileClose.focus(); }, 50);
        }
    }

    function closeMobileDrawer() {
        if (!mobileDrawer) return;
        mobileDrawer.classList.remove('is-active');
        if (mobileBackdrop) mobileBackdrop.classList.remove('is-active');
        document.body.classList.remove('sta-no-scroll');
        if (mobileTrigger) {
            mobileTrigger.setAttribute('aria-expanded', 'false');
            mobileTrigger.focus();
        }
    }

    function toggleMobileDrawer() {
        if (!mobileDrawer) return;
        if (mobileDrawer.classList.contains('is-active')) {
            closeMobileDrawer();
        } else {
            openMobileDrawer();
        }
    }

    if (mobileTrigger) mobileTrigger.addEventListener('click', toggleMobileDrawer);
    if (mobileClose) mobileClose.addEventListener('click', closeMobileDrawer);
    if (mobileBackdrop) mobileBackdrop.addEventListener('click', closeMobileDrawer);

    // Close on link click
    if (mobileDrawer) {
        const navLinks = mobileDrawer.querySelectorAll('.sta-mobile-nav-link:not(.sta-mobile-accordion-toggle), .sta-mobile-sublink');
        navLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                closeMobileDrawer();
            });
        });
    }

    // Auto-close on viewport resize to desktop
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 1024 && mobileDrawer && mobileDrawer.classList.contains('is-active')) {
            closeMobileDrawer();
        }
    });

    // Mobile Accordion Toggle
    const mobileAccordions = document.querySelectorAll('.sta-mobile-accordion-toggle');
    mobileAccordions.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const subnav = btn.nextElementSibling;
            if (!subnav) return;
            const isExpanded = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', !isExpanded);
            subnav.style.display = isExpanded ? 'none' : 'flex';
            const chevron = btn.querySelector('.sta-chevron');
            if (chevron) {
                chevron.style.transform = isExpanded ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        });
    });

    // Global Escape Handler
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            if (searchModal && searchModal.classList.contains('is-active')) {
                closeSearchModal();
            } else if (mobileDrawer && mobileDrawer.classList.contains('is-active')) {
                closeMobileDrawer();
            }
        }
    });
});
