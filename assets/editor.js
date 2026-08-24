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

/**
 * Live template parts preview in Gutenberg canvas
 */
(function () {
    'use strict';

    function initTemplateParts() {
        const config = window.ossarkTemplatePartsConfig;
        if (!config) return;

        let currentData = config.initialData || { has_parts: false, before_html: '', after_html: '' };
        let isFetching = false;
        let observerAttached = false;

        function getCanvasDocument() {
            const iframe = document.querySelector('iframe[name="editor-canvas"]') ||
                           document.querySelector('.edit-post-visual-editor iframe');
            if (iframe) {
                try {
                    return iframe.contentDocument || iframe.contentWindow?.document || null;
                } catch (e) {
                    return null;
                }
            }
            if (document.querySelector('.editor-styles-wrapper')) {
                return document;
            }
            return null;
        }

        function renderParts() {
            const doc = getCanvasDocument();
            if (!doc) return false;

            const blockList = doc.querySelector('.block-editor-block-list__layout') ||
                              doc.querySelector('.wp-block-post-content');
            if (!blockList || !blockList.parentNode) return false;

            const beforeHtml = currentData.before_html || '';
            const afterHtml  = currentData.after_html || '';

            // 1. Before container
            let beforeContainer = doc.getElementById('ossark-editor-template-parts-before');
            if (beforeHtml.trim()) {
                if (!beforeContainer) {
                    beforeContainer = doc.createElement('div');
                    beforeContainer.id = 'ossark-editor-template-parts-before';
                    beforeContainer.className = 'ossark-editor-template-parts ossark-editor-template-parts--before';
                    blockList.parentNode.insertBefore(beforeContainer, blockList);
                }
                if (beforeContainer.innerHTML !== beforeHtml) {
                    beforeContainer.innerHTML = beforeHtml;
                }
            } else if (beforeContainer) {
                beforeContainer.remove();
            }

            // 2. After container
            let afterContainer = doc.getElementById('ossark-editor-template-parts-after');
            if (afterHtml.trim()) {
                if (!afterContainer) {
                    afterContainer = doc.createElement('div');
                    afterContainer.id = 'ossark-editor-template-parts-after';
                    afterContainer.className = 'ossark-editor-template-parts ossark-editor-template-parts--after';
                    if (blockList.nextSibling) {
                        blockList.parentNode.insertBefore(afterContainer, blockList.nextSibling);
                    } else {
                        blockList.parentNode.appendChild(afterContainer);
                    }
                }
                if (afterContainer.innerHTML !== afterHtml) {
                    afterContainer.innerHTML = afterHtml;
                }
            } else if (afterContainer) {
                afterContainer.remove();
            }

            return true;
        }

        function fetchParts(templateSlug) {
            if (isFetching || !config.ajaxUrl || !config.nonce) return;
            isFetching = true;

            const formData = new FormData();
            formData.append('action', 'ossark_get_editor_template_parts');
            formData.append('nonce', config.nonce);
            formData.append('post_id', String(config.postId || 0));
            formData.append('template', templateSlug || '');

            fetch(config.ajaxUrl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin',
            })
                .then(function (res) { return res.json(); })
                .then(function (response) {
                    isFetching = false;
                    if (response && response.success && response.data) {
                        currentData = response.data;
                        renderParts();
                    }
                })
                .catch(function () {
                    isFetching = false;
                });
        }

        function setupCanvasObserver() {
            const doc = getCanvasDocument();
            if (!doc || !doc.body) return false;

            const canvasObserver = new MutationObserver(function () {
                const blockList = doc.querySelector('.block-editor-block-list__layout') ||
                                  doc.querySelector('.wp-block-post-content');
                if (!blockList) return;

                const beforeNeeded = Boolean(currentData.before_html && currentData.before_html.trim());
                const afterNeeded  = Boolean(currentData.after_html && currentData.after_html.trim());

                const beforeExists = Boolean(doc.getElementById('ossark-editor-template-parts-before'));
                const afterExists  = Boolean(doc.getElementById('ossark-editor-template-parts-after'));

                if ((beforeNeeded && !beforeExists) || (afterNeeded && !afterExists)) {
                    renderParts();
                }
            });

            canvasObserver.observe(doc.body, { childList: true, subtree: true });
            return true;
        }

        function start() {
            renderParts();

            const mainObserver = new MutationObserver(function () {
                renderParts();
                if (!observerAttached && setupCanvasObserver()) {
                    observerAttached = true;
                }
            });

            mainObserver.observe(document.body, { childList: true, subtree: true });
            if (setupCanvasObserver()) {
                observerAttached = true;
            }

            if (window.wp && window.wp.data && window.wp.data.subscribe) {
                let lastTemplate = window.wp.data.select('core/editor')?.getEditedPostAttribute?.('template');
                let wasSaving    = false;

                window.wp.data.subscribe(function () {
                    const editorSelect = window.wp.data.select('core/editor');
                    if (!editorSelect) return;

                    const newTemplate = editorSelect.getEditedPostAttribute?.('template');
                    if (newTemplate !== undefined && newTemplate !== lastTemplate) {
                        lastTemplate = newTemplate;
                        fetchParts(newTemplate);
                    }

                    const isSaving = editorSelect.isSavingPost?.();
                    const isAutosaving = editorSelect.isAutosavingPost?.();
                    if (wasSaving && !isSaving && !isAutosaving) {
                        fetchParts(lastTemplate || '');
                    }
                    wasSaving = Boolean(isSaving && !isAutosaving);
                });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', start);
        } else {
            start();
        }
    }

    initTemplateParts();
})();
