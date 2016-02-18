<?php

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) exit;

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
    unset( $data['submit'] );
    unset( $data['_wpnonce'] );
    unset( $data['_wp_http_referer'] );

    foreach ( $data as $key => $item ) {
        $data[$key] = sanitize_text_field( $item );
    }

    update_option( 'gnt_settings', apply_filters( 'gnt_save_settings', $data ) );
}
