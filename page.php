<?php
get_header();

wp_reset_query();
if ( have_posts() ) : while ( have_posts() ) : the_post();
    the_content();
endwhile;
endif;
wp_reset_query();

get_footer();
