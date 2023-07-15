<?php

/*
=====================
	Add JS Variables
=====================
*/
function javascript_variables(){ ?>
    <script type="text/javascript">
        var main_url = '<?php echo get_site_url(); ?>';
        // var lang = '<?php // echo ICL_LANGUAGE_CODE; ?>'; // add if you have WPML
        var theme_url = '<?php echo get_template_directory_uri(); ?>';
        var ajax_url = '<?php echo admin_url( "admin-ajax.php" ); ?>';
        var ajax_nonce = '<?php echo wp_create_nonce( "secure_nonce_name" ); ?>';
    </script><?php
  }
  add_action ( 'wp_head', 'javascript_variables' );