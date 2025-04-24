export function imageDimensions() {
    document.querySelectorAll("img").forEach((img) => {
        if (!img.hasAttribute("width") || !img.hasAttribute("height")) {
            img.decode().then(() => {
                img.setAttribute("width", img.naturalWidth);
                img.setAttribute("height", img.naturalHeight);
            }).catch(() => {
                img.setAttribute("width", img.naturalWidth);
                img.setAttribute("height", img.naturalHeight);
            });
        }
    });
}