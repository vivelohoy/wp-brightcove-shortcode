<?php
/*
 * Plugin Name: WP Brightcove Shortcode Plugin
 * Plugin URI: https://github.com/vivelohoy/wp-brightcove-shortcode
 * Description: A WordPress plugin to provide a shortcode to embed a Brightcove video.
 * Version: 0.1.0
 * Author: Nick Bennett
 * Author URI: https://github.com/tothebeat
 * License: MIT
 */

/*
 * Global variables
 */

if( !defined( 'wp_brightcove_shortcode_DIR' ) ) {
    define('wp_brightcove_shortcode_DIR', dirname( __FILE__ ) ); // plugin dir
}
if( !defined( 'wp_brightcove_shortcode_URL' ) ) {
    define('wp_brightcove_shortcode_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}


/*

IIIII NN   NN IIIII TTTTTTT
 III  NNN  NN  III    TTT
 III  NN N NN  III    TTT
 III  NN  NNN  III    TTT
IIIII NN   NN IIIII   TTT

*/

// Set-up Action and Filter Hooks
register_uninstall_hook( __FILE__, 'wp_brightcove_shortcode_delete_plugin_options' );
// On deactivation, delete all settings. This is't suitable for production.
register_deactivation_hook( __FILE__, 'wp_brightcove_shortcode_delete_plugin_options' );
register_activation_hook( __FILE__, 'wp_brightcove_shortcode_add_defaults' );


// Delete options table entries ONLY when plugin deactivated AND deleted
function wp_brightcove_shortcode_delete_plugin_options() {
    delete_option( 'wp_brightcove_shortcode_options' );
}

// Define default option settings
function wp_brightcove_shortcode_add_defaults() {
    $tmp = get_option( 'wp_brightcove_shortcode_options' );
    if( !is_array( $tmp ) ) {
        delete_option( 'wp_brightcove_shortcode_options' );
        /*
        Brightcove Players:

        Vivelohoy Video Player with Ads - Chromeless, 480x270 (default)
        player_id = 4005130149001
        player_key = AQ~~,AAAB2Ejp1kE~,qYgZ7QVyRmCtORwOH7VtCKYNUwwP3qno

        Vivelohoy Video Player - Chromeless, 480x270
        player_id = 2027711527001
        player_key = AQ~~,AAAB2Ejp1kE~,qYgZ7QVyRmCflxEtsSSb7N6jXd3aEUNg

        Emprendedores Video Player - Chromeless, 480x270
        player_id = 3971228038001
        player_key = AQ~~,AAAB2Ejp1kE~,qYgZ7QVyRmAY6eVE_jKAzK_NU0a57Pd6

        source: https://videocloud.brightcove.com/publishing
        */
        $arr = array(
            'video_width'   => 853,
            'video_height'  => 480,
            'player_id'     => 4005130149001,
            'player_key'    => 'AQ~~,AAAB2Ejp1kE~,qYgZ7QVyRmCtORwOH7VtCKYNUwwP3qno'
        );
        update_option( 'wp_brightcove_shortcode_options', $arr );
    }
}


/*

 SSSSS  HH   HH  OOOOO  RRRRRR  TTTTTTT  CCCCC   OOOOO  DDDDD   EEEEEEE
SS      HH   HH OO   OO RR   RR   TTT   CC    C OO   OO DD  DD  EE
 SSSSS  HHHHHHH OO   OO RRRRRR    TTT   CC      OO   OO DD   DD EEEEE
     SS HH   HH OO   OO RR  RR    TTT   CC    C OO   OO DD   DD EE
 SSSSS  HH   HH  OOOO0  RR   RR   TTT    CCCCC   OOOO0  DDDDDD  EEEEEEE


*/

if( !function_exists( 'brightcove_video_shortcode' ) ) {
    function brightcove_video_shortcode( $atts ) {
        if( array_key_exists( 'id', $atts ) && $atts['id'] ) {
            $options = get_option( 'wp_brightcove_shortcode_options' );

            $atts = shortcode_atts(
                    array(
                        'id'            => false,
                        'video_width'   => $options['video_width'],
                        'video_height'  => $options['video_height'],
                        'player_id'     => $options['player_id'],
                        'player_key'    => $options['player_key']
                    ),
                    $atts
                );

            $context = Timber::get_context();
            $timber_options = array(
                'VIDEO_WIDTH'       => $atts['video_width'],
                'VIDEO_HEIGHT'      => $atts['video_height'],
                'VIDEO_ID'          => $atts['id'],
                'PLAYER_ID'         => $atts['player_id'],
                'PLAYER_KEY'        => $atts['player_key']
            );
            $context = array_merge( $context, $timber_options );

            return Timber::compile('inc/default-post-template.twig', $context);
        } else {
            return '';
        }
    }
    add_shortcode( 'brightcove', 'brightcove_video_shortcode' );
}

?>