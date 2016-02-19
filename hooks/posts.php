<?php

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) exit;

class GNT_Hook_Post {

    /**
     * Function that is run upon creating instance
     */
    public function __construct() {
        add_action( 'gnt_setup_hooks',   array( $this, 'add_hooks' ) );
        add_action( 'transition_post_status', array( $this, 'post_status_change' ), 10, 3 );
    }

    /**
     * Add a hooks
     */
    function add_hooks() {
        $post_statuses = get_post_stati();
        foreach ( $post_statuses as $post_status ) {
            gnt_register_hook( 'post_status_' . $post_status, array(
                'slug'  => 'post_status_' . $post_status,
                'name'  => __( 'Post ' . ucfirst( $post_status ), 'gnt' ),
                'desc'  => __( 'Triggered when a post\'s status changes to ' . $post_status . '.', 'gnt' ),
            ) );
        }
    }

    /**
     * Post status change
     *
     * @param  string   $new_status The new status of the post
     * @param  string   $old_status The old statud of the post
     * @param  WP_Post  $post       The WP_Post object
     * @return null
     */
    function post_status_change( $new_status, $old_status, $post ) {
        $post_statuses = get_post_stati();
        $hook_settings = gnt_get_hook_settings();

        foreach ( $post_statuses as $post_status ) {
            if ( isset( $hook_settings['post_status_' . $post_status . '-enable'] ) && $hook_settings['post_status_' . $post_status . '-enable'] ) {
                if ( $old_status != $post_status && $new_status == $post_status ) {
                    do_action( 'gnt_hook_post_status_' . $post_status, array(
                        'hook'          => 'post_' . $post_status,
                        'post'          => $post,
                        'post_status'    => $post_status,
                    ) );
                }
            }
        }
    }
}

$gnt_hook_post_class = new GNT_Hook_Post();
