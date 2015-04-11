<?php
/**
 * Scripts
 *
 * @package     RoboBlog\Scripts
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Load admin scripts
 *
 * @since       1.0.0
 * @param       string $hook The page hook
 * @return      void
 */
function roboblog_admin_scripts( $hook ) {
    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix     = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
    $ui_style   = ( get_user_option( 'admin_color' ) == 'classic' ) ? 'classic' : 'fresh';

    // Main stylesheet loaded globally for dashicon support
    wp_enqueue_style( 'roboblog', ROBOBLOG_URL . 'assets/css/admin' . $suffix . '.css', array(), ROBOBLOG_VER );

    if( ! apply_filters( 'roboblog_load_admin_scripts', roboblog_is_admin_page( $hook ), $hook ) ) {
        return;
    }

    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_media();
    wp_enqueue_style( 'jquery-ui-css', ROBOBLOG_URL . 'assets/css/jquery-ui-' . $ui_style . $suffix . '.css' );
    wp_enqueue_script( 'media-upload' );
    wp_enqueue_style( 'thickbox' );
    wp_enqueue_script( 'thickbox' );

    wp_enqueue_style( 'roboblog-fa', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' );
    wp_enqueue_script( 'roboblog', ROBOBLOG_URL . 'assets/js/admin' . $suffix . '.js', array( 'jquery' ), ROBOBLOG_VER );
    wp_localize_script( 'roboblog', 'roboblog_vars', array(
        'image_media_button'    => __( 'Insert Image', 'roboblog' ),
        'image_media_title'     => __( 'Select Image', 'roboblog' )
    ) );
}
add_action( 'admin_enqueue_scripts', 'roboblog_admin_scripts', 100 );
