<?php
/**
 * This file contains the Banner Settings controller class.
 *
 * @package termly
 */

namespace termly;

/**
 * This class handles the routing for the dashboard experience.
 */
class Banner_Settings_Controller extends Menu_Controller {

	public static function hooks() {

		add_action( 'admin_menu', [ __CLASS__, 'menu' ] );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'scripts' ], 10, 1 );

		// Register our settings.
		add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );

	}

	public static function menu() {

		add_submenu_page(
			'termly',
			__( 'Banner Settings', 'uk-cookie-consent' ),
			__( 'Banner Settings', 'uk-cookie-consent' ),
			'manage_options',
			'banner-settings',
			[ __CLASS__, 'menu_page' ]
		);

	}

	public static function menu_page() {

		require_once TERMLY_VIEWS . 'banner-settings.php';

	}

	public static function scripts( $hook ) {

		if ( 'termly_page_banner-settings' !== $hook ) {
			return;
		}

		wp_enqueue_script(
			'termly-banner-settings',
			TERMLY_DIST . 'js/banner-settings.js',
			[ 'jquery' ],
			TERMLY_VERSION,
			true
		);

	}

	/**
	 * Add associated settings.
	 *
	 * @return void
	 */
	public static function register_settings() {

		// Register the API Key Setting.
		register_setting(
			'termly_banner_settings',
			'termly_banner_settings',
			[
				'sanitize_callback' => [ __CLASS__, 'sanitize_settings' ]
			]
		);

		// Add a section to the Settings API.
		add_settings_section(
			'termly_banner_settings_section',
			'',
			'__return_null',
			'termly_banner_settings'
		);

		add_settings_field(
			'termly_banner_settings',
			'',
			'__return_null', // Print this field with the custom view.
			'termly_banner_settings',
			'termly_banner_settings_section'
		);

	}

	/**
	 * Save settings.
	 *
	 * @param  mixed $dirty
	 * @return void
	 */
	public static function sanitize_settings( $dirty ) {

		if ( ! is_array( $dirty ) || empty( $dirty ) ) {
			update_option( 'termly_display_banner', 'no' );
			update_option( 'termly_display_auto_blocker', 'off' );
			return;
		}

		$display_banner = isset( $dirty['display_banner'] ) && 'yes' === $dirty['display_banner'] ? 'yes' : 'no';
		update_option( 'termly_display_banner', $display_banner );

		$auto_block = isset( $dirty['auto_block'] ) && 'on' === $dirty['auto_block'] ? 'on' : 'off';
		update_option( 'termly_display_auto_blocker', $auto_block );

		return;

	}

}
Banner_Settings_Controller::hooks();
