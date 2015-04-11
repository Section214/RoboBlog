<?php
/**
 * Helper functions
 *
 * @package     RoboBlog\Functions
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Get user IP
 *
 * @since       1.0.0
 * @return      string $ip The IP address of the user
 */
function roboblog_get_ip() {
    $ip = '127.0.0.1';

    if( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return apply_filters( 'roboblog_get_ip', $ip );
}


/**
 * Get user host
 *
 * @since       1.0.0
 * @return      mixed string $host if detected, false otherwise
 */
function roboblog_get_host() {
    $host = false;

    if( defined( 'WPE_APIKEY' ) ) {
        $host = 'WP Engine';
	} elseif( defined( 'PAGELYBIN' ) ) {
		$host = 'Pagely';
	} elseif( DB_HOST == 'localhost:/tmp/mysql5.sock' ) {
		$host = 'ICDSoft';
	} elseif( DB_HOST == 'mysqlv5' ) {
		$host = 'NetworkSolutions';
	} elseif( strpos( DB_HOST, 'ipagemysql.com' ) !== false ) {
		$host = 'iPage';
	} elseif( strpos( DB_HOST, 'ipowermysql.com' ) !== false ) {
		$host = 'IPower';
	} elseif( strpos( DB_HOST, '.gridserver.com' ) !== false ) {
		$host = 'MediaTemple Grid';
	} elseif( strpos( DB_HOST, '.pair.com' ) !== false ) {
		$host = 'pair Networks';
	} elseif( strpos( DB_HOST, '.stabletransit.com' ) !== false ) {
		$host = 'Rackspace Cloud';
	} elseif( strpos( DB_HOST, '.sysfix.eu' ) !== false ) {
		$host = 'SysFix.eu Power Hosting';
	} elseif( strpos( $_SERVER['SERVER_NAME'], 'Flywheel' ) !== false ) {
		$host = 'Flywheel';
	} else {
		// Adding a general fallback for data gathering
		$host = 'DBH: ' . DB_HOST . ', SRV: ' . $_SERVER['SERVER_NAME'];
    }

    return $host;
}


/**
 * Get the current page URL
 *
 * @since       1.0.0
 * @global      object $post The WordPress post object
 * @return      string $page_url The current page URL
 */
function roboblog_get_current_page_url() {
    global $post;

    if( is_front_page() ) {
        $page_url = home_url();
    } else {
        $page_url = 'http';

        if( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) {
            $page_url .= 's';
        }

        $page_url .= '://';

        if( isset( $_SERVER['SERVER_PORT'] ) && $_SERVER['SERVER_PORT'] != '80' ) {
            $page_url .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        } else {
            $page_url .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        }
    }

    return apply_filters( 'roboblog_get_current_page_url', esc_url( $page_url ) );
}
