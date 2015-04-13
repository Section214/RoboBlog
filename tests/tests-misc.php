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

    public function test_validate_feed() {
        $valid_feed     = roboblog_validate_feed( 'http://section214.com/feed/' );
        $invalid_feed   = roboblog_validate_feed( 'http://sports.yahoo.com/nfl/rss.xml' );

        $this->assertArrayHasKey( 'validity', $valid_feed );
        $this->assertArrayHasKey( 'validity', $invalid_feed );
        $this->assertArrayHasKey( 'success', $valid_feed );
        $this->assertArrayHasKey( 'success', $invalid_feed );
        $this->assertEquals( true, $valid_feed['success'] );
        $this->assertEquals( true, $invalid_feed['success'] );
    }
}
