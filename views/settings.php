<div class="wrap">
	<h1><?php esc_html_e( 'Get Notified', 'get-notified' ); ?></h1>

	<form method="post">

		<h2><?php esc_html_e( 'Notify When', 'get-notified' ); ?></h2>
		<hr/>

		<table class="form-table">
			<tbody>
				<tr>
					<th><?php esc_html_e( 'Post is Published', 'get-notified' ); ?></th>
					<td>
						<input type="checkbox" name="notify-publish-post" value="yes" <?php echo ( isset( $settings['notify-publish-post'] ) && $settings['notify-publish-post'] ? 'checked="checked"' : ''); ?>/>
					</td>
				</tr>
			</tbody>
		</table>
		<?php submit_button(); ?>
		<?php wp_nonce_field( 'gnt_save_settings' ); ?>
	</form>
</div>
