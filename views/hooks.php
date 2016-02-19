<div class="wrap">
    <h1><?php esc_attr_e( 'Post Status Change', 'gnt' ); ?></h1>
    <form method="post">
        <?php $gnt_hooks_table = new GNT_Hooks_Table();
        $gnt_hooks_table->prepare_items();
        $gnt_hooks_table->display();
        submit_button();
        wp_nonce_field( 'gnt_save_hooks' ); ?>
    </form>
</div>
