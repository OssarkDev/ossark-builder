export function imageDimensions() {
    document.querySelectorAll("img").forEach((img) => {
        if (!img.hasAttribute("width") || !img.hasAttribute("height")) {
            const setDimensions = () => {
                if (img.naturalWidth > 1 && img.naturalHeight > 1) {
                    img.setAttribute("width", img.naturalWidth);
                    img.setAttribute("height", img.naturalHeight);
                }
            };
            if (img.complete) {
                setDimensions();
            } else {
                img.addEventListener("load", setDimensions, { once: true });
            }
        }
    });
}