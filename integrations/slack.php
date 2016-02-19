<?php

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) exit;

class GNT_Slack {

    /**
     * The slug for this integration
     *
     * @var string
     */
    private $slug = 'slack';

    /**
     * Function that is run upon creating instance
     */
    public function __construct() {
        add_action( 'gnt_setup_integrations',                   array( $this, 'add_integration' ) );
        add_action( 'gnt_integration_content_' . $this->slug,   array( $this, 'settings' ) );

        $hooks = gnt_get_hooks();

        foreach ( $hooks as $hook ) {
            add_action( 'gnt_hook_' . $hook, array( $this, 'send') );
        }
    }

    /**
     * Add a slack integration
     */
    function add_integration() {
        gnt_register_integration( $this->slug, array(
            'name'	=> __( 'Slack', 'gnt' ),
            'hooks' => true,
        ) );
    }

    /**
     * Send post data
     *
     * @param  object $post WP_Post object
     * @return null
     */
    function send($data) {
        if ( ! isset( $data['hook'] ) || empty( $data['hook'] ) ) {
            return;
        }
        $settings = gnt_get_integration_settings();

        switch ( $data['hook'] ) {
            case 'post_published':
                if ( ! isset( $settings[ $this->slug . '-webhook'] ) && ! empty( $settings[ $this->slug . '-webhook' ] ) ) {
                    $this->send_message( $settings[ $this->slug . '-webhook' ], array(
                        'text'  => '<' . get_post_permalink( $post->ID ) . '|' . $post->post_title . '> was just published!'
                    ) );
                }
            break;

            default;
                if ( ! isset( $data['text'] ) || empty( $data['text'] ) ) {
                    $data['text'] = __( $data['hook'], 'gnt' );
                }
                $this->send_message( $settings[ $this->slug . '-webhook' ], array(
                    'text'  => $data['text'],
                ) );
            break;
        }
    }

    /**
     * Message slack with a payload
     *
     * @param string $url The incoming webhook
     * @param array $payload All of the data slack needs to post a message
     * @return null
     */
    function send_message($url, $payload) {
        if ( isset( $url ) && ! empty( $url ) ) {
            wp_remote_post( $url , array(
                'method'   => 'POST',
                'body'     => json_encode( $payload ) )
            );
        }
    }

    /**
     * Output the settings for the Slack integration
     *
     * @param  array $settings The settings array
     * @return null
     */
    function settings($settings) {
        ?>
        <h2><?php esc_attr_e( 'Slack', 'gnt' ); ?></h2>
        <table class="form-table">
            <tbody>

                <tr>
                    <th><?php esc_attr_e( 'Enable', 'gnt' ); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo $this->slug; ?>-enable" value="yes" <?php echo ( isset( $settings[ $this->slug . '-enable'] ) && $settings[ $this->slug . '-enable' ] ? 'checked="checked"' : ''); ?>/>
                    </td>
                </tr>

                <?php if ( isset( $settings[ $this->slug . '-enable' ] ) && $settings[ $this->slug . '-enable' ] ) { ?>
                    <tr>
                        <th><?php esc_attr_e( 'Webhook URL', 'gnt' ); ?></th>
                        <td>
                            <input type="text" class="regular-text" name="<?php echo $this->slug; ?>-webhook" value="<?php echo ( isset( $settings[ $this->slug . '-webhook' ] ) ? esc_attr( $settings[ $this->slug . '-webhook' ] ) : '' ); ?>"/>
                            <p class="description"><?php esc_attr_e( 'Create a', 'gnt' ); ?> <a href="https://my.slack.com/services/new/incoming-webhook/" target="_blank"><?php esc_attr_e( 'Slack Webhook', 'gnt' ); ?></a> <?php esc_attr_e( 'and then save the URL here.  This Webhook will be used to send data to Slack.', 'gnt' ); ?></p>
                        </td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
        <?php
    }
}

$gnt_slack_class = new GNT_Slack();
