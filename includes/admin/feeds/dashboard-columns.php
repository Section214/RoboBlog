<?php
/**
 * Feed post type columns
 *
 * @package     RoboBlog\PostTypes\Dashboard\Columns
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Rework dashboard columns
 *
 * @since       1.0.0
 * @param       array $columns The current columns
 * @global      string $typenow The post type we are viewing
 * @return      array $columns The updated columns
 */
function roboblog_feed_columns( $columns ) {
    global $typenow;

    $columns = array(
        'cb'        => '<input type="checkbox" />',
        'title'     => __( 'Feed', 'roboblog' ),
        'last_run'  => __( 'Last Run', 'roboblog' ),
        'next_run'  => __( 'Next Run', 'roboblog' ),
        'status'    => __( 'Status', 'roboblog' )
    );

    return apply_filters( 'roboblog_feed_columns', $columns );
}
add_filter( 'manage_edit-rbfeed_columns', 'roboblog_feed_columns' );


/**
 * Render our custom columns
 *
 * @since       1.0.0
 * @param       string $column_name The name of a given column
 * @param       int $post_id The post ID for a given row
 * @return      void
 */
function roboblog_render_feed_columns( $column_name, $post_id ) {
    if( get_post_type( $post_id ) == 'rbfeed' ) {
        switch( $column_name ) {
            case 'last_run':
                // Do stuff
                break;
            case 'next_run':
                // Do stuff
                break;
            case 'status':
                // Do stuff
                break;
        }
    }
}
add_action( 'manage_posts_custom_column', 'roboblog_render_feed_columns', 10, 2 );


/**
 * Remove date filter
 *
 * @since       1.0.0
 * @param       array $dates The current dates
 * @global      string $typenow The current post type
 * @return      array $dates The updated (empty) dates array
 */
function roboblog_remove_date_filter( $dates ) {
    global $typenow;

    if( $typenow == 'rbfeed' ) {
        $dates = array();
    }

    return $dates;
}
//add_filter( 'months_dropdown_results', 'roboblog_remove_date_filter', 99 );
