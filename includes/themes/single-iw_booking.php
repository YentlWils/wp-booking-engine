<?php
/**
 * The template for displaying single room
 * @package monalisa
 */
if (have_posts()) : while (have_posts()) : the_post();
    $path = includeTemplateFile('iw_booking/roominfo',IWBOOKING_THEME_PATH);
    if($path){
        include $path;
    }else{
        echo esc_html__('No theme found', 'monalisa');
    }
endwhile;
endif;

