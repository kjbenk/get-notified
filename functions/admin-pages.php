<?php

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add submenu page
 * @return null
 */
function gnt_admin_page() {

    add_menu_page(
        __( 'Get Notified', 'gnt' ),
        __( 'Get Notified', 'gnt' ),
        'manage_options',
        'get-notified',
        'gnt_info_page_content',
        'dashicons-megaphone'
    );

    // add_submenu_page(
    //     'get-notified',
    //     __( 'Settings', 'gnt' ),
    //     __( 'Settings', 'gnt' ),
    //     'manage_options',
    //     'get-notified',
    //     'gnt_settings_page_content'
    // );

    add_submenu_page(
        'get-notified',
        __( 'Information', 'gnt' ),
        __( 'Information', 'gnt' ),
        'manage_options',
        'get-notified',
        'gnt_info_page_content'
    );

    add_submenu_page(
        'get-notified',
        __( 'Hooks', 'gnt' ),
        __( 'Hooks', 'gnt' ),
        'manage_options',
        'get-notified-hooks',
        'gnt_hooks_page_content'
    );

    add_submenu_page(
        'get-notified',
        __( 'Integrations', 'gnt' ),
        __( 'Integrations', 'gnt' ),
        'manage_options',
        'get-notified-integrations',
        'gnt_integrations_page_content'
    );
}
add_action( 'admin_menu', 'gnt_admin_page' );

/**
 * Add the contents of the settings page
 * @return null
 */
function gnt_settings_page_content() {
    $settings = gnt_get_settings();

    // Save the settings

    if ( isset( $_POST['submit'] ) && check_admin_referer( 'gnt_save_settings' ) ) {
        gnt_save_settings($_POST);
        gnt_force_redirect( get_admin_url() . 'admin.php?page=get-notified' );
    }

    include_once( GET_NOTIFIED_PLUGIN_DIR . 'views/settings.php' );
}

/**
 * The content for the Info Page
 *
 * @return null
 */
function gnt_info_page_content() {
    ?><div class="wrap">
        <h1><?php esc_attr_e( 'Information', 'gnt' ); ?></h1>
        <p>This is an open source project that I started to give developers the tools they need to create great notifications for a WordPress site. I welcome any help from developers who want to contribute :)</p>
        <p>There are two main parts of the plugin: Hooks and Integrations. Hooks are basically the WordPress defined action hooks for any specific event. This can be when a post is published or when a theme is activated. Integrations are the tools used to notify people of these events, like email or slack. Developers can add new Hooks and/or Integrations to the plugin to perform the tasks they need.</p>

        <a href="https://github.com/kjbenk/get-notified" target="_blank">Contribute - Read more</a> |
        <a href="https://github.com/kjbenk/get-notified/issues" target="_blank">Report a Bug</a>

        <p>
            <iframe src="https://ghbtns.com/github-btn.html?user=kjbenk&repo=get-notified&type=star&count=true" frameborder="0" scrolling="0" width="170px" height="20px"></iframe>
        </p>
        <p>
            <iframe src="https://ghbtns.com/github-btn.html?user=kjbenk&repo=get-notified&type=fork&count=true" frameborder="0" scrolling="0" width="170px" height="20px"></iframe>
        </p>
    </div><?php
}

/**
 * Show the Hooks page content
 * @return null
 */
function gnt_hooks_page_content() {
    $hooks = gnt_get_hooks();
    $hook_settings = gnt_get_hook_settings();

    // Save the settings

    if ( isset( $_POST['submit'] ) && check_admin_referer( 'gnt_save_hooks' ) ) {
        gnt_save_hook_settings( $_POST );
        gnt_force_redirect( $_POST[ '_wp_http_referer' ] );
    }

    include_once( GET_NOTIFIED_PLUGIN_DIR . 'views/hooks.php' );
}

/**
 * Show the Integrations page content
 * @return null
 */
function gnt_integrations_page_content() {
    $integrations = gnt_get_integrations();
    $integration_settings = gnt_get_integration_settings();

    // Save the settings

    if ( isset( $_POST['submit'] ) && check_admin_referer( 'gnt_save_integrations' ) ) {
        gnt_save_integration_settings( $_POST );
        gnt_force_redirect( $_POST[ '_wp_http_referer' ] );
    }

    include_once( GET_NOTIFIED_PLUGIN_DIR . 'views/integrations.php' );
}
