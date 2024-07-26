<?php

add_action('wp_ajax_test_ajax', 'test_ajax');
add_action('wp_ajax_nopriv_test_ajax', 'test_ajax');

public function test_ajax() {

    $data = $_POST['data'];
    wp_send_json_success($data);

    wp_die();
}