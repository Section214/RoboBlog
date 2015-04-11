<?php
/**
 * AJAX functions
 *
 * @package     RoboBlog\Functions\AJAX
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Get AJAX URL
 *
 * @since       1.0.0
 * @return      string The AJAX URL
 */
function roboblog_get_ajax_url() {
    $scheme     = defined( 'FORCE_SSL_ADMIN' ) && FORCE_SSL_ADMIN ? 'https' : 'admin';
    $current_url= roboblog_get_current_page_url();
    $ajax_url   = admin_url( 'admin-ajax.php', $scheme );

    if( preg_match( '/^https/', $current_url ) && ! preg_match( '/^https/', $ajax_url ) ) {
        $ajax_url = preg_replace( '/^http/', 'https', $ajax_url );
    }

    return apply_filters( 'roboblog_ajax_url', $ajax_url );
}


/**
 * Check if AJAX works
 *
 * @since       1.0.0
 * @return      bool True if AJAX works, false otherwise
 */
function roboblog_test_ajax_works() {
    // Check if Airplane Mode plugin is installed
    if( class_exists( 'Airplane_Mode_Core' ) ) {
        global $Airplane_Mode_Core;

        if( method_exists( $Airplane_Mode_Core, 'enabled' ) ) {
            if( $Airplane_Mode_Core->enabled() ) {
                return true;
            }
        } else {
            if( $Airplane_Mode_Core->check_status() == 'on' ) {
                return true;
            }
        }
    }

    add_filter( 'block_local_requests', '__return_false' );

    if( get_transient( '_roboblog_ajax_works' ) ) {
        return true;
    }

    $params = array(
        'sslverify' => false,
        'timeout'   => 30,
        'body'      => array(
            'action'    => 'roboblog_test_ajax'
        )
    );

    $ajax   = wp_remote_post( roboblog_get_ajax_url(), $params );
    $works  = true;

    if( is_wp_error( $ajax ) ) {
        $works = false;
    } else {
        if( empty( $ajax['response'] ) ) {
            $works = false;
        }

        if( empty( $ajax['response']['code'] ) || (int) $ajax['response']['code'] !== 200 ) {
            $works = false;
        }

        if( empty( $ajax['response']['message'] ) || $ajax['response']['message'] !== 'OK' ) {
            $works = false;
        }

        if( ! isset( $ajax['body'] ) || (int) $ajax['body'] !== 0 ) {
            $works = false;
        }
    }

    if( $works ) {
        set_transient( '_roboblog_ajax_works', '1', DAY_IN_SECONDS );
    }

    return $works;
}
