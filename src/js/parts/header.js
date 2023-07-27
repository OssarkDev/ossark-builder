// adds class to header when scrolled

export function headerAnimation() {
    var prevScrollpos = window.pageYOffset;
    window.onscroll = function () {
        var currentScrollPos = window.pageYOffset;
        var header = document.querySelector("header");
        if (prevScrollpos > currentScrollPos) {
            header.classList.remove("hide-header");
        } else {
            header.classList.add("hide-header");
        } prevScrollpos = currentScrollPos;
    };
}
