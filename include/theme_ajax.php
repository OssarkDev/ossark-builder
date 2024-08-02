<?php

/**
 * AJAX Example
 */
add_action('wp_ajax_test_ajax', 'test_ajax');
add_action('wp_ajax_nopriv_test_ajax', 'test_ajax');

function test_ajax() {

    // Get the id from the ajax request
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
    }

    // Start output buffering (needed for get_template_part to work)
    ob_start();

    // set $args for get_template_part
    $args = array(
        'id' => $id
    );

    // Load the template part with the $args
    get_template_part('components/parts/ajax-example', '', $args);

    // Get the output buffer and clean it
    wp_die();
}




