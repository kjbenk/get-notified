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

	<h1><?php _e('All Notifications', GET_NOTIFIED_TEXT_DOMAIN); ?>
		<a href="?page=get-notified-add-new&action=add" class="page-title-action"><?php _e('Add New', GET_NOTIFIED_TEXT_DOMAIN); ?></a>
	</h1>

	<ul class="subsubsub">

		<li class="all">
			<a href="?page=<?php echo $_REQUEST['page']; ?>&status=all" class="<?php echo( !isset($_GET['status']) || ( isset($_GET['status']) && $_GET['status'] == 'all' ) ? 'current' : ''); ?>">
				<?php _e('All', GET_NOTIFIED_TEXT_DOMAIN); ?> <span class="count">(<?php echo $notification_db->get_data(true); ?>)</span>
			</a> |
		</li>

		<li class="active">
			<a href="?page=<?php echo $_REQUEST['page']; ?>&status=active" class="<?php echo(isset($_GET['status']) && $_GET['status'] == 'active' ? 'current' : ''); ?>"><?php _e('Active', GET_NOTIFIED_TEXT_DOMAIN); ?> <span class="count">(<?php echo $notification_db->get_active_data(true); ?>)</span>
			</a> |
		</li>

		<li class="inactive">
			<a href="?page=<?php echo $_REQUEST['page']; ?>&status=inactive" class="<?php echo(isset($_GET['status']) && $_GET['status'] == 'inactive' ? 'current' : ''); ?>">
				<?php _e('Inactive', GET_NOTIFIED_TEXT_DOMAIN); ?> <span class="count">(<?php echo $notification_db->get_inactive_data(true); ?>)</span>
			</a>
		</li>

	</ul>

	<form method="post">
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
		<?php
		$all_notifications_table->prepare_items();
		$all_notifications_table->search_box('Search Notifications', 'gnt');
		$all_notifications_table->display(); ?>
	</form>

<?php include_once('footer.php'); ?>