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
 * Add a action link to the settings page
 * @param  array $links All of the current links
 * @return array        All of the new links
 */
function gnt_plugin_action_links($links) {
    $settings_link = array(
        '<a href="' . admin_url( 'options-general.php?page=get-notified' ) . '">' . esc_attr__( 'Settings', 'gnt' ) . '</a>',
    );
    return array_merge( $links, $settings_link );
}
add_filter( 'plugin_action_links_' . plugin_basename( GET_NOTIFIED_PLUGIN_FILE ), 'gnt_plugin_action_links' );

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

    include_once( GET_NOTIFIED_PLUGIN_DIR . 'views/settings.php' );
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

    foreach ( $data as $key => $item ) {
        $data[$key] = sanitize_text_field( $item );
    }

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
