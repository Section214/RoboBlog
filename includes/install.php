<?php
/**
 * Install
 *
 * @package     RoboBlog\Install
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Install function
 *
 * @since       1.0.0
 * @global      object $wpdb The WordPress database object
 * @global      array $roboblog_options The RoboBlog options array
 * @return      void
 */
function roboblog_install() {
    global $wpdb, $roboblog_options;

    // Add upgraded from option
    $current_version = get_option( 'roboblog_version' );
    if( $current_version ) {
        update_option( 'roboblog_version_upgraded_from', $current_version );
    }

    // Setup default options
    $options = array();

    foreach( roboblog_get_registered_settings() as $tab => $settings ) {
        foreach( $settings as $option ) {
            if( $option['type'] == 'checkbox' && ! empty( $option['std'] ) ) {
                $options[$option['id']] = '1';
            }
        }
    }

    update_option( 'roboblog_settings', array_merge( $roboblog_options, $options ) );
    update_option( 'roboblog_version', ROBOBLOG_VER );

    // Bail if activating from network/bulk
    if( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
        return;
    }

    // Add the redirect transient
    set_transient( '_roboblog_activation_redirect', true, 30 );
}
register_activation_hook( ROBOBLOG_FILE, 'roboblog_install' );
