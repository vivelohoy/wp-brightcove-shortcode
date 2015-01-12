<?php

if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

if( get_option( 'wp_brightcove_shortcode' ) != false ) {
    delete_option( 'wp_brightcove_shortcode' );
} 

?>