<?php
/**
 * The Get Notified test class
 */
class Get_Notified_Tests extends WP_UnitTestCase {

	/**
	 * Test to make sure the settings we get are an array
	 * @return null
	 */
	function test_get_settings() {
		include_once( GET_NOTIFIED_PLUGIN_DIR . '/functions/common.php' );
		$settings = gnt_get_settings();
		$this->assertTrue( is_array( $settings) );
	}
}
