<div class="wrap">
	<h1><?php esc_attr_e( 'Post Status Hooks', 'get-notified' ); ?></h1>
	<form method="post">
		<?php $gnt_hooks_table = new GNT_Hooks_Table();
		$gnt_hooks_table->prepare_items();
		$gnt_hooks_table->display();

		do_action( 'gnt_hook_content' );

		submit_button();
		wp_nonce_field( 'gnt_save_hooks' ); ?>
	</form>
</div>
