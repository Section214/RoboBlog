<?php
/**
 * Plugin Name:     RoboBlog
 * Plugin URI:      http://section214.com
 * Description:     Content farming automation for WordPress
 * Version:         1.0.0
 * Author:          Daniel J Griffiths
 * Author URI:      http://section214.com
 * Text Domain:     roboblog
 *
 * @package         RoboBlog
 * @author          Daniel J Griffiths <dgriffiths@section214.com>
 * @copyright       Copyright (c) 2015, Daniel J Griffiths
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
    exit;
}


if( ! class_exists( 'RoboBlog' ) ) {


    /**
     * Main RoboBlog class
     *
     * @since       1.0.0
     */
    class RoboBlog {


        /**
         * @access      private
         * @since       1.0.0
         * @var         RoboBlog $instance The one true RoboBlog
         */
        private static $instance;


        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      self::$instance The one true RoboBlog
         */
        public static function instance() {
            if( ! self::$instance ) {
                self::$instance = new RoboBlog();
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->hooks();
            }

            return self::$instance;
        }


        /**
         * Setup plugin constants
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {
            // Plugin version
            define( 'ROBOBLOG_VER', '1.0.0' );

            // Plugin path
            define( 'ROBOBLOG_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'ROBOBLOG_URL', plugin_dir_url( __FILE__ ) );

            // Plugin file
            define( 'ROBOBLOG_FILE', __FILE__ );
        }


        /**
         * Include required files
         *
         * @access      private
         * @since       1.0.0
         * @global      array $roboblog_options The RoboBlog options array
         * @return      void
         */
        private function includes() {
            global $roboblog_options;

            require_once ROBOBLOG_DIR . 'includes/admin/settings/register.php';
            $roboblog_options = roboblog_get_settings();

            require_once ROBOBLOG_DIR . 'includes/scripts.php';
            require_once ROBOBLOG_DIR . 'includes/functions.php';
            require_once ROBOBLOG_DIR . 'includes/ajax-functions.php';
            require_once ROBOBLOG_DIR . 'includes/post-types.php';

            if( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
                require_once ROBOBLOG_DIR . 'includes/admin/actions.php';
                require_once ROBOBLOG_DIR . 'includes/admin/pages.php';
                require_once ROBOBLOG_DIR . 'includes/admin/settings/display.php';
                require_once ROBOBLOG_DIR . 'includes/admin/tools/tools.php';
                require_once ROBOBLOG_DIR . 'includes/admin/tools/sysinfo.php';
                require_once ROBOBLOG_DIR . 'includes/admin/welcome.php';
            }

            require_once ROBOBLOG_DIR . 'includes/install.php';
        }


        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function hooks() {
        
        }


        /**
         * Internationalization
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function load_textdomain() {
            // Set filter for language directory
            $lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
            $lang_dir = apply_filters( 'roboblog_language_directory', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale = apply_filters( 'plugin_locale', get_locale(), '' );
            $mofile = sprintf( '%1$s-%2$s.mo', 'roboblog', $locale );

            // Setup paths to current locale file
            $mofile_local   = $lang_dir . $mofile;
            $mofile_global  = WP_LANG_DIR . '/roboblog' . $mofile;

            if( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/roboblog/ folder
                load_textdomain( 'roboblog', $mofile_global );
            } elseif( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/roboblog/languages/ folder
                load_textdomain( 'roboblog', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'roboblog', false, $lang_dir );
            }
        }
    }
}


/**
 * The main function responsible for returning the one true RoboBlog
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      RoboBlog The one true RoboBlog
 */
function roboblog() {
    return RoboBlog::instance();
}
roboblog();
