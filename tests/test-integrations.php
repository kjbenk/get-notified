<?php

include_once( GET_NOTIFIED_PLUGIN_DIR . '/functions/integrations.php' );

/**
 * The Get Notified Integrations Test class
 */
class Get_Notified_Integrations_Test extends WP_UnitTestCase {

	/**
	 * Test to make sure the settings we get are an array
	 * @return null
	 */
	function test_get_integration_settings() {
		$integration_settings = gnt_get_integration_settings();
		$this->assertTrue( is_array( $integration_settings ) );
	}

	/**
	 * Test to make sure the integrations we get are an array
	 * @return null
	 */
	function test_get_integrations() {
		$integrations = gnt_get_integrations();
		$this->assertTrue( is_array( $integrations ) );
	}

	/**
	 * Test to make sure the integrations we get are an array
	 * @return null
	 */
	function test_register_integration() {

		gnt_register_integration( 'test', array(
			'name' => 'Test Integration',
		) );

		$integrations = gnt_get_integrations();
		$this->assertTrue( array_key_exists( 'test', $integrations ) );
	}
}
