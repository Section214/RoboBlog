<?php
/**
 * System info
 *
 * @package     RoboBlog\Admin\Pages\Tools\SysInfo
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Display the system info tab
 *
 * @since       1.0.0
 * @return      void
 */
function roboblog_tools_sysinfo_display() {
    if( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <form action="<?php echo esc_url( admin_url( 'admin.php?page=roboblog-tools&tab=system_info' ) ); ?>" method="post" dir="ltr">
        <textarea readonly="readonly" onclick="this.focus(); this.select()" id="system-info-textarea" name="roboblog-sysinfo" title="<?php _e( 'To copy the system info, click below then press Ctrl+C (PC) or Cmd+C (Mac).', 'roboblog' ); ?>"><?php echo roboblog_tools_sysinfo_get(); ?></textarea>
        <p class="submit">
            <input type="hidden" name="roboblog-action" value="download_sysinfo" />
            <?php submit_button( __( 'Download System Info File', 'roboblog' ), 'primary', 'roboblog-download-sysinfo', false ); ?>
        </p>
    </form>
    <?php
}
add_action( 'roboblog_tools_tab_system_info', 'roboblog_tools_sysinfo_display' );


/**
 * Get system info
 *
 * @since       1.0.0
 * @global      object $wpdb Used to query the database using the WordPress Database API
 * @return      string $return The system info
 */
function roboblog_tools_sysinfo_get() {
    global $wpdb;

    if( ! class_exists( 'Browser' ) ) {
        require_once ROBOBLOG_DIR . 'includes/libraries/browser.php';
    }

    $browser = new Browser();

    // Get theme info
    $theme_data = wp_get_theme();
    $theme      = $theme_data->get( 'Name' ) . ' ' . $theme_data->get( 'Version' ) . ( $theme_data->get( 'ThemeURI' ) !== false ? ' (' . $theme_data->get( 'ThemeURI' ) . ')' : '' );

    // Try to identify the hosting provider
    $host = roboblog_get_host();

    $return = '### Begin System Info ###' . "\n\n";

    // Start with the basics...
    $return .= '-- Site Info' . "\n\n";
    $return .= 'Site URL:                 ' . site_url() . "\n";
    $return .= 'Home URL:                 ' . home_url() . "\n";
    $return .= 'Multisite:                ' . ( is_multisite() ? 'Yes' : 'No' ) . "\n";

    $return  = apply_filters( 'roboblog_sysinfo_after_site_info', $return );

    $return .= "\n" . '-- Hosting Provider' . "\n\n";
    $return .= 'Host:                     ' . $host . "\n";

    $return  = apply_filters( 'roboblog_sysinfo_after_host_info', $return );

    // The local users' browser information, handled by the Browser class
    $return .= "\n" . '-- User Browser' . "\n\n";
    $return .= $browser;

    $return  = apply_filters( 'roboblog_sysinfo_after_user_browser', $return );

    // WordPress configuration
    $return .= "\n" . '-- WordPress Configuration' . "\n\n";
    $return .= 'Version:                  ' . get_bloginfo( 'version' ) . "\n";
    $return .= 'Language:                 ' . ( defined( 'WPLANG' ) && WPLANG ? WPLANG : 'en_US' ) . "\n";
    $return .= 'Permalink Structure:      ' . ( get_option( 'permalink_structure' ) ? get_option( 'permalink_structure' ) : 'Default' ) . "\n";
    $return .= 'Active Theme:             ' . $theme . "\n";
    $return .= 'Show On Front:            ' . get_option( 'show_on_front' ) . "\n";

    // Only show page specs if frontpage is set to 'page'
    if( get_option( 'show_on_front' ) == 'page' ) {
        $front_page_id = get_option( 'page_on_front' );
        $blog_page_id = get_option( 'page_for_posts' );

        $return .= 'Page On Front:            ' . ( $front_page_id != 0 ? get_the_title( $front_page_id ) . ' (#' . $front_page_id . ')' : 'Unset' ) . "\n";
        $return .= 'Page For Posts:           ' . ( $blog_page_id != 0 ? get_the_title( $blog_page_id ) . ' (#' . $blog_page_id . ')' : 'Unset' ) . "\n";
    }

    // Make sure wp_remote_post() is working
    $request['cmd'] = '_notify-validate';

    $params = array(
        'sslverify'     => false,
        'timeout'       => 60,
        'user-agent'    => 'ROBOBLOG/' . ROBOBLOG_VER,
        'body'          => $request
    );

    $response = wp_remote_post( 'https://www.paypal.com/cgi-bin/webscr', $params );

    if( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
        $WP_REMOTE_POST = 'wp_remote_post() works';
    } else {
        $WP_REMOTE_POST = 'wp_remote_post() does not work';
    }

    $return .= 'Remote Post:              ' . $WP_REMOTE_POST . "\n";
    $return .= 'Table Prefix:             ' . 'Length: ' . strlen( $wpdb->prefix ) . '   Status: ' . ( strlen( $wpdb->prefix ) > 16 ? 'ERROR: Too long' : 'Acceptable' ) . "\n";
    $return .= 'Admin AJAX:               ' . ( roboblog_test_ajax_works() ? 'Accessible' : 'Inaccessible' ) . "\n";
    $return .= 'WP_DEBUG:                 ' . ( defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' : 'Disabled' : 'Not set' ) . "\n";
    $return .= 'SCRIPT_DEBUG:             ' . ( defined( 'SCRIPT_DEBUG' ) ? SCRIPT_DEBUG ? 'Enabled' : 'Disabled' : 'Not set' ) . "\n";
    $return .= 'Memory Limit:             ' . WP_MEMORY_LIMIT . "\n";
    $return .= 'Registered Post Stati:    ' . implode( ', ', get_post_stati() ) . "\n";

    $return  = apply_filters( 'roboblog_sysinfo_after_wordpress_config', $return );

    // RoboBlog configuration
    $return .= "\n" . '-- RoboBlog Configuration' . "\n\n";
    $return .= 'Version:                  ' . ROBOBLOG_VER . "\n";
    $return .= 'Upgraded From:            ' . get_option( 'roboblog_version_upgraded_from', 'None' ) . "\n";
    
    $return  = apply_filters( 'roboblog_sysinfo_after_edd_config', $return );

    // Must-use plugins
    $muplugins = get_mu_plugins();
    if( count( $muplugins ) > 0 ) {
        $return .= "\n" . '-- Must-Use Plugins' . "\n\n";

        foreach( $muplugins as $plugin => $plugin_data ) {
            $return .= $plugin_data['Name'] . ': ' . $plugin_data['Version'] . ( isset( $plugin['PluginURI'] ) ? ' (' . $plugin['PluginURI'] . ')' : '' ) . "\n";
        }

        $return = apply_filters( 'roboblog_sysinfo_after_wordpress_mu_plugins', $return );
    }

    // WordPress active plugins
    $return .= "\n" . '-- WordPress Active Plugins' . "\n\n";

    $plugins = get_plugins();
    $active_plugins = get_option( 'active_plugins', array() );

    foreach( $plugins as $plugin_path => $plugin ) {
        if( !in_array( $plugin_path, $active_plugins ) )
            continue;

        $return .= $plugin['Name'] . ': ' . $plugin['Version'] . ( isset( $plugin['PluginURI'] ) ? ' (' . $plugin['PluginURI'] . ')' : '' ) . "\n";
    }

    $return  = apply_filters( 'roboblog_sysinfo_after_wordpress_plugins', $return );

    // WordPress inactive plugins
    $return .= "\n" . '-- WordPress Inactive Plugins' . "\n\n";

    foreach( $plugins as $plugin_path => $plugin ) {
        if( in_array( $plugin_path, $active_plugins ) )
            continue;

        $return .= $plugin['Name'] . ': ' . $plugin['Version'] . ( isset( $plugin['PluginURI'] ) ? ' (' . $plugin['PluginURI'] . ')' : '' ) . "\n";
    }

    $return  = apply_filters( 'roboblog_sysinfo_after_wordpress_plugins_inactive', $return );

    if( is_multisite() ) {
        // WordPress Multisite active plugins
        $return .= "\n" . '-- Network Active Plugins' . "\n\n";

        $plugins = wp_get_active_network_plugins();
        $active_plugins = get_site_option( 'active_sitewide_plugins', array() );

        foreach( $plugins as $plugin_path ) {
            $plugin_base = plugin_basename( $plugin_path );

            if( !array_key_exists( $plugin_base, $active_plugins ) )
                continue;

            $plugin  = get_plugin_data( $plugin_path );
            $return .= $plugin['Name'] . ': ' . $plugin['Version'] . ( isset( $plugin['PluginURI'] ) ? ' (' . $plugin['PluginURI'] . ')' : '' ) . "\n";
        }

        $return  = apply_filters( 'roboblog_sysinfo_after_wordpress_ms_plugins', $return );
    }

    // Server configuration (really just versioning)
    $return .= "\n" . '-- Webserver Configuration' . "\n\n";
    $return .= 'PHP Version:              ' . PHP_VERSION . "\n";
    $return .= 'MySQL Version:            ' . $wpdb->db_version() . "\n";
    $return .= 'Webserver Info:           ' . $_SERVER['SERVER_SOFTWARE'] . "\n";

    $return  = apply_filters( 'roboblog_sysinfo_after_webserver_config', $return );

    // PHP configs... now we're getting to the important stuff
    $return .= "\n" . '-- PHP Configuration' . "\n\n";
    $return .= 'Safe Mode:                ' . ( ini_get( 'safe_mode' ) ? 'Enabled' : 'Disabled' . "\n" );
    $return .= 'Memory Limit:             ' . ini_get( 'memory_limit' ) . "\n";
    $return .= 'Upload Max Size:          ' . ini_get( 'upload_max_filesize' ) . "\n";
    $return .= 'Post Max Size:            ' . ini_get( 'post_max_size' ) . "\n";
    $return .= 'Upload Max Filesize:      ' . ini_get( 'upload_max_filesize' ) . "\n";
    $return .= 'Time Limit:               ' . ini_get( 'max_execution_time' ) . "\n";
    $return .= 'Max Input Vars:           ' . ini_get( 'max_input_vars' ) . "\n";
    $return .= 'Display Errors:           ' . ( ini_get( 'display_errors' ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A' ) . "\n";

    $return  = apply_filters( 'roboblog_sysinfo_after_php_config', $return );

    // PHP extensions and such
    $return .= "\n" . '-- PHP Extensions' . "\n\n";
    $return .= 'cURL:                     ' . ( function_exists( 'curl_init' ) ? 'Supported' : 'Not Supported' ) . "\n";
    $return .= 'fsockopen:                ' . ( function_exists( 'fsockopen' ) ? 'Supported' : 'Not Supported' ) . "\n";
    $return .= 'SOAP Client:              ' . ( class_exists( 'SoapClient' ) ? 'Installed' : 'Not Installed' ) . "\n";
    $return .= 'Suhosin:                  ' . ( extension_loaded( 'suhosin' ) ? 'Installed' : 'Not Installed' ) . "\n";

    $return  = apply_filters( 'roboblog_sysinfo_after_php_ext', $return );

    // Session stuff
    $return .= "\n" . '-- Session Configuration' . "\n\n";
    $return .= 'Session:                  ' . ( isset( $_SESSION ) ? 'Enabled' : 'Disabled' ) . "\n";

    // The rest of this is only relevant is session is enabled
    if( isset( $_SESSION ) ) {
        $return .= 'Session Name:             ' . esc_html( ini_get( 'session.name' ) ) . "\n";
        $return .= 'Cookie Path:              ' . esc_html( ini_get( 'session.cookie_path' ) ) . "\n";
        $return .= 'Save Path:                ' . esc_html( ini_get( 'session.save_path' ) ) . "\n";
        $return .= 'Use Cookies:              ' . ( ini_get( 'session.use_cookies' ) ? 'On' : 'Off' ) . "\n";
        $return .= 'Use Only Cookies:         ' . ( ini_get( 'session.use_only_cookies' ) ? 'On' : 'Off' ) . "\n";
    }

    $return  = apply_filters( 'roboblog_sysinfo_after_session_config', $return );

    $return .= "\n" . '### End System Info ###';

    return $return;
}


/**
 * Generates a System Info download file
 *
 * @since       1.0.0
 * @return      void
 */
function roboblog_tools_sysinfo_download() {
    if( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    nocache_headers();

    header( 'Content-Type: text/plain' );
    header( 'Content-Disposition: attachment; filename="roboblog-system-info.txt"' );

    echo wp_strip_all_tags( $_POST['roboblog-sysinfo'] );
    die();
}
add_action( 'roboblog_download_sysinfo', 'roboblog_tools_sysinfo_download' );
