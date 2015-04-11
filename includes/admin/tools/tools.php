<?php
/**
 * Tools
 *
 * @package     RoboBlog\Admin\Pages\Tools
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Render the tools page
 *
 * @since       1.0.0
 * @return      void
 */
function roboblog_render_tools_page() {
    $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'system_info';
    ?>
    <div class="wrap">
        <?php screen_icon(); ?>
        <h2 class="nav-tab-wrapper">
            <?php
            foreach( roboblog_get_tools_tabs() as $tab_id => $tab_name ) {
                $tab_url = add_query_arg( array(
                    'tab'           => $tab_id,
                    'roboblog-message'   => false
                ) );

                $active = $active_tab == $tab_id ? ' nav-tab-active' : '';
                echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name ) . '" class="nav-tab' . $active . '">' . esc_html( $tab_name ) . '</a>';
            }
            ?>
        </h2>
        <div class="metabox-holder">
            <?php do_action( 'roboblog_tools_tab_' . $active_tab ); ?>
        </div>
    </div>
    <?php
}


/**
 * Retrieve tools tabs
 *
 * @since       1.0.0
 * @return      array $tabs The available tools tabs
 */
function roboblog_get_tools_tabs() {
    $tabs = array(
        'system_info'   => __( 'System Info', 'roboblog' )
    );

    return apply_filters( 'roboblog_tools_tabs', $tabs );
}
