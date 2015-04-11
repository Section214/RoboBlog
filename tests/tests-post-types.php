<?php
class Tests_Post_Types extends WP_UnitTestCase {
    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function test_rbfeed_post_type() {
        global $wp_post_types;

        $this->assertArrayHasKey( 'rbfeed', $wp_post_types );
    }

    public function test_rbfeed_post_type_labels() {
        global $wp_post_types;

        $this->assertEquals( 'Feeds', $wp_post_types['rbfeed']->labels->name );
        $this->assertEquals( 'Feed', $wp_post_types['rbfeed']->labels->singular_name );
        $this->assertEquals( 'Add New', $wp_post_types['rbfeed']->labels->add_new );
        $this->assertEquals( 'Add New Feed', $wp_post_types['rbfeed']->labels->add_new_item );
        $this->assertEquals( 'Edit Feed', $wp_post_types['rbfeed']->labels->edit_item );
        $this->assertEquals( 'View Feed', $wp_post_types['rbfeed']->labels->view_item );
        $this->assertEquals( 'Search Feeds', $wp_post_types['rbfeed']->labels->search_items );
        $this->assertEquals( 'No feeds found', $wp_post_types['rbfeed']->labels->not_found );
        $this->assertEquals( 'No feeds found in Trash', $wp_post_types['rbfeed']->labels->not_found_in_trash );
        $this->assertEquals( 'All Feeds', $wp_post_types['rbfeed']->labels->all_items );
        $this->assertEquals( 'RoboBlog', $wp_post_types['rbfeed']->labels->menu_name );
        $this->assertEquals( 1, $wp_post_types['rbfeed']->publicly_queryable );
        $this->assertEquals( 'Feeds', $wp_post_types['rbfeed']->label );
    }
}
