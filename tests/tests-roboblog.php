<?php
class Tests_RoboBlog extends WP_UnitTestCase {
    protected $object;

    public function setUp() {
        parent::setUp();
        $this->object = RoboBlog();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function test_roboblog_instance() {
        $this->assertClassHasStaticAttribute( 'instance', 'RoboBlog' );
    }

    public function test_constants() {
        $path = str_replace( 'tests/', '', plugin_dir_url( __FILE__ ) );
        $this->assertSame( ROBOBLOG_URL, $path );

        $path = str_replace( 'tests/', '', plugin_dir_path( __FILE__ ) );
        $this->assertSame( ROBOBLOG_DIR, $path );

        $path = str_replace( 'tests/', '', plugin_dir_path( __FILE__ ) );
        $this->assertSame( ROBOBLOG_FILE, $path . 'roboblog.php' );
    }

    public function test_includes() {
        $this->assertFileExists( ROBOBLOG_DIR . 'includes/admin/settings/register.php' );
        $this->assertFileExists( ROBOBLOG_DIR . 'includes/install.php' );
        $this->assertFileExists( ROBOBLOG_DIR . 'includes/ajax-functions.php' );
        $this->assertFileExists( ROBOBLOG_DIR . 'includes/functions.php' );
        $this->assertFileExists( ROBOBLOG_DIR . 'includes/scripts.php' );
        $this->assertFileExists( ROBOBLOG_DIR . 'includes/post-types.php' );
        $this->assertFileExists( ROBOBLOG_DIR . 'includes/admin/actions.php' );
        $this->assertFileExists( ROBOBLOG_DIR . 'includes/admin/pages.php' );
        $this->assertFileExists( ROBOBLOG_DIR . 'includes/admin/settings/display.php' );
        $this->assertFileExists( ROBOBLOG_DIR . 'includes/admin/welcome.php' );

        $this->assertFileExists( ROBOBLOG_DIR . 'assets/css/admin.css' );
        $this->assertFileExists( ROBOBLOG_DIR . 'assets/css/jquery-ui-classic.css' );
        $this->assertFileExists( ROBOBLOG_DIR . 'assets/css/jquery-ui-fresh.css' );
        $this->assertFileExists( ROBOBLOG_DIR . 'assets/img/roboblog-badge.png' );
        $this->assertFileExists( ROBOBLOG_DIR . 'assets/js/admin.js' );
    }
}
