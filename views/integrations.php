<div class="wrap">
	<form method="post">
		<?php
		foreach ( $integrations as $key => $integration ) {
			do_action( 'gnt_integration_content_' . $key, $integration_settings );
		}
		submit_button();
		wp_nonce_field( 'gnt_save_integrations' ); ?>
	</form>
</div>
