<?php
/**
 * Post type functions
 *
 * @package     RoboBlog\PostTypes
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Register our new CPT
 *
 * @since       1.0.0
 * @return      void
 */
function roboblog_register_post_types() {
    $labels = apply_filters( 'roboblog_feed_labels', array(
        'name'              => _x( 'Feeds', 'rbfeed post type name', 'roboblog' ),
        'singular_name'     => _x( 'Feed', 'singular rbfeed post type name', 'roboblog' ),
        'add_new'           => __( 'Add New', 'roboblog' ),
        'add_new_item'      => __( 'Add New Feed', 'roboblog' ),
        'new_item'          => __( 'New Feed', 'roboblog' ),
        'edit_item'         => __( 'Edit Feed', 'roboblog' ),
        'all_items'         => __( 'All Feeds', 'roboblog' ),
        'view_item'         => __( 'View Feed', 'roboblog' ),
        'search_items'      => __( 'Search Feeds', 'roboblog' ),
        'not_found'         => __( 'No feeds found', 'roboblog' ),
        'not_found_in_trash'=> __( 'No feeds found in Trash', 'roboblog' ),
        'menu_name'         => _x( 'RoboBlog', 'rbfeed post type menu name', 'roboblog' )
    ) );

    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'publicly_queryable'=> true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'hierarchical'      => false,
        'menu_position'     => 1815215212157,
        'supports'          => apply_filters( 'roboblog_feed_supports', array( 'title' ) ),
    );

    register_post_type( 'rbfeed', apply_filters( 'roboblog_feed_post_type_args', $args ) );
}
add_action( 'init', 'roboblog_register_post_types', 1 );


/**
 * Change default "Enter title here" placeholder
 *
 * @since       1.0.0
 * @param       string $title The default placeholder
 * @return      string $title The updated placeholder
 */
function roboblog_enter_title_here( $title ) {
    $screen = get_current_screen();

    if( $screen->post_type = 'rbfeed' ) {
        $title = __( 'Enter feed title here (will not be shown publicly)', 'roboblog' );
    }

    return $title;
}
add_filter( 'enter_title_here', 'roboblog_enter_title_here' );


/**
 * Update messages
 *
 * @since       1.0.0
 * @param       array $messages The default messages
 * @return      array $messages The updated messages
 */
function roboblog_updated_messages( $messages ) {
    $messages['rbfeed'] = array(
        1 => __( 'Feed updated.', 'roboblog' ),
        4 => __( 'Feed updated.', 'roboblog' ),
        6 => __( 'Feed published.', 'roboblog' ),
        7 => __( 'Feed saved.', 'roboblog' ),
        8 => __( 'Feed submitted.', 'roboblog' )
    );

    return $messages;
}
add_filter( 'post_updated_messages', 'roboblog_updated_messages' );


/**
 * Updated bulk messages
 *
 * @since       1.0.0
 * @param       array $bulk_messages Post updated messages
 * @param       array $bulk_counts Post counts
 * @return      array $bulk_messages Updated post updated messages
 */
function roboblog_bulk_updated_messages( $bulk_messages, $bulk_counts ) {
    $bulk_messages['rbfeed'] = array(
        'updated'   => sprintf( _n( '%1$s feed updated.', '%1$s feeds updated.', $bulk_counts['updated'], 'roboblog' ), $bulk_counts['updated'] ),
        'locked'    => sprintf( _n( '%1$s feed not updated, somebody is editing it.', '%1$s feeds not updated, somebody is editing them.', $bulk_counts['locked'], 'roboblog' ), $bulk_counts['locked'] ),
        'deleted'   => sprintf( _n( '%1$s feed permanently deleted.', '%1$s feeds permanently deleted.', $bulk_counts['deleted'], 'roboblog' ), $bulk_counts['deleted'] ),
        'trashed'   => sprintf( _n( '%1$s feed moved to the Trash.', '%1$s feeds moved to the Trash.', $bulk_counts['trashed'], 'roboblog' ), $bulk_counts['trashed'] ),
        'untrashed' => sprintf( _n( '%1$s feed restored from the Trash.', '%1$s feeds restored from the Trash.', $bulk_counts['untrashed'], 'roboblog' ), $bulk_counts['untrashed'] )
    );

    return $bulk_messages;
}
add_filter( 'bulk_post_updated_messages', 'roboblog_bulk_updated_messages', 10, 2 );
