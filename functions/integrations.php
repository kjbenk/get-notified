<?php

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the settings
 * @return array The settings array
 */
function gnt_get_integration_settings() {
	if ( false === ( $settings = get_option( 'gnt_integration_settings' ) ) ) {
		$settings = array();
	}
	return $settings;
}

/**
 * Save the settings
 * @param  array  $data The new settings to save
 * @return null
 */
function gnt_save_integration_settings( $data ) {
	unset( $data['submit'] );
	unset( $data['_wpnonce'] );
	unset( $data['_wp_http_referer'] );

	foreach ( $data as $key => $item ) {
		$data[ $key ] = sanitize_text_field( $item );
	}
	update_option( 'gnt_integration_settings', apply_filters( 'gnt_save_integrations', $data ) );
}

/**
 * Get the integrations
 *
 * @access public
 * @return void
 */
function gnt_get_integrations() {

	global $gnt_integrations;

	if ( ! is_array( $gnt_integrations ) ) {
		$gnt_integrations = array();
	}

	return apply_filters( 'gnt_get_integrations', $gnt_integrations );

}

/**
 * Register a new Integration
 *
 * @access public
 * @param mixed $integration
 * @param array $args (default: array())
 * @return void
 */
function gnt_register_integration( $integration, $args = array() ) {

	global $gnt_integrations;

	if ( ! is_array( $gnt_integrations ) ) {
		$gnt_integrations = array();
	}

	// Default integration

	$default = array(
		'name' => __( 'Integration', 'get-notified' ),
	);

	$args = array_merge( $default, $args );

	// Add the integration to the global integrations array

	$gnt_integrations[ $integration ] = apply_filters( 'gnt_register_integration_args', $args );

	/**
	* Fires after an integration is registered.
	*
	* @param string $integration Integration
	* @param array $args	  Arguments used to register the integration.
	*/
	do_action( 'gnt_registed_integration', $integration, $args );

	return $args;

}
