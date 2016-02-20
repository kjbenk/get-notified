<?php

include_once( GET_NOTIFIED_PLUGIN_DIR . '/functions/hooks.php' );

/**
 * The Get Notified Hooks Test class
 */
class Get_Notified_Hooks_Test extends WP_UnitTestCase {

	/**
	 * Test to make sure the settings we get are an array
	 * @return null
	 */
	function test_get_hook_settings() {
		$hook_settings = gnt_get_hook_settings();
		$this->assertTrue( is_array( $hook_settings ) );
	}

	/**
	 * Test to make sure the hooks we get are an array
	 * @return null
	 */
	function test_get_hooks() {
		$hooks = gnt_get_hooks();
		$this->assertTrue( is_array( $hooks ) );
	}

	/**
	 * Test to make sure the hooks we get are an array
	 * @return null
	 */
	function test_register_hook() {

		gnt_register_hook( 'test', array(
			'name' => 'Test Hook',
		) );

		$hooks = gnt_get_hooks();
		$this->assertTrue( array_key_exists( 'test', $hooks ) );
	}
}
