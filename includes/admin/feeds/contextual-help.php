<?php
/**
 * Contextual Help
 *
 * @package     RoboBlog\Admin\Feeds\ContextualHelp
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Adds Contextual Help for the Feeds add/edit pages
 *
 * @since       1.0.0
 * @return      void
 */
function roboblog_feeds_contextual_help() {
    $screen = get_current_screen();

    if( $screen->id != 'rbfeed' ) {
        return;
    }

    $screen->set_help_sidebar(
        '<p><strong>' . __( 'For more information:', 'roboblog' ) . '</strong></p>'
    );

    $screen->add_help_tab( array(
        'id'        => 'roboblog-feed-configuration',
        'title'     => __( 'Feed Settings', 'roboblog' ),
        'content'   => '<p>Test</p>'
    ) );

    // Allow extension
    do_action( 'roboblog_feeds_contextual_help', $screen );
}
add_action( 'load-post.php', 'roboblog_feeds_contextual_help' );
add_action( 'load-post-new.php', 'roboblog_feeds_contextual_help' );
