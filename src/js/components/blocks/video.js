export function video() {
    const videos = document.querySelectorAll('.video__container');

    if (!videos.length) return;

    videos.forEach(container => {
        container.addEventListener('click', () => {
            container.classList.add('active');
            const iframe = container.querySelector('iframe');
            if (iframe) {
                const src = iframe.getAttribute('src');
                const separator = src.includes('?') ? '&' : '?';
                iframe.setAttribute('src', src + separator + 'autoplay=1');
                setTimeout(() => {
                    container.classList.add('hide');
                }, 2000);
            }
        });
    });
}

