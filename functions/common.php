<?php

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Send a new request to Slack when a post is published
 *
 * @param  string   $new_status The new status of the post
 * @param  string   $old_status The old statud of the post
 * @param  WP_Post  $post       The WP_Post object
 * @return null
 */
function gnt_post_published( $new_status, $old_status, $post ) {
    if ( $old_status != 'publish'  &&  $new_status == 'publish' ) {
        $settings = gnt_get_settings();
        if ( isset( $settings['notify-publish-post'] ) && $settings['notify-publish-post'] ) {
            gnt_message_slack( $settings['slack-webhook'], array(
                'text'  => '<' . get_post_permalink( $post->ID ) . '|' . $post->post_title . '> was just published!'
            ) );
        }
    }
}
add_action( 'transition_post_status', 'gnt_post_published', 10, 3 );

/**
 * Message slack with a payload
 *
 * @param string $url The incoming webhook
 * @param array $payload All of the data slack needs to post a message
 * @return null
 */
function gnt_message_slack($url, $payload) {
    if ( isset( $url ) && ! empty( $url ) ) {
        wp_remote_post( $url , array(
            'method'   => 'POST',
            'body'     => json_encode( $payload ) )
        );
    }
}

/**
 * Add submenu page
 * @return null
 */
function gnt_admin_page() {
    add_options_page(
        __( 'Get Notified', 'gnt' ),
        __( 'Get Notified', 'gnt' ),
        'manage_options',
        'get-notified',
        'gnt_admin_page_content'
    );
}
add_action( 'admin_menu', 'gnt_admin_page' );

/**
 * Add the contents of the settings page
 * @return null
 */
function gnt_admin_page_content() {
    $settings = gnt_get_settings();

    // Save the settings

    if ( isset( $_POST['submit'] ) && check_admin_referer( 'gnt_save_settings' ) ) {
        gnt_save_settings($_POST);
        gnt_force_redirect( get_admin_url() . 'options-general.php?page=get-notified' );
    }

    ?>
    <div class="wrap">
        <h1><?php esc_attr_e( 'Get Notified', 'gnt' ); ?></h1>

        <form method="post">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th><?php esc_attr_e( 'Notify on Pubslihed Post', 'gnt' ); ?></th>
                        <td>
                            <input type="checkbox" name="notify-publish-post" value="yes" <?php echo ( isset( $settings['notify-publish-post'] ) && $settings['notify-publish-post'] ? 'checked="checked"' : ''); ?>/>
                        </td>
                    </tr>

                    <tr>
                        <th><?php esc_attr_e( 'Slack Web Hook', 'gnt' ); ?></th>
                        <td>
                            <input type="text" class="widefat" name="slack-webhook" value="<?php echo ( isset( $settings['slack-webhook'] ) ? esc_attr( $settings['slack-webhook'] ) : '' ); ?>"/>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php submit_button(); ?>
            <?php wp_nonce_field( 'gnt_save_settings' ); ?>
        </form>
    </div>
    <?php
}

/**
 * Get the settings
 * @return array The settings array
 */
function gnt_get_settings() {
    if ( false === ( $settings = get_option( 'gnt_settings' ) ) ) {
        $settings = array();
    }
    return $settings;
}

/**
 * Save the settings
 * @param  array  $data The new settings to save
 * @return null
 */
function gnt_save_settings($data) {
    unset($data['submit']);
    unset($data['_wpnonce']);
    unset($data['_wp_http_referer']);

    update_option( 'gnt_settings', $data );
}

/**
 * Force a URL redirect via Javascript
 * @param  string $url The URL you want to redirect to
 * @return null
 */
function gnt_force_redirect($url) {
    if ( isset( $url ) && ! empty( $url ) ) {
        ?><script type="text/javascript">
            window.location = '<?php echo $url; ?>';
        </script><?php
    }
}