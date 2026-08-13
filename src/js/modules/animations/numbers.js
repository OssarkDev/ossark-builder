export function numbers() {
    const numberElements = document.querySelectorAll('[data-animate-number]');

    if (!numberElements.length) return;

    numberElements.forEach(number => {
        // Split the source text into prefix + numeric value + suffix so units
        // like "km", "M+", "%" or a leading "$" are preserved while counting.
        const raw = number.innerText.trim();
        const match = raw.match(/^(\D*?)(-?[\d.,]+)(.*)$/);
        if (!match) return;

        const [, prefix, numberString, suffix] = match;
        const target = parseFloat(numberString.replace(/,/g, ''));
        if (isNaN(target)) return;

        const decimals = numberString.includes('.') ? numberString.split('.')[1].length : 0;
        const useGrouping = numberString.includes(',');

        const format = value => {
            const formatted = value.toLocaleString('en-US', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals,
                useGrouping,
            });
            return prefix + formatted + suffix;
        };

        let startTime = null;

        function easeOutQuad(t) {
            return t * (2 - t);
        }

        // Increase the duration to slow down the animation
        const duration = 2500;

        function updateNumber(timestamp) {
            if (!startTime) startTime = timestamp;
            let progress = (timestamp - startTime) / duration;
            progress = Math.min(progress, 1);
            const count = easeOutQuad(progress) * target;
            number.innerText = format(count);

            if (progress < 1) {
                requestAnimationFrame(updateNumber);
            } else {
                number.innerText = format(target);
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