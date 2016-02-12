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

if ( !class_exists('GNT_Notification_DB') ) :

class GNT_Notification_DB {

	/**
	 * table_name
	 *
	 * (default value: 'gnt_notifications')
	 *
	 * @var string
	 * @access public
	 */
	public $table_name = 'gnt_notifications';

	/**
	 * data_format
	 *
	 * (default value: 'Y-m-d H:i:s')
	 *
	 * @var string
	 * @access public
	 */
	public $data_format = 'Y-m-d H:i:s';

	/**
	 * Return the default Notification
	 *
	 * @access public
	 * @return void
	 */
	function default_data() {
		return array(
			'name'					=> __('My Notification', GET_NOTIFIED_TEXT_DOMAIN),
			'active'				=> 0,
			'start_date'			=> '',
			'end_date'				=> '',
			'conditions'			=> '',
			'args'					=> '',
		);
	}

	/**
	 * Create the table
	 *
	 * @access public
	 * @param mixed $table_name
	 * @return void
	 */
	function create_table() {

		global $wpdb;

		$result = $wpdb->query("CREATE TABLE IF NOT EXISTS `" . $this->get_table_name() . "` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(60) NOT NULL DEFAULT '',
				`active` int(1) NOT NULL DEFAULT 0,
				`start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				`end_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				`conditions` longtext,
				`args` longtext,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;");

		return $result;
	}

	/**
	 * Adds data into the table as a new row
	 *
	 * @access public
	 * @param array $data (default: array())
	 * @return void
	 */
	function add_data( $data = array() ) {

		$data = $this->validate_data($data);

		global $wpdb;

		$result = $wpdb->query( $wpdb->prepare("INSERT INTO `" . $this->get_table_name() . "` (
				`name`,
				`active`,
				`start_date`,
				`end_date`,
				`conditions`,
				`args`
			) VALUES (%s, %d, %s, %s, %s, %s)",
				$data['name'],
				$data['active'],
				date($this->data_format, strtotime($data['start_date'])),
				date($this->data_format, strtotime($data['end_date'])),
				json_encode($data['conditions']),
				json_encode($data['args'])
		) );

		// Return the recently created id for this entry

		return $wpdb->insert_id;

	}

	/**
	 * Update data
	 *
	 * @since 1.0.0
	 *
	 * @param	data to be updated
	 * @return	false if error, otherwise nothing
	 */
	public function update_data( $id = null, $data = array() ) {

		$data = $this->validate_data($data);

		if ( !isset($id) || empty($id) ) {
			return false;
		}

		global $wpdb;

		$result = $wpdb->query( $wpdb->prepare(
			"UPDATE `" . $this->get_table_name() . "` SET
				`name` = %s,
				`active` = %d,
				`start_date` = %s,
				`end_date` = %s,
				`conditions` = %s,
				`args` = %s
			WHERE id = %d",
				$data['name'],
				$data['active'],
				date($this->data_format, strtotime($data['start_date'])),
				date($this->data_format, strtotime($data['end_date'])),
				json_encode($data['conditions']),
				json_encode($data['args']),
				$id
		) );

		return $result;
	}

	/**
	 * Get all data
	 *
	 * @since 1.0.0
	 *
	 * @param	id
	 */
	function get_data($count = false) {

		global $wpdb;

		if ( !$count ) {
			$select = '*';
		} else {
			$select = 'COUNT(*)';
		}

		$data = $wpdb->get_results("SELECT " . $select . " FROM `" . $this->get_table_name() . "`", 'ARRAY_A');

		if ( !$count ) {
			return $this->parse_data( $data );
		} else {
			return $data[0]['COUNT(*)'];
		}
	}

