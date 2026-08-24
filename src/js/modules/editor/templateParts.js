/**
 * Editor Template Parts Preview Module
 *
 * Injects and manages the live rendering of template parts (`get_part(...)`)
 * inside the Gutenberg editor canvas for single templates and page templates.
 */

export function initEditorTemplateParts() {
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

        // Inject / update Top (before content) template parts
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

        // Inject / update Bottom (after content) template parts
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

    function fetchParts(templateSlug = '') {
        if (isFetching || !config.ajaxUrl || !config.nonce) return;
        isFetching = true;

        const formData = new FormData();
        formData.append('action', 'ossark_get_editor_template_parts');
        formData.append('nonce', config.nonce);
        formData.append('post_id', String(config.postId || 0));
        formData.append('template', templateSlug);

        fetch(config.ajaxUrl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
        })
            .then(res => res.json())
            .then(response => {
                isFetching = false;
                if (response && response.success && response.data) {
                    currentData = response.data;
                    renderParts();
                }
            })
            .catch(() => {
                isFetching = false;
            });
    }

    function setupCanvasObserver() {
        const doc = getCanvasDocument();
        if (!doc || !doc.body) return false;

        const canvasObserver = new MutationObserver(() => {
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

        // Observe main document for iframe creation
        const mainObserver = new MutationObserver(() => {
            renderParts();
            if (!observerAttached && setupCanvasObserver()) {
                observerAttached = true;
            }
        });

        mainObserver.observe(document.body, { childList: true, subtree: true });
        if (setupCanvasObserver()) {
            observerAttached = true;
        }

        // Gutenberg store subscriptions (if wp.data is available)
        if (window.wp && window.wp.data && window.wp.data.subscribe) {
            let lastTemplate = window.wp.data.select('core/editor')?.getEditedPostAttribute?.('template');
            let wasSaving    = false;

            window.wp.data.subscribe(() => {
                const editorSelect = window.wp.data.select('core/editor');
                if (!editorSelect) return;

                // Detect template switch in sidebar
                const newTemplate = editorSelect.getEditedPostAttribute?.('template');
                if (newTemplate !== undefined && newTemplate !== lastTemplate) {
                    lastTemplate = newTemplate;
                    fetchParts(newTemplate);
                }

                // Detect post save completion to refresh dynamic fields
                const isSaving = editorSelect.isSavingPost?.();
                const isAutosaving = editorSelect.isAutosavingPost?.();
                if (wasSaving && !isSaving && !isAutosaving) {
                    fetchParts(lastTemplate || '');
                }
                wasSaving = Boolean(isSaving && !isAutosaving);
            });
        }
    }

    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start);
    } else {
        start();
    }
}
