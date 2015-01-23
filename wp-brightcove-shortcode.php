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
/*

DDDDD   EEEEEEE VV     VV EEEEEEE LL       OOOOO  PPPPPP  MM    MM EEEEEEE NN   NN TTTTTTT
DD  DD  EE      VV     VV EE      LL      OO   OO PP   PP MMM  MMM EE      NNN  NN   TTT
DD   DD EEEEE    VV   VV  EEEEE   LL      OO   OO PPPPPP  MM MM MM EEEEE   NN N NN   TTT
DD   DD EE        VV VV   EE      LL      OO   OO PP      MM    MM EE      NN  NNN   TTT
DDDDDD  EEEEEEE    VVV    EEEEEEE LLLLLLL  OOOO0  PP      MM    MM EEEEEEE NN   NN   TTT

On deactivation, delete all settings. This is't suitable for production.

*/
register_deactivation_hook( __FILE__, 'wp_brightcove_shortcode_delete_plugin_options' );
register_activation_hook( __FILE__, 'wp_brightcove_shortcode_add_defaults' );
add_action( 'admin_init', 'wp_brightcove_shortcode_admin_init' );
add_action( 'admin_menu', 'wp_brightcove_shortcode_add_options_page' );


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

        Vivelohoy Video Player - Chromeless, 480x270 (default)
        player_id = 2027711527001
        player_key = AQ~~,AAAB2Ejp1kE~,qYgZ7QVyRmCflxEtsSSb7N6jXd3aEUNg

        Emprendedores Video Player - Chromeless, 480x270 (default)
        player_id = 3971228038001
        player_key = AQ~~,AAAB2Ejp1kE~,qYgZ7QVyRmAY6eVE_jKAzK_NU0a57Pd6

        source: https://videocloud.brightcove.com/publishing
        */
        $arr = array(
            'video_width'   => 853,
            'video_height'  => 480,
            'player_id'     => 2027711527001,
            'player_key'    => 'AQ~~,AAAB2Ejp1kE~,qYgZ7QVyRmCflxEtsSSb7N6jXd3aEUNg'
        );
        update_option( 'wp_brightcove_shortcode_options', $arr );
    }
}

// Init plugin options to white list our options
function wp_brightcove_shortcode_admin_init(){
    load_plugin_textdomain( 'wp-brightcove-shortcode', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

    register_setting(
        'wp_brightcove_shortcode_plugin_options',
        'wp_brightcove_shortcode_options',
        'wp_brightcove_shortcode_validate_options'
    );
}

/*

 OOOOO  PPPPPP  TTTTTTT IIIII  OOOOO  NN   NN  SSSSS
OO   OO PP   PP   TTT    III  OO   OO NNN  NN SS
OO   OO PPPPPP    TTT    III  OO   OO NN N NN  SSSSS
OO   OO PP        TTT    III  OO   OO NN  NNN      SS
 OOOO0  PP        TTT   IIIII  OOOO0  NN   NN  SSSSS

*/


// Add a link to our plugin in the admin menu
function wp_brightcove_shortcode_add_options_page() {
    add_options_page(
        'WP Brightcove Shortcode Plugin',
        'WP Brightcove Shortcode',
        'manage_options',
        'wp-brightcove-shortcode',
        'wp_brightcove_shortcode_render_form'
    );
}


function wp_brightcove_shortcode_render_form() {
//    wp_brightcove_shortcode_set_default_options();

    if( !current_user_can( 'manage_options') ) {
        wp_die( 'You do not have sufficient permission to access this page.' );
    }

?>

<div class="wrap">
    
    <!-- Display Plugin Icon, Header, and Description -->
    <div class="icon32" id="icon-options-general"><br></div>
    <h2><?php _e( 'WP Brightcove Shortcode Options', 'wp-brightcove-shortcode' ); ?></h2>
    <p><?php _e( 'Enter the default values that will be used when the Brightcove Shortcode is used without any additional options.', 'wp-brightcove-shortcode' ); ?></p>

    <!-- Beginning of the Plugin Options Form -->
    <form method="post" action="options.php">
        <?php settings_fields( 'wp_brightcove_shortcode_plugin_options' ); ?>
        <?php $options = get_option( 'wp_brightcove_shortcode_options' ); ?>

        <!-- Table Structure Containing Form Controls -->
        <!-- Each Plugin Option Defined on a New Table Row -->
        <table class="form-table">

            <!-- Textbox Control -->
            <tr>
                <th scope="row"><?php _e( 'Video Width', 'wp-brightcove-shortcode' ); ?></th>
                <td>
                    <input type="text" size="57" name="wp_brightcove_shortcode_options[video_width]" value="<?php echo $options['video_width']; ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e( 'Video Height', 'wp-brightcove-shortcode' ); ?></th>
                <td>
                    <input type="text" size="57" name="wp_brightcove_shortcode_options[video_height]" value="<?php echo $options['video_height']; ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e( 'Player ID', 'wp-brightcove-shortcode' ); ?></th>
                <td>
                    <input type="text" size="57" name="wp_brightcove_shortcode_options[player_id]" value="<?php echo $options['player_id']; ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e( 'Player Key', 'wp-brightcove-shortcode' ); ?></th>
                <td>
                    <input type="text" size="57" name="wp_brightcove_shortcode_options[player_key]" value="<?php echo $options['player_key']; ?>" />
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>
        <h3><?php _e( 'Preview', 'wp-brightcove-shortcode' ); ?></h3>
        <h4>&#91;brightcove id="3917628997001"&#93;</h4>
        <table class="form-table">
            <tr>
                <th scope="row"><?php _e( 'Video Width', 'wp-brightcove-shortcode' ); ?></th>
                <td>
                    <?php echo $options['video_width']; ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e( 'Video Height', 'wp-brightcove-shortcode' ); ?></th>
                <td>
                    <?php echo $options['video_height']; ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e( 'Player ID', 'wp-brightcove-shortcode' ); ?></th>
                <td>
                    <?php echo $options['player_id']; ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e( 'Player Key', 'wp-brightcove-shortcode' ); ?></th>
                <td>
                    <?php echo $options['player_key']; ?>
                </td>
            </tr>
        </table>
        <div id="brightcove_shortcode_preview">
            <?php echo do_shortcode( '[brightcove id="3917628997001"]' ); ?>
        </div>
    </form>
</div>

<?php
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function wp_brightcove_shortcode_validate_options($input) {
    $input['video_width']   =  wp_filter_nohtml_kses($input['video_width']);
    $input['video_height']  =  wp_filter_nohtml_kses($input['video_height']);
    $input['player_id']     =  wp_filter_nohtml_kses($input['player_id']);
    $input['player_key']    =  wp_filter_nohtml_kses($input['player_key']);
    return $input;
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