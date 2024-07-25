export function activeMenuItem() {
    const url = window.location.href;
    const $navLinks = $(".header__nav__list a");
    
    $navLinks.each(function() {
        if (url === this.href) {
            $(this).closest(".header__nav__list__item").addClass("active");
        }
    });
}
