<?php

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'GNT_Email' ) ) :

	class GNT_Email extends GNT_Integration {

		/**
		 * The slug for this integration
		 *
		 * @var string
		 */
		private $slug = 'email';

		/**
		 * Adds a new line to the email
		 *
		 * @var string
		 */
		private $new_line = "\r\n";

		/**
		 * Function that is run upon creating instance
		 */
		public function __construct() {
			parent::__construct();

			add_action( 'gnt_setup_integrations',   array( $this, 'add_integration' ) );
			add_action( 'gnt_integration_send',	 array( $this, 'send_message' ), 10, 3 );

			add_action( 'gnt_integration_content_' . $this->slug,   array( $this, 'settings' ) );
		}

		/**
		 * Add a email integration
		 */
		function add_integration() {
			gnt_register_integration( $this->slug, array(
				'name'	=> __( 'Email', 'get-notified' ),
				'hooks' => true,
			) );
		}

		/**
		 * Should we send the message over to email?
		 *
		 * @param  array $data	  The data to be sent
		 * @param  array $settings  The Integration settings
		 * @param  bool			 Is this a default message?
		 * @return null
		 */
		function send_message( $data, $settings, $default ) {

			if ( ! isset( $settings[ $this->slug . '-enable' ] ) ) {
				return;
			}

			// Check if the to emails are set

			$to_emails = $settings[ $this->slug . '-to-emails' ];
			if ( empty( $to_emails ) ) {
				return;
			}

			$subject = '';
			$message = '';
			$headers = array();

			// Check if this is a default message

			if ( $default ) {
				$subject = $data['text'];
				$message = $data['text'];
			}

			// Post Status

			if ( isset( $data['post_status'] ) && isset( $data['post'] ) ) {
				$subject = $data['post']->post_title . ' changed status to ' . $data['post_status'];
				$message = $data['post']->post_title . ' changed status to ' . $data['post_status'] . '.' .
					$this->new_line .
					$this->new_line .
					'View: ' . get_post_permalink( $data['post']->ID );
			}

      // Comment Created

      if ( isset( $data['comment_object'] ) && isset( $data['post'] ) ) {
          $subject = $data['post']->post_title . ' has a new comment by ' . $data['comment_object']->comment_author;
          $message = $data['post']->post_title . ' has a new comment by ' . $data['comment_object']->comment_author . ':' .
              $this->new_line .
	            $this->new_line .
							'"' . $data['comment_data']->comment_content . '"' .
							$this->new_line .
							$this->new_line .
              'View: ' . get_post_permalink( $data['post']->ID );
      }

			wp_mail(
				apply_filters( 'gnt_integration_' . $this->slug . '_to_emails', $to_emails ),
				apply_filters( 'gnt_integration_' . $this->slug . '_subject', $subject ),
				apply_filters( 'gnt_integration_' . $this->slug . '_message', $message ),
				apply_filters( 'gnt_integration_' . $this->slug . '_headers', $headers )
			);
		}

		/**
		 * Output the settings for the Email integration
		 *
		 * @param  array $settings The settings array
		 * @return null
		 */
		function settings( $settings ) {
			?>
			<h1><?php esc_html_e( 'Email', 'get-notified' ); ?></h1>
			<table class="form-table">
				<tbody>

					<tr>
						<th><?php esc_html_e( 'Enable', 'get-notified' ); ?></th>
						<td>
							<input type="checkbox" name="<?php esc_attr_e( $this->slug ); ?>-enable" value="yes" <?php echo ( isset( $settings[ $this->slug . '-enable' ] ) && $settings[ $this->slug . '-enable' ] ? 'checked="checked"' : ''); ?>/>
						</td>
					</tr>

					<tr style="<?php if ( !isset($settings[ $this->slug . '-enable' ]) ) { esc_attr_e( $this->hide_setting() ); } ?>">
						<th><?php esc_html_e( 'To Email', 'get-notified' ); ?></th>
						<td>
							<input type="text" class="regular-text" name="<?php esc_attr_e( $this->slug ); ?>-to-emails" value="<?php echo ( isset( $settings[ $this->slug . '-to-emails' ] ) && ! empty( $settings[ $this->slug . '-to-emails' ] ) ? esc_attr( $settings[ $this->slug . '-to-emails' ] ) : esc_attr( get_option( 'admin_email' ) ) ); ?>"/>
							<p class="description"><?php esc_html_e( 'What emails should get this notification? Use a comma separated list for multiple emails.', 'get-notified' ); ?></p>
						</td>
					</tr>

				</tbody>
			</table>
			<?php
		}
	}

	$gnt_email_class = new GNT_Email();

endif;
