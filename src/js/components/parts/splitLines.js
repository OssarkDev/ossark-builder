export function splitLines() {
    const elements = document.querySelectorAll('.split-lines');

    elements.forEach(element => {
        // Remove previous split if any
        if (element.hasAttribute('data-split-initialized')) {
            element.removeAttribute('data-split-initialized');
            element.innerHTML = element.textContent;
        }

        const text = element.textContent;
        const words = text.match(/\S+\s*/g) || [];
        const tempWrapper = document.createElement('div');
        tempWrapper.style.position = 'absolute';
        tempWrapper.style.visibility = 'hidden';
        tempWrapper.style.pointerEvents = 'none';
        tempWrapper.style.whiteSpace = 'pre-wrap';
        tempWrapper.style.width = element.offsetWidth + 'px';
        tempWrapper.style.font = getComputedStyle(element).font;
        document.body.appendChild(tempWrapper);

        let lines = [];
        let currentLine = '';
        let prevHeight = null;

        words.forEach(word => {
            const testLine = currentLine + word;
            tempWrapper.textContent = testLine;
            const height = tempWrapper.getBoundingClientRect().height;

            if (prevHeight === null) prevHeight = height;

            // If height increases, new line is needed
            if (height > prevHeight && currentLine.trim() !== '') {
                lines.push(currentLine);
                currentLine = word;
                tempWrapper.textContent = currentLine;
                prevHeight = tempWrapper.getBoundingClientRect().height;
            } else {
                currentLine = testLine;
                prevHeight = height;
            }
        });

        if (currentLine.trim() !== '') {
            lines.push(currentLine);
        }

        document.body.removeChild(tempWrapper);

        // Clear the original text
        element.innerHTML = '';

            // Get all relevant computed font styles from parent
            const computed = getComputedStyle(element);
            const fontStyles = [
                'color',
                'font-weight',
                'font-size',
                'font-family',
                'line-height',
                'letter-spacing',
                'font-style',
                'text-align',
            ];

            lines.forEach(line => {
                const parent = document.createElement('div');
                parent.className = 'split-parent';                
                const lineDiv = document.createElement('div');
                const lineIndex = lines.indexOf(line) + 4;
                lineDiv.className = `split-child delay-${lineIndex}`;
                lineDiv.textContent = line;
                fontStyles.forEach(style => {
                    lineDiv.style[style] = computed.getPropertyValue(style);
                });
                parent.appendChild(lineDiv);
                element.appendChild(parent);
            });

        element.setAttribute('data-split-initialized', 'true');
    });
}

// Debounce helper
function debounce(fn, delay) {
    let timer = null;
    return function(...args) {
        clearTimeout(timer);
        timer = setTimeout(() => fn.apply(this, args), delay);
    };
}

// Initial run
splitLines();

// Re-run on resize (debounced)
window.addEventListener('resize', debounce(splitLines, 200));