	/**
	 * Get specfic data based on id
	 *
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	function get_data_from_id( $id ) {

		global $wpdb;

		$data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM `" . $this->get_table_name() . "` WHERE `id` = %d", $id ), 'ARRAY_A');

		if ( $data ) {

			$parsed_data = $this->parse_data( $data );

			return $parsed_data[0];
		} else {
			return null;
		}

	}

	/**
	 * Returns the name of a data object from the id
	 *
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	function get_name_from_id( $id ) {

		global $wpdb;

		$data = $wpdb->get_results( $wpdb->prepare("SELECT `name` FROM `" . $this->get_table_name() . "` WHERE `id` = %d", $id ), 'ARRAY_A');

		if ( $data ) {
			return $data[0]['name'];
		} else {
			return __('No Name Found', GET_NOTIFIED_TEXT_DOMAIN);
		}

	}

	/**
	 * Get active data
	 *
	 * @since 1.0.0
	 *
	 * @param	id
	 */
	function get_active_data($count = false) {

		global $wpdb;

		if ( !$count ) {
			$select = '*';
		} else {
			$select = 'COUNT(*)';
		}

		$data = $wpdb->get_results( "SELECT " . $select . " FROM `" . $this->get_table_name() . "` WHERE `active` = 1", 'ARRAY_A');

		if ( !$count ) {
			return $this->parse_data( $data );
		} else {
			return $data[0]['COUNT(*)'];
		}
	}

	/**
	 * Set data to active
	 *
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	function set_active( $id ) {

		global $wpdb;

		$result = $wpdb->query( $wpdb->prepare("UPDATE `" . $this->get_table_name() . "` SET `active` = 1 WHERE `id` = %d", $id ) );

		return $result;

	}

	/**
	 * Get active data
	 *
	 * @since 1.0.0
	 *
	 * @param	id
	 */
	function get_inactive_data($count = false) {

		global $wpdb;

		if ( !$count ) {
			$select = '*';
		} else {
			$select = 'COUNT(*)';
		}

		$data = $wpdb->get_results( "SELECT " . $select . " FROM `" . $this->get_table_name() . "` WHERE `active` = 0", 'ARRAY_A');

		if ( !$count ) {
			return $this->parse_data( $data );
		} else {
			return $data[0]['COUNT(*)'];
		}
	}

	/**
	 * Set data to inactive
	 *
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	function set_inactive( $id ) {

		global $wpdb;

		$result = $wpdb->query( $wpdb->prepare("UPDATE `" . $this->get_table_name() . "` SET `active` = 0 WHERE `id` = %d", $id ) );

		return $result;

	}

	/**
	 * Delete some data
	 *
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	function delete_data( $id ) {

		global $wpdb;

		$result = $wpdb->query( $wpdb->prepare("DELETE FROM `" . $this->get_table_name() . "` WHERE `id` = %d", $id ) );

		return $result;

	}

	/**
	 * Validate that the data is in the correct format
	 *
	 * @access public
	 * @param mixed $data
	 * @return void
	 */
	function validate_data( $data ){
		return array_merge($this->default_data(), $data);
	}

	/**
	 * Parse the returned data
	 *
	 * @access public
	 * @param mixed $data
	 * @return void
	 */
	function parse_data( $data ) {

		$parsed_data = array();

		foreach ($data as $row) {

			$entry = array();

			$entry["id"]           			= $row["id"];
			$entry["name"]         			= $row["name"];
			$entry["active"]        		= $row["active"];
			$entry["start_date"]        	= $row["start_date"];
			$entry["end_date"]        		= $row["end_date"];
			$entry['conditions']   			= json_decode($row['conditions']);
			$entry['args']         			= json_decode($row['args']);

			$parsed_data[] = $entry;
		}

		return $parsed_data;

	}

	/**
	 * Clean the data given to us
	 *
	 * @access public
	 * @param mixed $data
	 * @return void
	 */
	function clean_data($data) {
		return stripcslashes(sanitize_text_field($data));
	}

	/**
	 * Returns the proper table name for Multisies
	 *
	 * @access public
	 * @param mixed $table_name
	 * @return void
	 */
	function get_table_name() {

		global $wpdb;

		return $wpdb->prefix . $this->table_name;
	}

}

endif;