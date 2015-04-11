<?php
/**
 * Uninstall
 *
 * @package     RoboBlog\Uninstall
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}


// Load main plugin file
include_once( 'roboblog.php' );

global $wpdb;

if( eventlab_get_option( 'uninstall_on_delete' ) ) {

    // Delete CPTs
    //$post_types = array( 'event', 'event_payment', 'event_discount', 'event_log' );

    /*
    foreach( $post_types as $post_type ) {
        $items = get_posts( array( 'post_type' => $post_type, 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids' ) );

        if( $items ) {
            foreach( $items as $item ) {
                wp_delete_post( $item, true );
            }
        }
    }
    */

    // Delete plugin options
    delete_option( 'roboblog_settings' );
}
