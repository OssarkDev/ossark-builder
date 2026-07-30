/**
 * Draggable block inspector sidebar for the WP block editor.
 *
 * The sidebar (.interface-interface-skeleton__sidebar) lives OUTSIDE
 * the block iframe. editor.css draws a 6px grab handle on its left
 * edge via ::before; this script listens for mousedown on that zone
 * and resizes by updating --ossark-inspector-width on <body>.
 *
 * Width is persisted to localStorage so it survives reloads.
 */
(function () {
    'use strict';

    const STORAGE_KEY = 'ossark_inspector_width';
    const MIN_WIDTH   = 240;
    const HANDLE_ZONE = 6; // px from left edge that counts as the handle

    // Apply any saved width on load
    const saved = parseInt(localStorage.getItem(STORAGE_KEY), 10);
    if (saved && saved >= MIN_WIDTH) {
        document.body.style.setProperty('--ossark-inspector-width', saved + 'px');
    }

    let dragging   = false;
    let startX     = 0;
    let startWidth = 0;

    function getSidebar() {
        return document.querySelector('.interface-interface-skeleton__sidebar');
    }

    function getMaxWidth() {
        // 50% of viewport, matching the CSS cap
        return Math.floor(window.innerWidth * 0.5);
    }

    document.addEventListener('mousedown', function (e) {
        const sidebar = getSidebar();
        if (!sidebar) return;

        // Only care about clicks on the left-edge handle zone
        const rect = sidebar.getBoundingClientRect();
        if (e.clientX < rect.left || e.clientX > rect.left + HANDLE_ZONE) return;

        dragging   = true;
        startX     = e.clientX;
        startWidth = rect.width;
        document.body.classList.add('ossark-resizing');
        e.preventDefault();
    });

    document.addEventListener('mousemove', function (e) {
        if (!dragging) return;

        // Sidebar grows leftward — moving mouse left increases width
        const delta    = startX - e.clientX;
        let   newWidth = Math.round(startWidth + delta);
        const maxWidth = getMaxWidth();

        if (newWidth < MIN_WIDTH) newWidth = MIN_WIDTH;
        if (newWidth > maxWidth) newWidth = maxWidth;

        document.body.style.setProperty('--ossark-inspector-width', newWidth + 'px');
    });

    document.addEventListener('mouseup', function () {
        if (!dragging) return;
        dragging = false;
        document.body.classList.remove('ossark-resizing');

        // Persist current width
        const current = document.body.style.getPropertyValue('--ossark-inspector-width');
        const px      = parseInt(current, 10);
        if (px && px >= MIN_WIDTH) {
            localStorage.setItem(STORAGE_KEY, String(px));
        }
    });

    // Also cancel if mouse leaves the window
    document.addEventListener('mouseleave', function () {
        if (dragging) {
            dragging = false;
            document.body.classList.remove('ossark-resizing');
        }
    });
})();
