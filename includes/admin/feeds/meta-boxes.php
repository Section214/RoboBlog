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


/**
 * Register new meta boxes
 *
 * @since       1.0.0
 * @return      void
 */
function roboblog_add_meta_boxes() {
    add_meta_box( 'feed_general', __( 'General', 'roboblog' ), 'roboblog_render_feed_general_meta_box', 'rbfeed', 'normal', 'default' );
}
add_action( 'add_meta_boxes', 'roboblog_add_meta_boxes' );


/**
 * Render the general config meta box
 *
 * @since       1.0.0
 * @global      object $post The WordPress object for this post
 * @return      void
 */
function roboblog_render_feed_general_meta_box() {
    global $post;

    $feed_url       = get_post_meta( $post->ID, '_roboblog_feed_url', true );
    $post_type      = get_post_meta( $post->ID, '_roboblog_post_type', true );

    // Feed URL
    $html  = '<p class="rbfeed-field">';
    $html .= '<label for="_roboblog_feed_url"><strong>' . __( 'Feed URL', 'roboblog' ) . '</strong></label><br />';
    $html .= '<input type="text" class="widefat" name="_roboblog_feed_url" id="_roboblog_feed_url" value="' . $feed_url . '" placeholder="http://" />';
    $html .= '</p>';

    // Post type
    $html .= '<p class="rbfeed-field">';
    $html .= '<label for="_roboblog_post_type"><strong>' . __( 'Post Type', 'roboblog' ) . '</strong></label><br />';
    $html .= '<select name="_roboblog_post_type" id="_roboblog_post_type">';

    foreach( roboblog_get_post_types() as $id => $title ) {
        $html .= '<option value="' . $id . '"' . ( $post_type && $post_type == $id ? ' selected="selected"' : '' ) . '>' . $title . '</option>';
    }

    $html .= '</select>';
    $html .= '</p>';

    echo $html;

    // Allow extension of the meta box
    do_action( 'roboblog_feed_general_fields', $post->ID );

    wp_nonce_field( basename( __FILE__ ), 'roboblog_feed_nonce' );
}


/**
 * Save post meta when the save_post action is called
 *
 * @since       1.0.0
 * @param       int $post_id The ID of the post we are saving
 * @global      object $post The WordPress object for this post
 * @return      void
 */
function roboblog_save_feed_meta_boxes( $post_id ) {
    global $post;

    // Bail if this isn't the feed post type
    if( ! isset( $post->post_type ) || $post->post_type != 'rbfeed' ) {
        return $post_id;
    }

    // Bail if nonce can't be validated
    if( ! isset( $_POST['roboblog_feed_nonce'] ) || ! wp_verify_nonce( $_POST['roboblog_feed_nonce'], basename( __FILE__ ) ) ) {
        return $post_id;
    }

    // Bail if this is an autosave or bulk edit
    if( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) ) {
        return $post_id;
    }

    // Bail if this is a revision
    if( $post->post_type == 'revision' ) {
        return $post_id;
    }

    // Bail if the current user shouldn't be editing this
    if( ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }

    // The fields to save
    $fields = apply_filters( 'roboblog_feed_fields_save', array(
        '_roboblog_feed_url'
    ) );

    foreach( $fields as $field ) {
        if( isset( $_POST[$field] ) ) {
            if( is_string( $_POST[$field] ) ) {
                $new = esc_attr( $_POST[$field] );
            } else {
                $new = $_POST[$field];
            }

            $new = apply_filters( 'roboblog_feed_save_' . $field, $new );
            update_post_meta( $post_id, $field, $new );
        } else {
            delete_post_meta( $post_id, $field );
        }
    }
}
add_action( 'save_post', 'roboblog_save_feed_meta_boxes' );
