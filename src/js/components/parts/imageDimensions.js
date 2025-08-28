export function imageDimensions() {
    const setDimensions = (element) => {
        if (element.tagName === 'IMG') {
            if (element.naturalWidth > 0 && element.naturalHeight > 0) {
                element.setAttribute("width", element.naturalWidth);
                element.setAttribute("height", element.naturalHeight);
            }
        } else if (element.tagName === 'VIDEO') {
            if (element.videoWidth > 0 && element.videoHeight > 0) {
                element.setAttribute("width", element.videoWidth);
                element.setAttribute("height", element.videoHeight);
            }
        }
    };

    const processAllMedia = () => {
        // Process all images without width/height
        document.querySelectorAll('img:not([width]), img:not([height])').forEach(img => {
            if (img.complete) {
                setDimensions(img);
            } else {
                img.addEventListener('load', () => setDimensions(img), { once: true });
            }
        });

        // Process all videos without width/height
        document.querySelectorAll('video:not([width]), video:not([height])').forEach(video => {
            if (video.readyState >= 1) {
                setDimensions(video);
            } else {
                video.addEventListener('loadedmetadata', () => setDimensions(video), { once: true });
            }
        });
    };

    // Run immediately
    processAllMedia();

    // Re-run after AJAX content loads (simple approach)
    document.addEventListener('DOMContentLoaded', processAllMedia);
    window.addEventListener('load', processAllMedia);
    
    // For AJAX content - run after a delay
    setTimeout(processAllMedia, 1000);
}