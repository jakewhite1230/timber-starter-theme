<?php
add_action( 'admin_enqueue_scripts', 'wpse19907_admin_enqueue_scripts' );
function wpse19907_admin_enqueue_scripts()
{
        wp_enqueue_script( 'wpse-19907', get_template_directory_uri() . '/js/sidebar-limits.js', array(), false, true );
        wp_enqueue_style( 'wpse-19907', get_template_directory_uri() . '/css/sidebar-limits.css');

}