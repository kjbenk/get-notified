<div class="wrap">
    <h1><?php esc_attr_e( 'Get Notified', 'gnt' ); ?></h1>

    <form method="post">

        <h2><?php esc_attr_e( 'Notify When', 'gnt' ); ?></h2>
        <hr/>

        <table class="form-table">
            <tbody>
                <tr>
                    <th><?php esc_attr_e( 'Post is Published', 'gnt' ); ?></th>
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
