<?php

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'GNT_Hook_Comment' ) ) :

	class GNT_Hook_Comment {

		/**
		 * Function that is run upon creating instance
		 */
		public function __construct() {
			add_action( 'gnt_setup_hooks',   array( $this, 'add_hooks' ) );
			add_action( 'wp_insert_comment', array( $this, 'comment_created' ), 10, 2 );
		}

		/**
		 * Add a hooks
		 */
		function add_hooks() {
			gnt_register_hook( 'comment_created', array(
		    'slug'  => 'comment_created',
		    'name'  => __( 'Comment Created', 'get-notified' ),
		    'desc'  => __( 'Triggered when a comment is added to a post.', 'get-notified' ),
			) );
		}

		/**
		 * Post status change
		 *
		 * @param  string   $new_status The new status of the post
		 * @param  string   $old_status The old statud of the post
		 * @param  WP_Post  $post	   The WP_Post object
		 * @return null
		 */
		function comment_created( $comment_id, $comment_object ) {
			$hook_settings = gnt_get_hook_settings();
			$post = get_post( $comment_object->comment_post_ID );

      if ( isset( $hook_settings['comment_created-enable'] ) && $hook_settings['comment_created-enable'] ) {
        if ( isset( $comment_id ) && isset( $comment_object ) ) {
          do_action( 'gnt_hook_comment_created', array(
            'hook'		  => 'comment_created',
            'text'		  => $post->post_title . __( ' has a new comment by ' . $comment_object->comment_author, 'get-notified' ),
            'post'		  => $post,
            'comment_id'   => $comment_id,
            'comment_object'   => $comment_object,
          ) );
        }
      }
		}
	}

	$gnt_hook_comment_class = new GNT_Hook_Comment();

endif;
