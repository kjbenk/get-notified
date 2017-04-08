<?php

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add a action link to the settings page
 * @param  array $links All of the current links
 * @return array		All of the new links
 */
function gnt_plugin_action_links( $links ) {
	$settings_link = array(
		'<a href="' . admin_url( 'options-general.php?page=get-notified' ) . '">' . esc_html__( 'Settings', 'get-notified' ) . '</a>',
	);
	return array_merge( $links, $settings_link );
}
add_filter( 'plugin_action_links_' . plugin_basename( GET_NOTIFIED_PLUGIN_FILE ), 'gnt_plugin_action_links' );

/**
 * Force a URL redirect via Javascript
 * @param  string $url The URL you want to redirect to
 * @return null
 */
function gnt_force_redirect( $url ) {
	if ( isset( $url ) && ! empty( $url ) ) {
		?><script type="text/javascript">
			window.location = '<?php echo esc_attr__( $url ); ?>';
		</script><?php
	}
}
