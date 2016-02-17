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

        <h2><?php esc_attr_e( 'Send To Slack', 'gnt' ); ?></h2>
        <hr/>

        <table class="form-table">
            <tbody>

                <tr>
                    <th><?php esc_attr_e( 'Enable', 'gnt' ); ?></th>
                    <td>
                        <input type="checkbox" name="notify-enable-slack" value="yes" <?php echo ( isset( $settings['notify-enable-slack'] ) && $settings['notify-enable-slack'] ? 'checked="checked"' : ''); ?>/>
                    </td>
                </tr>

                <?php if ( isset( $settings['notify-enable-slack'] ) && $settings['notify-enable-slack'] ) { ?>
                    <tr>
                        <th><?php esc_attr_e( 'Webhook URL', 'gnt' ); ?></th>
                        <td>
                            <input type="text" class="regular-text" name="slack-webhook" value="<?php echo ( isset( $settings['slack-webhook'] ) ? esc_attr( $settings['slack-webhook'] ) : '' ); ?>"/>
                            <p class="description"><?php esc_attr_e( 'Create a', 'gnt' ); ?> <a href="https://my.slack.com/services/new/incoming-webhook/" target="_blank"><?php esc_attr_e( 'Slack Webhook', 'gnt' ); ?></a> <?php esc_attr_e( 'and then save the URL here.  This Webhook will be used to send data to Slack.', 'gnt' ); ?></p>
                        </td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
        <?php submit_button(); ?>
        <?php wp_nonce_field( 'gnt_save_settings' ); ?>
    </form>
</div>
