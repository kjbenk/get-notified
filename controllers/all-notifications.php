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

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if ( !class_exists('GNT_All_Notifications_Table') ) :

class GNT_All_Notifications_Table extends WP_List_Table {

	/**
	 * Initialize the table object
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		parent::__construct( array(
			'singular' => __( 'Notification', GET_NOTIFIED_TEXT_DOMAIN ), 	//singular name of the listed records
			'plural'   => __( 'Notifications', GET_NOTIFIED_TEXT_DOMAIN ), 	//plural name of the listed records
			'ajax'     => false 											//does this table support ajax?
		) );

	}

	/**
	 * Display this message when there are no notifications found
	 *
	 * @access public
	 * @return void
	 */
	public function no_items() {
		_e( 'No notifications found.', GET_NOTIFIED_TEXT_DOMAIN );
	}

	/**
	 * Prepare all the items to be displayed
	 *
	 * @access public
	 * @return void
	 */
	public function prepare_items() {

		$columns = $this->get_columns();
		$sortable = $this->get_sortable_columns();
		$hidden = array();

		$this->_column_headers = array($columns, $hidden, $sortable);

		$per_page     = 20;
		$current_page = $this->get_pagenum();
		$total_items  = $this->get_total();

		$this->set_pagination_args( array(
			'total_items' => $total_items, 	// We have to calculate the total number of items
			'per_page'    => $per_page 		// We have to determine how many items to show on a page
		) );

		$notification_db = new GNT_Notification_DB();

		if ( !isset($_GET['status']) || ( isset($_GET['status']) && $_GET['status'] == 'all' ) ) {
			$this->items = $notification_db->get_data();
		} else if ( isset($_GET['status']) && $_GET['status'] == 'active' ) {
			$this->items = $notification_db->get_active_data();
		} else {
			$this->items = $notification_db->get_inactive_data();
		}
	}

	/**
	 * Get all the columns that we want to display
	 *
	 * @access public
	 * @return void
	 */
	function get_columns() {

		$columns = array(
			'cb'      	=> '<input type="checkbox" />',
			'id'    	=> __( 'ID', GET_NOTIFIED_TEXT_DOMAIN ),
			'name'    	=> __( 'Name', GET_NOTIFIED_TEXT_DOMAIN )
		);

		return $columns;
	}

	/**
	 * Get the sortable columns for the table
	 *
	 * @access public
	 * @return void
	 */
	function get_sortable_columns() {

		$sortable_columns = array(
			'id' 			=> array('id',false),
			'name'  		=> array('name',false),
		);

		return $sortable_columns;
	}

	/**
	 * Get the bulk actions
	 *
	 * @access public
	 * @return void
	 */
	function get_bulk_actions() {

		$actions = array(
			'delete'    => 'Delete'
		);
		return $actions;
	}

	/**
	 * Default Column Value
	 *
	 * @access public
	 * @param mixed $item
	 * @param mixed $column_name
	 * @return void
	 */
	public function column_default( $item, $column_name ) {

		switch( $column_name ) {

			case 'id':
				return $item[ $column_name ];
			break;

			case 'name':
				return $item[ $column_name ];
			break;

			default:
				return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}

	}

	/**
	 * Checkbox column value
	 *
	 * @access public
	 * @param mixed $item
	 * @return void
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
		);
	}

	/**
	 * Name column value
	 *
	 * @access public
	 * @param mixed $item
	 * @return void
	 */
	function column_name($item) {

		$actions = array(
			'edit'      => sprintf('<a href="?page=%s&action=%s&id=%s">%s</a>', 'get-notified-add-new', 'edit', $item['id'], __('Edit', GET_NOTIFIED_TEXT_DOMAIN)),
			'delete'    => sprintf('<a href="?page=%s&action=%s&id=%s">%s</a>', $_REQUEST['page'], 'delete', $item['id'], __('Delete', GET_NOTIFIED_TEXT_DOMAIN)),
		);

		return sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions) );
	}

	/**
	 * Returns the total count of the items
	 *
	 * @access public
	 * @return void
	 */
	function get_total() {

		$notification_db = new GNT_Notification_DB();

		return 1;
	}
}

endif;