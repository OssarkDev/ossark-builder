export function video() {
    let videos = $('.video__container');

    videos.each(function() {
        let video = $(this);

        video.on('click', function() {
            video.addClass('active');
            let iframe = video.find('iframe');
            if (iframe.length) {
                let src = iframe.attr('src');
                let separator = src.includes('?') ? '&' : '?';
                iframe.attr('src', src + separator + 'autoplay=1');
                setTimeout(function() {
                    video.addClass('hide');
                }, 2000);
            }
        });
    });
}

