<?php
class Test_Misc extends WP_UnitTestCase {
    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function test_get_ip() {
        $this->assertEquals( '127.0.0.1', roboblog_get_ip() );
    }

    public function test_get_host() {
        define( 'WPE_APIKEY', 'testkey' );

        $this->assertEquals( 'WP Engine', roboblog_get_host() );
    }
}
