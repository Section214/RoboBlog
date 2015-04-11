<?php
class Tests_Install extends WP_UnitTestCase {
    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function test_settings() {
        global $roboblog_options;

        // $this->assertArrayHasKey( 'something', $roboblog_options );
    }

    public function test_install() {
        global $roboblog_options;

        $origin_roboblog_options= $roboblog_options;
        $origin_upgraded_from   = get_option( 'roboblog_version_upgraded_from' );
        $origin_roboblog_version= get_option( 'roboblog_version' );

        update_option( 'roboblog_version', '0.0.1' );

        $roboblog_options = array();

        roboblog_install();

        $this->assertEquals( get_option( 'roboblog_version_upgraded_from' ), '0.0.1' );
        $this->assertEquals( ROBOBLOG_VER, get_option( 'roboblog_version' ) );

        $this->assertNotFalse( get_transient( '_roboblog_activation_redirect' ) );

        // Reset
        update_option( 'roboblog_version_upgraded_from', $origin_upgraded_from );
        $roboblog_options = $origin_roboblog_options;
        update_option( 'roboblog_version', $origin_roboblog_version );
    }

    public function test_install_bail() {
        $_GET['activate-multi'] = 1;

        roboblog_install();

        $this->assertFalse( get_transient( 'activate-multi' ) );
    }
}
