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

function gnt_install() {

	// Add a temporary option to note that EDD pages have been created

	set_transient( '_gnt_installed', true, 30 );

}

register_activation_hook( GET_NOTIFIED_PLUGIN_FILE, 'gnt_install' );

function gnt_after_install() {

	if ( ! is_admin() ) {
		return;
	}

	$installed = get_transient( '_gnt_installed' );

	// Exit if not in admin or the transient doesn't exist

	if ( false === $installed ) {
		return;
	}

	// Create the customers database (this ensures it creates it on multisite instances where it is network activated)

	$notification_db = new GNT_Notification_DB();
	$notification_db->create_table();
}

add_action( 'admin_init', 'gnt_after_install' );