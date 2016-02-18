<?php

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) exit;

class GNT_Hook_Post {

    /**
     * Function that is run upon creating instance
     */
    public function __construct() {
        add_action( 'gnt_setup_hooks',   array( $this, 'add_hooks' ) );

        // Hooks

        add_action( 'transition_post_status', array( $this, 'post_published' ), 10, 3 );
    }

    /**
     * Add a hooks
     */
    function add_hooks() {
        gnt_register_hook( 'post_published', array(
            'name'	=> __( 'Post Published', 'gnt' ),
        ) );
        gnt_register_hook( 'post_trashed', array(
            'name'	=> __( 'Post Trashed', 'gnt' ),
        ) );
    }

    /**
     * Post is published
     *
     * @param  string   $new_status The new status of the post
     * @param  string   $old_status The old statud of the post
     * @param  WP_Post  $post       The WP_Post object
     * @return null
     */
    function post_published( $new_status, $old_status, $post ) {
        if ( $old_status != 'publish' && $new_status == 'publish' ) {
            $settings = gnt_get_settings();
            if ( isset( $settings['hook-post_published'] ) && $settings['hook-post_published'] ) {
                do_action( 'gnt_hook_post_published', array(
                    'hook'  => 'post_published',
                    'post'  => $post,
                ) );
            }
        }
    }

    /**
     * Post is trashed
     *
     * @param  string   $new_status The new status of the post
     * @param  string   $old_status The old statud of the post
     * @param  WP_Post  $post       The WP_Post object
     * @return null
     */
    function post_published( $new_status, $old_status, $post ) {
        if ( $old_status != 'trash' && $new_status == 'trash' ) {
            $settings = gnt_get_settings();
            if ( isset( $settings['hook-post_trashed'] ) && $settings['hook-post_trashed'] ) {
                do_action( 'gnt_hook_post_trashed', array(
                    'hook'  => 'post_trashed',
                    'post'  => $post,
                ) );
            }
        }
    }
}

$gnt_hook_post_class = new GNT_Hook_Post();
