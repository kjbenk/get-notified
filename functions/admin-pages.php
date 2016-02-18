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
        'gnt_settings_page_content',
        'dashicons-megaphone'
    );

    add_submenu_page(
        'get-notified',
        __( 'Settings', 'gnt' ),
        __( 'Settings', 'gnt' ),
        'manage_options',
        'get-notified',
        'gnt_settings_page_content'
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
 * Show the Hooks page content
 * @return null
 */
function gnt_hooks_page_content() {
    $hooks = gnt_get_hooks();
    $hook_settings = gnt_get_hook_settings();

    // Save the settings

    if ( isset( $_POST['submit'] ) && check_admin_referer( 'gnt_save_hooks' ) ) {
        gnt_save_hook_settings( $_POST );
        gnt_force_redirect( get_admin_url() . 'admin.php?page=get-notified-hooks' );
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
        gnt_force_redirect( get_admin_url() . 'admin.php?page=get-notified-integrations' );
    }

    include_once( GET_NOTIFIED_PLUGIN_DIR . 'views/integrations.php' );
}
