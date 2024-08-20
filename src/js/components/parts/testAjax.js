import $ from 'jquery';
import { scroll } from './scroll';

export function testAjax() {
    var block = $('.ajax-example');
    var container = block.find('.ajax-container');
    var button = block.find('.ajax-button');
    var id = 0;
    var data = {};

    function getAjaxData() {
        data.action = 'test_ajax';
        data.id = id;

        container.addClass('loading');

        $.ajax({
            url: customjs_ajax_object.ajax_url,
            type: 'POST',
            data: data,
            // on success, replace the content of the container with the response
            success: function(response) {
                if (response) {
                    container.html('');
                    container.html(response);
                    setTimeout(function() {
                        container.removeClass('loading');
                    }, 500);
                    scroll(); // Call the scroll function to check if the section is in view
                }
            },
            error: function(error) {
                console.error(error);
            }
        });
    }

    // When the button is clicked, get the data-id attribute and assign it to the 'id' variable and call the 'getAjaxData' function
    button.on('click', function() {
        var buttonId = $(this).data('id');
        id = buttonId;
        getAjaxData();
    });
}
