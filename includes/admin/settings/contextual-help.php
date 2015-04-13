<?php
/**
 * Contextual Help
 *
 * @package     RoboBlog\Admin\Settings\ContextualHelp
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Adds Contextual Help for the RoboBlog settings pages
 *
 * @since       1.0.0
 * @return      void
 */
function roboblog_settings_contextual_help() {
    $screen = get_current_screen();

    $screen->set_help_sidebar(
        '<p><strong>' . __( 'For more information:', 'roboblog' ) . '</strong></p>'
    );

    $screen->add_help_tab( array(
        'id'        => 'roboblog-feed-configuration',
        'title'     => __( 'Feed Settings', 'roboblog' ),
        'content'   => '<p>Test</p>'
    ) );

    // Allow extension
    do_action( 'roboblog_settings_contextual_help', $screen );
}
add_action( 'load-rbfeed_page_roboblog-settings', 'roboblog_settings_contextual_help' );
