export function numbers() {
    const numberElements = document.querySelectorAll('[data-animate-number]');

    if (!numberElements.length) return;

    numberElements.forEach(number => {
        let target = parseInt(number.innerText);
        let count = 0;
        let increment = target / 100;
        let startTime = null;

        function easeOutQuad(t) {
            return t * (2 - t);
        }

        // Increase the duration to slow down the animation
        const duration = 2500; // 4 seconds duration

        function updateNumber(timestamp) {
            if (!startTime) startTime = timestamp;
            let progress = (timestamp - startTime) / duration; // 2 seconds duration
            progress = Math.min(progress, 1);
            count = easeOutQuad(progress) * target;
            number.innerText = Math.ceil(count);

            if (progress < 1) {
                requestAnimationFrame(updateNumber);
            } else {
                number.innerText = target;
            }
        }

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    requestAnimationFrame(updateNumber);
                    observer.unobserve(entry.target);
                }
            });
        });

        observer.observe(number);
    });
}