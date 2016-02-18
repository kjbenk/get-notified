<?php
/**
 * Plugin Name: Get Notified
 * Plugin URI:
 * Description: Get Notified sends a slack notification when a post is published.
 * Author: Kyle Benk
 * Author URI: https://kylebenk.com
 * Version: 1.0.0
 * Text Domain: gnt
 * Domain Path: languages
 */

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Get_Notified' ) ) :

/**
 * Get_Notified class.
 */
final class Get_Notified {

	/**
	 * Holds the Get_Notified object and is the only way to obtain it
	 *
	 * @var mixed
	 * @access private
	 * @static
	 */
	private static $instance;

	/**
	 * Creates or retrieves the Get_Notified instance
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	public static function instance() {

		// No object is created yet so lets create one

		if ( !isset(self::$instance) && !(self::$instance instanceof Get_Notified) ) {

			self::$instance = new Get_Notified;
			self::$instance->setup_constants();
			self::$instance->includes();

			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		// Return the Get_Notified object

		return self::$instance;
	}

	/**
	 * Throw an error if this class is cloned
	 *
	 * @access public
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'You cannot __clone an instance of the Get_Notified class.', GET_NOTIFIED_TEXT_DOMAIN ), '1.6' );
	}

	/**
	 * Throw an error if this class is unserialized
	 *
	 * @access public
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'You cannot __wakeup an instance of the Get_Notified class.', GET_NOTIFIED_TEXT_DOMAIN ), '1.6' );
	}

	/**
	 * Sets up the constants we will use throughout the plugin
	 *
	 * @access private
	 * @return void
	 */
	private function setup_constants() {

		// Plugin prefix

		if ( ! defined( 'GET_NOTIFIED_PREFIX' ) ) {
			define( 'GET_NOTIFIED_PREFIX', 'gnt-' );
		}

		// Plugin text domain

		if ( ! defined( 'GET_NOTIFIED_TEXT_DOMAIN' ) ) {
			define( 'GET_NOTIFIED_TEXT_DOMAIN', 'get-notified' );
		}

		// Plugin version

		if ( ! defined( 'GET_NOTIFIED_VERSION' ) ) {
			define( 'GET_NOTIFIED_VERSION', '2.4.3' );
		}

		// Plugin Folder Path

		if ( ! defined( 'GET_NOTIFIED_PLUGIN_DIR' ) ) {
			define( 'GET_NOTIFIED_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Folder URL

		if ( ! defined( 'GET_NOTIFIED_PLUGIN_URL' ) ) {
			define( 'GET_NOTIFIED_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin Root File

		if ( ! defined( 'GET_NOTIFIED_PLUGIN_FILE' ) ) {
			define( 'GET_NOTIFIED_PLUGIN_FILE', __FILE__ );
		}

		// Make sure CAL_GREGORIAN is defined

		if ( ! defined( 'CAL_GREGORIAN' ) ) {
			define( 'CAL_GREGORIAN', 1 );
		}
	}

	/**
	 * Include required files
	 *
	 * @access private
	 * @since 1.4
	 * @return void
	 */
	private function includes() {

		include_once( GET_NOTIFIED_PLUGIN_DIR . 'functions/common.php');

	}

	/**
	 * Load the text domain for translation
	 *
	 * @access public
	 * @return void
	 */
	public function load_textdomain() {
		load_textdomain( GET_NOTIFIED_TEXT_DOMAIN , dirname( plugin_basename( GET_NOTIFIED_PLUGIN_FILE ) ) . '/languages/' );
	}

}

endif; // End if class_exists check

/**
 * This is the function you will use in order to obtain an instance
 * of the Get_Notified class.
 *
 * Example: <?php $Get_Notified = GET_NOTIFIED_instance(); ?>
 *
 * @access public
 * @return void
 */
function GET_NOTIFIED_instance() {
	return Get_Notified::instance();
}

// Get the class loaded up and running

GET_NOTIFIED_instance();
