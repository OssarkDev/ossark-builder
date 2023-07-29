// adds class to header when scrolled

export function headerAnimation() {
    var prevScrollpos = window.scrollY;
    window.onscroll = function () {
        var currentScrollPos = window.scrollY;
        var header = document.querySelector("header");
        if (prevScrollpos > currentScrollPos) {
            header.classList.remove("hide-header");
        } else {
            header.classList.add("hide-header");
        } prevScrollpos = currentScrollPos;
    };
}
