export function typewriter(speed = 200) {
    const elements = document.querySelectorAll('.typewriter');

    if (!elements.length) return;

    elements.forEach(el => {
        const fullText = el.getAttribute('data-text');
        let index = 0;

        function type() {
            if (index <= fullText.length) {
                el.textContent = fullText.slice(0, index);
                index++;
                setTimeout(type, speed);
            } else {
                el.style.borderRight = 'none';
            }
        }

        // Observe when .in-view class is added
        const observer = new MutationObserver(mutations => {
            for (const mutation of mutations) {
                if (
                    mutation.attributeName === 'class' &&
                    el.classList.contains('in-view') &&
                    !el.dataset.typed
                ) {
                    el.dataset.typed = 'true'; // avoid re-running
                    type();
                }
            }
        });

        observer.observe(el, { attributes: true });
    });
}