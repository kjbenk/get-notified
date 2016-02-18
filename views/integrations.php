<div class="wrap">
    <h1><?php esc_attr_e( 'Integrations', 'gnt' ); ?></h1>
    <form method="post">
        <?php
        foreach ( $integrations as $integration ) {
            do_action( 'gnt_hook_content_' . $integration, $integrationsettings[ $integration ] );
        }
        submit_button();
        wp_nonce_field( 'gnt_save_integrations' ); ?>
    </form>
</div>
