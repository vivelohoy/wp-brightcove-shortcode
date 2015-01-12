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

$brightcove_embed_defaults = array(
        'width'         => 853,
        'height'        => 480,
        'id'            => false,
        'player_id'     => 2027711527001,
        'player_key'    => 'AQ~~,AAAB2Ejp1kE~,qYgZ7QVyRmCflxEtsSSb7N6jXd3aEUNg'
    );

if( !defined( 'wp_brightcove_shortcode_DIR' ) ) {
    define('wp_brightcove_shortcode_DIR', dirname( __FILE__ ) ); // plugin dir
}
if( !defined( 'wp_brightcove_shortcode_URL' ) ) {
    define('wp_brightcove_shortcode_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}


/*

IIIII NN   NN TTTTTTT EEEEEEE RRRRRR  FFFFFFF   AAA    CCCCC  EEEEEEE
 III  NNN  NN   TTT   EE      RR   RR FF       AAAAA  CC    C EE
 III  NN N NN   TTT   EEEEE   RRRRRR  FFFF    AA   AA CC      EEEEE
 III  NN  NNN   TTT   EE      RR  RR  FF      AAAAAAA CC    C EE
IIIII NN   NN   TTT   EEEEEEE RR   RR FF      AA   AA  CCCCC  EEEEEEE


*/

/*
 * Add a link to our plugin in the admin menu
 * under Settings > Hoy Brightcove Importer
 */

function wp_brightcove_shortcode_menu() {

    add_options_page(
        'WP Brightcove Shortcode Plugin',
        'WP Brightcove Shortcode',
        'manage_options',
        'wp-brightcove-shortcode',
        'wp_brightcove_shortcode_options_page'
    );

}
add_action( 'admin_menu', 'wp_brightcove_shortcode_menu' );


function wp_brightcove_shortcode_options_page() {

    if( !current_user_can( 'manage_options') ) {
        wp_die( 'You do not have sufficient permission to access this page.' );
    }

    $options = get_option( 'wp_brightcove_shortcode' );

    $VIDEO_WIDTH = $options['video_width'];
    $VIDEO_HEIGHT = $options['video_height'];
    $PLAYER_ID = $options['player_id'];
    $PLAYER_KEY = $options['player_key'];

    require( 'inc/options-page-wrapper.php' );

}

/*

 SSSSS  HH   HH  OOOOO  RRRRRR  TTTTTTT  CCCCC   OOOOO  DDDDD   EEEEEEE
SS      HH   HH OO   OO RR   RR   TTT   CC    C OO   OO DD  DD  EE
 SSSSS  HHHHHHH OO   OO RRRRRR    TTT   CC      OO   OO DD   DD EEEEE
     SS HH   HH OO   OO RR  RR    TTT   CC    C OO   OO DD   DD EE
 SSSSS  HH   HH  OOOO0  RR   RR   TTT    CCCCC   OOOO0  DDDDDD  EEEEEEE


*/

function brightcove_video_shortcode( $atts ) {
    if( array_key_exists( 'id', $atts ) && $atts['id'] ) {
        $options = get_option( 'wp-brightcove-shortcode' );

        $atts = shortcode_atts(
                array(
                    'id'            => false,
                    'width'         => $options['video_width'],
                    'height'        => $options['video_height'],
                    'player_id'     => $options['player_id'],
                    'player_key'    => $options['player_key']
                ),
                $atts
            );

        $context = Timber::get_context();
        $timber_options = array(
            'VIDEO_WIDTH'       => $atts['width'],
            'VIDEO_HEIGHT'      => $atts['height'],
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


/*

IIIII NN   NN IIIII TTTTTTT
 III  NNN  NN  III    TTT
 III  NN N NN  III    TTT
 III  NN  NNN  III    TTT
IIIII NN   NN IIIII   TTT


*/

function wp_brightcove_shortcode_styles() {

    wp_enqueue_style( 'wp_brightcove_shortcode_styles', wp_brightcove_shortcode_URL . 'css/wp-brightcove-shortcode.css', array(),
        filemtime( wp_brightcove_shortcode_DIR . '/css/wp-brightcove-shortcode.css' ) );

}
add_action( 'admin_head', 'wp_brightcove_shortcode_styles' );


function wp_brightcove_shortcode_init() {

    load_plugin_textdomain( 'wp-brightcove-shortcode', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

}
add_action( 'init', 'wp_brightcove_shortcode_init' );

register_activation_hook( __FILE__, 'wp_brightcove_shortcode_set_default_options' );
function wp_brightcove_shortcode_set_default_options() {
    global $brightcove_embed_defaults;

    if( false === get_option( 'wp_brightcove_shortcode' ) ) {
        $options['video_width']     = $brightcove_embed_defaults['width'];
        $options['video_height']    = $brightcove_embed_defaults['height'];
        $options['player_id']       = $brightcove_embed_defaults['player_id'];
        $options['player_key']      = $brightcove_embed_defaults['player_key'];
        $options['version']         = '0.1.0';
        add_option( 'wp_brightcove_shortcode_options', $options );
    }
}

?>