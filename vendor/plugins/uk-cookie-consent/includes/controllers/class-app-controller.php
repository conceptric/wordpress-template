<?php
/**
 * This file contains the Site Scan controller class.
 *
 * @package termly
 */

namespace termly;

/**
 * This class handles the routing for the dashboard experience.
 */
class App_Controller extends Menu_Controller {

	public static function hooks() {

		// Register and enqueue global admin styles.
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'admin_global_styles' ] );

		// Remove all options and cron events.
		add_action( 'admin_init', [ __CLASS__, 'reset_all' ] );

		// Add links to plugin listing.
		add_filter( 'plugin_action_links_' . TERMLY_BASENAME, [ __CLASS__, 'add_action_link' ], 10, 2 );

		// Run the standard menu hooks.
		parent::hooks();

	}

	public static function admin_global_styles() {

		wp_register_style(
			'admin-global',
			TERMLY_URL . 'dist/css/termly.css',
			[],
			TERMLY_VERSION
		);
		wp_enqueue_style( 'admin-global' );

	}

	public static function menu() {

		add_menu_page(
			'Termly',
			'Termly',
			'manage_options',
			'termly',
			[ __CLASS__, 'menu_page' ]
		);

		add_submenu_page(
			'termly',
			__( 'Account', 'uk-cookie-consent' ),
			__( 'Account', 'uk-cookie-consent' ),
			'manage_options',
			'termly',
			[ __CLASS__, 'menu_page' ]
		);

	}

	public static function menu_page() {

		require_once TERMLY_VIEWS . 'main-menu.php';

	}

	public static function add_action_link( $links, $file ) {

		if ( Account_API_Controller::is_free() ) {

			// Add link to the plans page for free users.
			$premium_link = '<a style="font-weight: bold;" href="' . esc_url( Urls::get_plans_url( 'plugin-list' ) ) . '" target="_blank">' . __( 'Upgrade to Termly Pro', 'uk-cookie-consent' ) . '</a>';
			array_unshift( $links, $premium_link );

		}

		return $links;
	}

	public static function reset_all() {

		if (
			(
				! isset( $_REQUEST['_wpnonce'] ) ||
				! isset( $_REQUEST['page'] ) ||
				! isset( $_REQUEST['action'] )
			) ||
			(
				! \wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_REQUEST['_wpnonce'] ) ), 'reset-termly' ) ||
				'termly' !== $_REQUEST['page'] ||
				'disconnect' !== $_REQUEST['action']
			)
		) {
			return false;
		}

		// Transients.
		delete_transient( 'termly-feature-set' );
		delete_transient( 'termly-site-scan-results' );

		// Core data.
		delete_option( 'termly_api_key' );
		delete_option( 'termly_website' );
		delete_option( 'termly_business_info' );

		// Banner Settings.
		delete_option( 'termly_banner' );
		delete_option( 'termly_cookie_preference_button' );

		// Site Scan.
		delete_option( 'termly_site_scan' );

		// Banner display booleans.
		delete_option( 'termly_display_banner' );
		delete_option( 'termly_display_auto_blocker' );

		// Unschedule Cron Events.
		wp_unschedule_hook( 'termly_account_update' );

		// Add success message.
		add_settings_error(
			'termly_api_key',
			esc_attr( 'settings_updated' ),
			__( 'The Termly plugin has been reset and the Termly services have been disconnected.', 'uk-cookie-consent' ),
			'updated'
		);

		wp_safe_redirect(
			add_query_arg(
				[
					'page' => 'termly',
				],
				'admin.php'
			)
		);
		exit;

	}

}
App_Controller::hooks();
