<?php
/**
 * Welcome page
 *
 * @package     RoboBlog\Admin\Welcome
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * RoboBlog_Welcome class
 *
 * @since       1.0.0
 */
class RoboBlog_Welcome {


    /**
     * @access      public
     * @since       1.0.0
     * @var         string $minimum_capability The capability users should have to view the page
     */
    public $minimum_capability = 'manage_options';


    /**
     * Get things started
     *
     * @access      public
     * @since       1.0.0
     * @return      void
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menus' ) );
        add_action( 'admin_head', array( $this, 'admin_head' ) );
        add_action( 'admin_init', array( $this, 'welcome' ), 9999 );
    }


    /**
     * Register the dashboard pages for the welcome pages
     *
     * @access      public
     * @since       1.0.0
     * @return      void
     */
    public function admin_menus() {
//        add_dashboard_page( __( 'Welcome to RoboBlog', 'roboblog' ), __( 'Welcome to RoboBlog', 'roboblog' ), $this->minimum_capability, 'roboblog-about', array( $this, 'about_screen' ) );
        add_dashboard_page( __( 'Getting started with RoboBlog', 'roboblog' ), __( 'Getting started with RoboBlog', 'roboblog' ), $this->minimum_capability, 'roboblog-getting-started', array( $this, 'getting_started_screen' ) );
    }


    /**
     * Hide dashboard pages
     *
     * @access      public
     * @since       1.0.0
     * @return      void
     */
    public function admin_head() {
//        remove_submenu_page( 'index.php', 'roboblog-about' );
        remove_submenu_page( 'index.php', 'roboblog-getting-started' );

        $badge_url = ROBOBLOG_URL . 'assets/img/roboblog-badge.png';
        ?>
        <style type="text/css" media="screen">
            /*<![CDATA[*/
            .roboblog-badge {
                height: 80px;
                width: 145px;
                color: #34363e;
                position: relative;
                font-weight: bold;
                font-size: 14px;
                text-align: center;
                background: url( '<?php echo $badge_url; ?>' ) no-repeat;
            }

            .roboblog-badge span {
                position: absolute;
                bottom: -30px;
                left: 0;
                width: 100%;
            }

            .about-wrap .roboblog-badge {
                position: absolute;
                top: 0;
                right: 0;
            }

            .roboblog-welcome-screenshots {
                float: right;
                margin-left: 10px !important;
            }
            /*]]>*/
        </style>
        <?php
    }


    /**
     * Navigation tabs
     *
     * @access      public
     * @since       1.0.0
     * @return      void
     */
    public function tabs() {
        $selected = isset( $_GET['page'] ) ? $_GET['page'] : 'roboblog-about';
        ?>
        <h2 class="nav-tab-wrapper">
            <a style="display: none" class="nav-tab<?php echo $selected == 'roboblog-about' ? ' nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'roboblog-about' ), 'index.php' ) ) ); ?>">
                <?php _e( 'What\'s New', 'roboblog' ); ?>
            </a>
            <a class="nav-tab<?php echo $selected == 'roboblog-getting-started' ? ' nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'roboblog-getting-started' ), 'index.php' ) ) ); ?>">
                <?php _e( 'Getting Started', 'roboblog' ); ?>
            </a>
        </h2>
        <?php
    }


    /**
     * Render About screen
     *
     * @access      public
     * @since       1.0.0
     * @return      void
     */
    public function about_screen() {
        list( $display_version ) = explode( '-', ROBOBLOG_VER );
        ?>
        <div class="wrap about-wrap">
            <h1><?php printf( __( 'Welcome to RoboBlog %s', 'roboblog' ), esc_html( $display_version ) ); ?></h1>
            <div class="about-text"><?php printf( __( 'Thank you for purchasing RoboBlog! RoboBlog %s is ready to make your content farming faster, safer, and better!', 'roboblog' ), $display_version ); ?></div>
            <div class="roboblog-badge"><span><?php printf( __( 'Version %s', 'roboblog' ), esc_html( $display_version ) ); ?></span></div>

            <?php $this->tabs(); ?>

            <div class="changelog">
                <h3><?php _e( 'Initial Release!', 'roboblog' ); ?></h3>

                <div class="feature-section">
                    <p>Yep...</p>
                </div>
            </div>

            <div class="return-to-dashboard">
                <a href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => 'rbfeed', 'page' => 'roboblog-settings' ), 'edit.php' ) ) ); ?>"><?php _e( 'Go to RoboBlog Settings', 'roboblog' ); ?></a>
            </div>
        </div>
        <?php
    }


    /**
     * Render Getting Started screen
     *
     * @access      public
     * @since       1.0.0
     * @return      void
     */
    public function getting_started_screen() {
        list( $display_version ) = explode( '-', ROBOBLOG_VER );
        ?>
        <div class="wrap about-wrap">
            <h1><?php printf( __( 'Welcome to RoboBlog %s', 'roboblog' ), esc_html( $display_version ) ); ?></h1>
            <div class="about-text"><?php printf( __( 'Thank you for purchasing RoboBlog! RoboBlog %s is ready to make your content farming faster, safer, and better!', 'roboblog' ), $display_version ); ?></div>
            <div class="roboblog-badge"><span><?php printf( __( 'Version %s', 'roboblog' ), esc_html( $display_version ) ); ?></span></div>

            <?php $this->tabs(); ?>

            <div class="changelog">
                <h3><?php _e( 'Use the tips below to get started using RoboBlog. You\'ll be up and running in no time!', 'roboblog' ); ?></h3>

                <div class="feature-section">
                    <p>Yep...</p>
                </div>
            </div>

            <div class="return-to-dashboard">
                <a href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => 'rbfeed', 'page' => 'roboblog-settings' ), 'edit.php' ) ) ); ?>"><?php _e( 'Go to RoboBlog Settings', 'roboblog' ); ?></a>
            </div>
        </div>
        <?php
    }


    /**
     * Sends user to the Welcome page on first activation and each upgrade
     *
     * @access      public
     * @since       1.0.0
     * @return      void
     */
    public function welcome() {
        // Bail if no activation redirect
        if( ! get_transient( '_roboblog_activation_redirect' ) ) {
            return;
        }

        // Delete the transient
        delete_transient( '_roboblog_activation_redirect' );

        // Bail if activating from network/bulk
        if( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
            return;
        }

        $upgrade = get_option( 'roboblog_version_upgraded_from' );

        if( ! $upgrade ) {
            wp_safe_redirect( admin_url( 'index.php?page=roboblog-getting-started' ) );
            exit;
        } else {
            wp_safe_redirect( admin_url( 'index.php?page=roboblog-about' ) );
            exit;
        }
    }
}
new RoboBlog_Welcome();
