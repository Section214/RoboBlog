<?php
/**
 * Admin pages
 *
 * @package     RoboBlog\Admin\Pages
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Create the settings menu pages
 *
 * @since       1.0.0
 * @global      string $roboblog_settings_page The RoboBlog settings page hook
 * @global      string $roboblog_tools_page The RoboBlog tools page hook
 * @global      string $roboblog_addons_page The RoboBlog addons page hook
 * @global      string $roboblog_upgrades_page The RoboBlog upgrades page hook
 * @return      void
 */
function roboblog_add_settings_pages() {
    global $roboblog_settings_page, $roboblog_tools_page, $roboblog_addons_page, $roboblog_upgrades_page;

    $roboblog_settings_page = add_submenu_page( 'edit.php?post_type=rbfeed', __( 'RoboBlog Settings', 'roboblog' ), __( 'Settings', 'roboblog' ), 'manage_options', 'roboblog-settings', 'roboblog_render_settings_page' );
    $roboblog_tools_page    = add_submenu_page( 'edit.php?post_type=rbfeed', __( 'RoboBlog Tools', 'roboblog' ), __( 'Tools', 'roboblog' ), 'manage_options', 'roboblog-tools', 'roboblog_render_tools_page' );
//    $roboblog_addons_page   = add_submenu_page( 'edit.php?post_type=rbfeed', __( 'RoboBlog Addons', 'roboblog' ), __( 'Addons', 'roboblog' ), 'install_plugins', 'roboblog-addons', 'roboblog_render_addons_page' );
    $roboblog_upgrades_page = add_submenu_page( null, __( 'RoboBlog Upgrades', 'roboblog' ), __( 'RoboBlog Upgrades', 'roboblog' ), 'manage_options', 'roboblog-upgrades', 'roboblog_render_upgrades_page' );
}
add_action( 'admin_menu', 'roboblog_add_settings_pages', 10 );


/**
 * Determines whether or not the current admin page is an RoboBlog page
 *
 * @since       1.0.0
 * @param       string $hook The hook for this page
 * @global      string $typenow The post type we are viewing
 * @global      string $pagenow The page we are viewing
 * @global      string $roboblog_settings_page The RoboBlog settings page hook
 * @global      string $roboblog_tools_page The RoboBlog tools page hook
 * @global      string $roboblog_addons_page The RoboBlog addons page hook
 * @global      string $roboblog_upgrades_page The RoboBlog upgrades page hook
 * @return      bool $ret True if RoboBlog page, false otherwise
 */
function roboblog_is_admin_page( $hook ) {
    global $typenow, $pagenow, $roboblog_settings_page, $roboblog_tools_page, $roboblog_addons_page, $roboblog_upgrades_page;

    $ret    = false;
    $pages  = apply_filters( 'roboblog_admin_pages', array( $roboblog_settings_page, $roboblog_tools_page, $roboblog_addons_page, $roboblog_upgrades_page ) );

    if( $typenow == 'rbfeed' ) {
        $ret = true;
    } elseif( in_array( $hook, $pages ) ) {
        $ret = true;
    }

    return (bool) apply_filters( 'roboblog_is_admin_page', $ret );
}
