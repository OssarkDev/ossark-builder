// adds class to header when scrolled

export function headerAnimation() {
    var prevScrollpos = window.scrollY;
    window.onscroll = function () {
        var currentScrollPos = window.scrollY;
        var header = document.querySelector("header");

        // do not use up/down scroll on first 200px height of page
        if (currentScrollPos > 200 && prevScrollpos > currentScrollPos) {
            header.classList.remove("hide-header");
        } else {
            header.classList.add("hide-header");
        } prevScrollpos = currentScrollPos;
    };
}
