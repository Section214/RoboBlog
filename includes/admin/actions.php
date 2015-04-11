<?php
/**
 * Admin actions
 *
 * @package     RoboBlog\Admin\Actions
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Process all actions sent via POST and GET by looking for the 'roboblog-action'
 * request and running do_action() to call the function
 *
 * @since       1.0.0
 * @return      void
 */
function roboblog_process_actions() {
    if( isset( $_POST['roboblog-action'] ) ) {
        do_action( 'roboblog_' . $_POST['roboblog-action'], $_POST );
    }

    if( isset( $_GET['roboblog-action'] ) ) {
        do_action( 'roboblog_' . $_GET['roboblog-action'], $_GET );
    }
}
add_action( 'admin_init', 'roboblog_process_actions' );
