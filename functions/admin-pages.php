<?php
/* ===================================================================
 *
 * 99 Robots https://99robots.com
 *
 * Copyright 2015
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * ================================================================= */

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add the Admin Menu Page
 *
 * @access public
 * @return void
 */
function gnt_add_menu_page() {

	/**
	 * Root page
	 *
	 * @var mixed
	 * @access public
	 */
	$root_page = add_menu_page(
		__('Get Notified', GET_NOTIFIED_TEXT_DOMAIN),				// Page Title
		__('Get Notified', GET_NOTIFIED_TEXT_DOMAIN), 				// Menu Name
    	'manage_options', 											// Capabilities
    	'get-notified-dashboard', 									// slug
    	'gnt_all_notifications_content',							// Callback function
    	''															// Icon
    );

    /**
     * All Notifications
     *
     * @var mixed
     * @access public
     */
    $all_notifications_page_load = add_submenu_page(
    	'get-notified-dashboard', 									// parent slug
    	__('All Notifications', GET_NOTIFIED_TEXT_DOMAIN), 			// Page title
    	__('All Notifications', GET_NOTIFIED_TEXT_DOMAIN), 			// Menu name
    	'manage_options', 											// Capabilities
    	'get-notified-dashboard', 									// slug
    	'gnt_all_notifications_content'								// Callback function
    );
    add_action("admin_print_scripts-$all_notifications_page_load", 'gnt_all_notifications_scripts');

    /**
     * Add New
     *
     * @var mixed
     * @access public
     */
    $all_new_page_load = add_submenu_page(
    	'get-notified-dashboard', 									// parent slug
    	__('All New', GET_NOTIFIED_TEXT_DOMAIN), 					// Page title
    	__('All New', GET_NOTIFIED_TEXT_DOMAIN), 					// Menu name
    	'manage_options', 											// Capabilities
    	'get-notified-add-new', 									// slug
    	'gnt_add_new_content'										// Callback function
    );
    add_action("admin_print_scripts-$all_new_page_load", 'gnt_add_new_scripts');

    /**
     * Settings
     *
     * @var mixed
     * @access public
     */
    $settings_page_load = add_submenu_page(
    	'get-notified-dashboard', 									// parent slug
    	__('Settings', GET_NOTIFIED_TEXT_DOMAIN), 					// Page title
    	__('Settings', GET_NOTIFIED_TEXT_DOMAIN), 					// Menu name
    	'manage_options', 											// Capabilities
    	'get-notified-settings', 									// slug
    	'gnt_settings_content'										// Callback function
    );
    add_action("admin_print_scripts-$settings_page_load", 'gnt_settings_scripts');

    /**
     * Extensions
     *
     * @var mixed
     * @access public
     */
    $extensions_page_load = add_submenu_page(
    	'get-notified-dashboard', 									// parent slug
    	__('Extensions', GET_NOTIFIED_TEXT_DOMAIN), 				// Page title
    	__('Extensions', GET_NOTIFIED_TEXT_DOMAIN), 				// Menu name
    	'manage_options', 											// Capabilities
    	'get-notified-extensions', 									// slug
    	'gnt_extensions_content'									// Callback function
    );
    add_action("admin_print_scripts-$extensions_page_load", 'gnt_extensions_scripts');

    /**
     * Import / Export
     *
     * @var mixed
     * @access public
     */
    $import_export_page_load = add_submenu_page(
    	'get-notified-dashboard', 									// parent slug
    	__('Import / Export', GET_NOTIFIED_TEXT_DOMAIN), 			// Page title
    	__('Import / Export', GET_NOTIFIED_TEXT_DOMAIN), 			// Menu name
    	'manage_options', 											// Capabilities
    	'get-notified-import-export', 								// slug
    	'gnt_import_export_content'									// Callback function
    );
    add_action("admin_print_scripts-$import_export_page_load", 'gnt_import_export_scripts');

}

add_action( 'admin_menu', 'gnt_add_menu_page' );

/**
 * Display on the Contents of the All Notifications Page
 *
 * @access public
 * @return void
 */
function gnt_all_notifications_content() {

	require_once( GET_NOTIFIED_PLUGIN_DIR . 'controllers/all-notifications.php' );

	$all_notifications_table = new GNT_All_Notifications_Table();
	$notification_db = new GNT_Notification_DB();

	// Delete

	if ( isset($_GET['action']) && $_GET['action'] == 'delete' && check_admin_referer( 'delete-notification' ) ) {

		$notification_db->delete_data($_GET['id']);

	}

	// Activate

	if ( isset($_GET['action']) && $_GET['action'] == 'activate' && check_admin_referer( 'activate-notification' ) ) {

		$notification_db->set_active($_GET['id']);

	}

	// Inactivate

	if ( isset($_GET['action']) && $_GET['action'] == 'inactivate' && check_admin_referer( 'inactivate-notification' ) ) {

		$notification_db->set_inactive($_GET['id']);

	}

	include(GET_NOTIFIED_PLUGIN_DIR . 'views/all-notifications-page.php');
}

/**
 * Include all the scripts and stylesheet needed for the All Notifications page
 *
 * @access public
 * @return void
 */
function gnt_all_notifications_scripts() {
	;
}

/**
 * Display on the Contents of the Add New Page
 *
 * @access public
 * @return void
 */
function gnt_add_new_content() {

	$notification_db = new GNT_Notification_DB();

	// Save a Notification

	if ( isset($_POST['submit']) && check_admin_referer( 'save-notification' ) ) {

		// Edit

		if ( isset($_GET['action']) && $_GET['action'] == 'edit' ) {

			$data = $notification_db->get_data_from_id($_GET['id']);

			$data['name'] = isset($_POST[GET_NOTIFIED_PREFIX . 'name']) ? $notification_db->clean_data($_POST[GET_NOTIFIED_PREFIX . 'name']) : 'My Notification';

			$notification_db->update_data($_GET['id'], $data);

		}

		// Add

		else {

			$data['name'] = isset($_POST[GET_NOTIFIED_PREFIX . 'name']) ? $notification_db->clean_data($_POST[GET_NOTIFIED_PREFIX . 'name']) : 'My Notification';

			$notification_db->add_data($data);
		}
	}

	include(GET_NOTIFIED_PLUGIN_DIR . 'views/add-new-page.php');
}

/**
 * Include all the scripts and stylesheet needed for the Add New page
 *
 * @access public
 * @return void
 */
function gnt_add_new_scripts() {
	;
}

/**
 * Display on the Contents of the Settings Page
 *
 * @access public
 * @return void
 */
function gnt_settings_content() {

	include(GET_NOTIFIED_PLUGIN_DIR . 'views/settings-page.php');
}

/**
 * Include all the scripts and stylesheet needed for the Settings page
 *
 * @access public
 * @return void
 */
function gnt_settings_scripts() {
	;
}

/**
 * Display on the Contents of the Extensions Page
 *
 * @access public
 * @return void
 */
function gnt_extensions_content() {

	include(GET_NOTIFIED_PLUGIN_DIR . 'views/extensions-page.php');
}

/**
 * Include all the scripts and stylesheet needed for the Extensions page
 *
 * @access public
 * @return void
 */
function gnt_extensions_scripts() {
	;
}

/**
 * Display on the Contents of the Import / Export Page
 *
 * @access public
 * @return void
 */
function gnt_import_export_content() {

	include(GET_NOTIFIED_PLUGIN_DIR . 'views/import-export-page.php');
}

/**
 * Include all the scripts and stylesheet needed for the Import / Export page
 *
 * @access public
 * @return void
 */
function gnt_import_export_scripts() {
	;
}