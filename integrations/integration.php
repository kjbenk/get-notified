<?php

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'GNT_Integration' ) ) :

	class GNT_Integration {

		/**
		 * Function that is run upon creating instance
		 */
		public function __construct() {}

		/**
		 * Add all the hooks
		 */
		public function add_hooks() {
			$hooks = gnt_get_hooks();
			$hook_settings = gnt_get_hook_settings();

			foreach ( $hooks as $hook ) {
				add_action( 'gnt_hook_' . $hook['slug'], array( $this, 'send' ) );
			}
		}

		/**
		 * Send post data
		 *
		 * @param  object $post WP_Post object
		 * @return null
		 */
		function send( $data ) {
			if ( ! isset( $data['hook'] ) || empty( $data['hook'] ) ) {
				return;
			}

			$settings = gnt_get_integration_settings();

			// Default Message

			if ( ! isset( $data['text'] ) || empty( $data['text'] ) ) {
				$data['text'] = __( $data['hook'], 'get-notified' );

				/**
				 * Action to send data via the integration
				 *
				 * @param array $data	   All attributes
				 * @param array $settigns   The current integration settings
				 * @param bool			  Is this a default message?
				 */
				do_action( 'gnt_integration_send', $data, $settings, true );
				return;
			}

			/**
			 * Action to send data via the integration
			 *
			 * @param array $data	   All attributes
			 * @param array $settigns   The current integration settings
			 * @param bool			  Is this a default message?
			 */
			do_action( 'gnt_integration_send', $data, $settings, false );
		}

		/**
		 * Show a setting if it is enabled
		 * @param  string $setting The setting
		 * @return string		  Style attribute
		 */
		public function hide_setting() {
			return 'display:none;';
		}
	}

	$gnt_integration_class = new GNT_Integration();
	add_action( 'init', array( $gnt_integration_class, 'add_hooks' ), 11 );

endif;
