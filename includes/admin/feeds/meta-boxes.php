<?php
/**
 * Meta boxes
 *
 * @package     RoboBlog\Admin\Feeds\MetaBoxes
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Change the label for the featured image meta box
 *
 * @since       1.0.0
 * @return      void
 */
function roboblog_change_featured_image_label() {
    remove_meta_box( 'postimagediv', 'rbfeed', 'side' );
    add_meta_box( 'postimagediv', __( 'Default Image', 'roboblog' ), 'post_thumbnail_meta_box', 'rbfeed', 'side', 'default' );
}
add_action( 'do_meta_boxes', 'roboblog_change_featured_image_label' );


/**
 * Change the featured image meta box link text
 *
 * @since       1.0.0
 * @param       string $content The HTML for the featured image meta box
 * @param       int $post_id The ID of this post
 * @global      string $typenow The post type we are working with
 * @return      string $content The updated HTML for the featured image meta box
 */
function roboblog_change_featured_image_text( $content, $post_id ) {
    global $typenow;

    if( $typenow == 'rbfeed' ) {
        $content = str_replace( __( 'Set featured image' ), __( 'Set default image', 'roboblog' ), $content );
        $content = $content . '<p class="description">' . __( 'If no default image is set, the system-wide default will be used.', 'roboblog' ) . '</p>';
    }

    return $content;
}
add_filter( 'admin_post_thumbnail_html', 'roboblog_change_featured_image_text', 10, 2 );


/**
 * Change the Add to post button in the media manager
 *
 * @since       1.0.0
 * @param       array $strings Array of default strings for the media manager
 * @global      object $post The WordPress object for this post
 * @return      array $strings Updated array of strings for the media manager
 */
function roboblog_rebrand_media_manager( $strings ) {
    global $post;

    if( $post && $post->post_type == 'rbfeed' ) {
        $strings['insertIntoPost']          = __( 'Set default image', 'roboblog' );
        $strings['setFeaturedImageTitle']   = __( 'Set Default Image', 'roboblog' );
        $strings['setFeaturedImage']        = __( 'Set default image', 'roboblog' );
    }

    return $strings;
}
add_filter( 'media_view_strings', 'roboblog_rebrand_media_manager', 10, 1 );
