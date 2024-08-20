export function cookieBanner() {

    // *** Check if cookie is set is done via PHP ***

    // Cookie banner items
    const acceptButton = $('#cookie-accept');
    const cookieBanner = $('.cookie-banner');

    // Show cookie banner with animation
    cookieBanner.addClass('active');

    // Accept cookies
    acceptButton.on('click', function() {
        document.cookie = "accept_cookies=true; path=/; max-age=" + 60*60*24*30; // 30 days
        cookieBanner.removeClass('active');
        setTimeout(function() {
            $('.cookie-banner').hide();
        }, 400);
    });
}