<?php

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'GNT_Slack' ) ) :

	class GNT_Slack extends GNT_Integration {

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
			parent::__construct();

			add_action( 'gnt_setup_integrations',   array( $this, 'add_integration' ) );
			add_action( 'gnt_integration_send',	 array( $this, 'check_send_message' ), 10, 3 );

			add_action( 'gnt_integration_content_' . $this->slug,   array( $this, 'settings' ) );
		}

		/**
		 * Add a slack integration
		 */
		function add_integration() {
			gnt_register_integration( $this->slug, array(
				'name'	=> __( 'Slack', 'get-notified' ),
				'hooks' => true,
			) );
		}

		/**
		 * Should we send the message over to slack?
		 *
		 * @param  array $data	  The data to be sent
		 * @param  array $settings  The Integration settings
		 * @param  bool			 Is this a default message?
		 * @return null
		 */
		function check_send_message( $data, $settings, $default ) {

			if ( ! isset( $settings[ $this->slug . '-enable' ] ) ) {
				return;
			}

			// Check if webhook is set

			$webhook = $settings[ $this->slug . '-webhook' ];
			if ( empty( $webhook ) ) {
				return;
			}

			// Check if this is a default message

			if ( $default ) {
				$this->send_message( $webhook, array(
					'text'  => $data['text'],
				) );
				return;
			}

			// Post Status

			if ( isset( $data['post_status'] ) && isset( $data['post'] ) ) {
				$this->send_message( $webhook, array(
					'text'  => '<' . get_post_permalink( $data['post']->ID ) . '|' . $data['post']->post_title . '> changed status to ' . $data['post_status'] . '.',
				) );
				return;
			}

			// Comment Created

			if ( isset( $data['comment_object'] ) && isset( $data['post'] ) ) {
				$this->send_message( $webhook, array(
					'text'  => '<' . get_post_permalink( $data['post']->ID ) . '|' . $data['post']->post_title . '> has a new comment by ' . $data['comment_object']->comment_author . '.',
				) );
				return;
			}
		}

		/**
		 * Message slack with a payload
		 *
		 * @param string $url The incoming webhook
		 * @param array $payload All of the data slack needs to post a message
		 * @return null
		 */
		function send_message( $url, $payload ) {
			if ( isset( $url ) && ! empty( $url ) ) {
				wp_remote_post( $url , array(
					'method'   => 'POST',
					'body'     => json_encode( $payload ),
				) );
			}
		}

		/**
		 * Output the settings for the Slack integration
		 *
		 * @param  array $settings The settings array
		 * @return null
		 */
		function settings( $settings ) {
			?>
			<h1><?php esc_html_e( 'Slack', 'get-notified' ); ?></h1>
			<table class="form-table">
				<tbody>

					<tr>
						<th><?php esc_html_e( 'Enable', 'get-notified' ); ?></th>
						<td>
							<input type="checkbox" name="<?php esc_attr_e( $this->slug ); ?>-enable" value="yes" <?php echo ( isset( $settings[ $this->slug . '-enable' ] ) && $settings[ $this->slug . '-enable' ] ? 'checked="checked"' : ''); ?>/>
						</td>
					</tr>

					<tr style="<?php ( ! isset( $settings[ $this->slug . '-enable' ] ) ? esc_attr_e( $this->hide_setting() ) : '' ) ?>">
						<th><?php esc_html_e( 'Webhook URL', 'get-notified' ); ?></th>
						<td>
							<input type="text" class="regular-text" name="<?php esc_attr_e( $this->slug ); ?>-webhook" value="<?php echo ( isset( $settings[ $this->slug . '-webhook' ] ) ? esc_attr( $settings[ $this->slug . '-webhook' ] ) : '' ); ?>"/>
							<p class="description"><?php esc_html_e( 'Create a', 'get-notified' ); ?> <a href="https://my.slack.com/services/new/incoming-webhook/" target="_blank"><?php esc_html_e( 'Slack Webhook', 'get-notified' ); ?></a> <?php esc_html_e( 'and then save the URL here.  This Webhook will be used to send data to Slack.', 'get-notified' ); ?></p>
						</td>
					</tr>

				</tbody>
			</table>
			<?php
		}
	}

	$gnt_slack_class = new GNT_Slack();

endif;
