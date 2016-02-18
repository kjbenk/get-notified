<?php

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) exit;

class GNT_Slack {

    /**
     * Function that is run upon creating instance
     */
    public function __construct() {
        add_action( 'gnt_setup_integrations',   array( $this, 'add_integration' ) );

        $hooks = gnt_get_hooks();

        foreach ( $hooks as $hook ) {
            add_action( 'gnt_hook_' . $hook, array( $this, 'send') );
        }
    }

    /**
     * Add a slack integration
     */
    function add_integration() {
        gnt_register_integration( 'slack', array(
            'name'	=> __( 'Slack', 'gnt' ),
            'hooks' => true,
        ) );
    }

    /**
     * Send post data
     * @param  object $post WP_Post object
     * @return null
     */
    function send($data) {
        if ( ! isset( $data['hook'] ) || empty( $data['hook'] ) ) {
            return;
        }
        $settings = gnt_get_settings();

        switch ( $data['hook'] ) {
            case 'post_published':
                if ( ! isset( $settings['slack-webhook'] ) && ! empty( $settings['slack-webhook'] ) ) {
                    $this->send_message( $settings['slack-webhook'], array(
                        'text'  => '<' . get_post_permalink( $post->ID ) . '|' . $post->post_title . '> was just published!'
                    ) );
                }
            break;

            default;
                if ( ! isset( $data['text'] ) || empty( $data['text'] ) ) {
                    $data['text'] = __( $data['hook'], 'gnt' );
                }

                $this->send_message( $settings['slack-webhook'], array(
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
}

$gnt_slack_class = new GNT_Slack();
