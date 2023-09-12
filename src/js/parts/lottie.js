import $  from 'jquery';
import bodymovin from 'lottie-web/build/player/lottie_svg.min.js';

export function lottie(){
    $(document).ready(function () {
        $('.lottie-animation').each(function(){
            let path = $(this).data('path');
            let animation = bodymovin.loadAnimation({
                container: this, // Required
                path: path, // Required
                renderer: 'svg', // Required
                loop: true, // Optional
                autoplay: true, // Optional
            })
        });
    });
}