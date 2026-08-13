export function splitText() {
    const elements = document.querySelectorAll('.split-text');

    if (!elements.length) return;

    elements.forEach(el => {
        const words = el.innerHTML.split(' ');

        const newContent = words.map((word, index) => {
            return '<div class="split-text__wrapper"><div class="split-text__text delay-' + index + '">' + word + ' </div></div>';
        }).join('');

        el.innerHTML = newContent;
    });
}