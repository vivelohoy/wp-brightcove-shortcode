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

// source: https://videocloud.brightcove.com/publishing
$wbs_brightcove_players = array(
    "vivelohoy"         => array(
        "player_id"     => 4005130149001,
        "player_key"    => 'AQ~~,AAAB2Ejp1kE~,qYgZ7QVyRmCtORwOH7VtCKYNUwwP3qno',
    ),
    "vivelohoy-noads"   => array(
        "player_id"     => 2027711527001,
        "player_key"    => 'AQ~~,AAAB2Ejp1kE~,qYgZ7QVyRmCflxEtsSSb7N6jXd3aEUNg',
    ),
    "emprendedores"     => array(
        "player_id"     => 3971228038001,
        "player_key"    => 'AQ~~,AAAB2Ejp1kE~,qYgZ7QVyRmAY6eVE_jKAzK_NU0a57Pd6',
    ),
);

$wbs_video_defaults = array(
    'id'        => false,
    'width'     => 860,
    'height'    => 484,
);


/*

 SSSSS  HH   HH  OOOOO  RRRRRR  TTTTTTT  CCCCC   OOOOO  DDDDD   EEEEEEE
SS      HH   HH OO   OO RR   RR   TTT   CC    C OO   OO DD  DD  EE
 SSSSS  HHHHHHH OO   OO RRRRRR    TTT   CC      OO   OO DD   DD EEEEE
     SS HH   HH OO   OO RR  RR    TTT   CC    C OO   OO DD   DD EE
 SSSSS  HH   HH  OOOO0  RR   RR   TTT    CCCCC   OOOO0  DDDDDD  EEEEEEE


*/

if( !function_exists( 'brightcove_video_shortcode' ) ) {
    function brightcove_video_shortcode( $atts ) {
        global $wbs_brightcove_players;
        global $wbs_video_defaults;

        $atts = shortcode_atts( $wbs_video_defaults, $atts );

        if( array_key_exists( 'id', $atts ) && $atts['id'] ) {
            $context = Timber::get_context();
            $timber_options = array(
                'VIDEO_WIDTH'       => $atts['width'],
                'VIDEO_HEIGHT'      => $atts['height'],
                'VIDEO_ID'          => $atts['id'],
                'PLAYER_ID'         => $wbs_brightcove_players['vivelohoy']['player_id'],
                'PLAYER_KEY'        => $wbs_brightcove_players['vivelohoy']['player_key'],
            );
            $context = array_merge( $context, $timber_options );

            return Timber::compile('inc/ads-player-template.twig', $context);
        } else {
            return '';
        }
    }
    add_shortcode( 'brightcove', 'brightcove_video_shortcode' );
}

if( !function_exists( 'brightcove_noads_video_shortcode' ) ) {
    function brightcove_noads_video_shortcode( $atts ) {
        global $wbs_brightcove_players;
        global $wbs_video_defaults;

        $atts = shortcode_atts( $wbs_video_defaults, $atts );

        if( array_key_exists( 'id', $atts ) && $atts['id'] ) {
            $context = Timber::get_context();
            $timber_options = array(
                'VIDEO_WIDTH'       => $atts['width'],
                'VIDEO_HEIGHT'      => $atts['height'],
                'VIDEO_ID'          => $atts['id'],
                'PLAYER_ID'         => $wbs_brightcove_players['vivelohoy-noads']['player_id'],
                'PLAYER_KEY'        => $wbs_brightcove_players['vivelohoy-noads']['player_key'],
            );
            $context = array_merge( $context, $timber_options );

            return Timber::compile('inc/noads-player-template.twig', $context);
        } else {
            return '';
        }
    }
    add_shortcode( 'brightcove-noads', 'brightcove_noads_video_shortcode' );
}

if( !function_exists( 'emprendedores_video_shortcode' ) ) {
    function emprendedores_video_shortcode( $atts ) {
        global $wbs_brightcove_players;
        global $wbs_video_defaults;

        $atts = shortcode_atts( $wbs_video_defaults, $atts );

        if( array_key_exists( 'id', $atts ) && $atts['id'] ) {
            $context = Timber::get_context();
            $timber_options = array(
                'VIDEO_WIDTH'       => $atts['width'],
                'VIDEO_HEIGHT'      => $atts['height'],
                'VIDEO_ID'          => $atts['id'],
                'PLAYER_ID'         => $wbs_brightcove_players['emprendedores']['player_id'],
                'PLAYER_KEY'        => $wbs_brightcove_players['emprendedores']['player_key'],
            );
            $context = array_merge( $context, $timber_options );

            return Timber::compile('inc/noads-player-template.twig', $context);
        } else {
            return '';
        }
    }
    add_shortcode( 'emprendedores', 'emprendedores_video_shortcode' );
}

?>