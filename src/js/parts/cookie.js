function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (
        value || ""
    ) + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') 
            c = c.substring(1, c.length);
        
        if (c.indexOf(nameEQ) == 0) 
            return c.substring(nameEQ.length, c.length); 
    }
    return null;
}

window.addEventListener('DOMContentLoaded', function () {
    var cookie = getCookie('header_notifiaction_cookie')
    if (cookie == null) {
        console.log('cookie does not exist')
        // document.querySelector('.announcement-bar').style.display = "block";
        // setCookie('header_notifiaction_cookie', 'hideNotification', 1)
    } else {
        // document.querySelector('.announcement-bar').style.display = "none";
        console.log('cookie already exists')
    }

})
