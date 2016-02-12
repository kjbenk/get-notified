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
?>

<?php include_once('header.php'); ?>

	<h1><?php _e('Add New', GET_NOTIFIED_TEXT_DOMAIN); ?></h1>

	<?php
	$notification_db = new GNT_Notification_DB();

	if ( isset($_GET['id']) ) {
		$notification = $notification_db->get_data_from_id($_GET['id']);
	} else {
		$notification = $notification_db->default_data();
	}

	?>

	<form method="post">

		<table class="form-table">
			<tbody>

			<!-- Name -->

			<tr>
				<th scope="row"><label for="<?php echo GET_NOTIFIED_PREFIX; ?>name"><?php _e('Name', GET_NOTIFIED_TEXT_DOMAIN); ?></label></th>
				<td>
					<input type="text" class="regular-text" id="<?php echo GET_NOTIFIED_PREFIX; ?>name" name="<?php echo GET_NOTIFIED_PREFIX; ?>name" placeholder="<?php _e('My Notification', GET_NOTIFIED_TEXT_DOMAIN); ?>" value="<?php echo $notification['name']; ?>"/>
				</td>
			</tr>

			</tbody>
		</table>

		<?php wp_nonce_field( 'save-notification' ); ?>

		<?php submit_button(); ?>

	</form>

<?php include_once('footer.php'); ?>