<div class="wrap">
    <h1><?php esc_attr_e( 'Hooks', 'gnt' ); ?></h1>
    <form method="post">
        <?php
        foreach ( $hooks as $hook ) {
            do_action( 'gnt_hook_content_' . $hook, $hook_settings[ $hook ] );
        }
        submit_button();
        wp_nonce_field( 'gnt_save_hooks' ); ?>
    </form>
</div>
