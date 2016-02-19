<?php

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get the settings
 * @return array The settings array
 */
function gnt_get_hook_settings() {
    if ( false === ( $settings = get_option( 'gnt_hook_settings' ) ) ) {
        $settings = array();
    }
    return $settings;
}

/**
 * Save the settings
 * @param  array  $data The new settings to save
 * @return null
 */
function gnt_save_hook_settings($data) {
    unset( $data['submit'] );
    unset( $data['_wpnonce'] );
    unset( $data['_wp_http_referer'] );

    foreach ( $data as $key => $item ) {
        $data[$key] = sanitize_text_field( $item );
    }

    update_option( 'gnt_hook_settings', apply_filters( 'gnt_save_hooks', $data ) );
}

/**
 * Get the hooks
 *
 * @access public
 * @return void
 */
function gnt_get_hooks() {
	global $gnt_hooks;
	if ( ! is_array( $gnt_hooks ) ) {
		$gnt_hooks = array();
	}

	return apply_filters( 'gnt_get_hooks', $gnt_hooks );
}

/**
 * Register a new hook
 *
 * @access public
 * @param mixed $hook
 * @param array $args (default: array())
 * @return void
 */
function gnt_register_hook($hook, $args = array()) {
	global $gnt_hooks;
	if ( ! is_array( $gnt_hooks ) ) {
		$gnt_hooks = array();
	}

	// Default hook
	$default = array(
		'name' => __( 'Hook', 'gnt' ),
	);
	$args = array_merge( $default, $args );

	// Add the hook to the global hooks array
	$gnt_hooks[ $hook ] = apply_filters( 'gnt_register_hook_args', $args );

	/**
	* Fires after an hook is registered.
	*
	* @param string $hook hook
	* @param array $args      Arguments used to register the hook.
	*/
	do_action( 'gnt_registed_hook', $hook, $args );

	return $args;

}
