<div class="wrap">
    <h1><?php esc_attr_e( 'Integrations', 'gnt' ); ?></h1>
    <form method="post">
        <?php
        foreach ( $integrations as $key => $integration ) {
            do_action( 'gnt_integration_content_' . $key, $integration_settings );
        }
        submit_button();
        wp_nonce_field( 'gnt_save_integrations' ); ?>
    </form>
</div>
