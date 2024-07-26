import $ from 'jquery';

export function testAjax() {
    // var block = $(this);
    // var container = block.find('.ajax-container');
    // let option = block.find('.ajax-select');
    // let id = option.val(); // Get the selected value

    var data = {
        action: 'test_ajax',
        id: '1',
    };

    $.ajax({
        url: customjs_ajax_object.ajax_url,
        type: 'POST',
        data: data,
        success: function(response) {
            if (response) {
                console.log(response);
            }
        },
        error: function(error) {
            console.error(error);
        }
    });
}
