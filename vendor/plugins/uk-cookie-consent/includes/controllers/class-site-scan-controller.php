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
class Site_Scan_Controller extends Menu_Controller {

	private static $last_request = null;

	public static function hooks() {

		// Handle new scan request.
		if ( array_key_exists( 'action', $_REQUEST ) && 'new-scan' === $_REQUEST['action'] ) {

			add_action( 'admin_init', [ get_called_class(), 'handle_new_scan_request' ] );

		}

		// Register our settings.
		add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );

		// Show the Update nag.
		add_action( 'admin_notices', [ __CLASS__, 'maybe_update_notice' ] );

		// Run the standard menu hooks.
		parent::hooks();

	}

	public static function register_settings() {

		// Register the API Key Setting.
		register_setting( 'termly_site_scan', 'termly_site_scan', [ 'sanitize_callback' => [ new Site_Scan_Model, 'sanitize_site_scan' ] ] );

		// Add a section to the Settings API.
		add_settings_section(
			'termly_site_scan_section',
			'',
			[ __CLASS__, 'section_header' ],
			'termly_site_scan'
		);

		add_settings_field(
			'termly_site_scan',
			__( 'Schedule Automatic Scans', 'uk-cookie-consent' ),
			[ __CLASS__, 'site_scan_field' ],
			'termly_site_scan',
			'termly_site_scan_section'
		);

	}

	public static function section_header( $args = [] ) {
		// Don't output a heading.
	}

	public static function site_scan_field( $args = [] ) {

		$feature_set_cache_key = 'termly-feature-set';
		$feature_set           = get_transient( $feature_set_cache_key );
		if ( false === $feature_set ) {

			delete_transient( $feature_set_cache_key );

			$request = Termly_API_Controller::call( 'GET', 'feature_set' );
			if ( 200 === wp_remote_retrieve_response_code( $request ) && ! is_wp_error( $request ) ) {

				$feature_set = json_decode( wp_remote_retrieve_body( $request ) );
				set_transient( $feature_set_cache_key, $feature_set, DAY_IN_SECONDS );

			}

		}

		$auto_scan = isset( $feature_set->auto_scan ) ? $feature_set->auto_scan : new \stdClass();
		$site_scan = get_option(
			'termly_site_scan',
			[
				'enabled'   => 0,
				'frequency' => 'trimonthly',
			]
		);
		?>
		<p><label class="checkbox-container" for="termly-site-scan-enabled">
			<input type="checkbox" name="termly_site_scan[enabled]" id="termly-site-scan-enabled" value="1" <?php checked( 1, $site_scan['enabled'] ); ?>>
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path class="border" d="M3.5 6C3.5 4.61929 4.61929 3.5 6 3.5H18C19.3807 3.5 20.5 4.61929 20.5 6V18C20.5 19.3807 19.3807 20.5 18 20.5H6C4.61929 20.5 3.5 19.3807 3.5 18V6Z" fill="white" stroke="#CED4DA"/>
				<path class="checkmark" fill-rule="evenodd" clip-rule="evenodd" d="M15.4937 9.25628C15.8383 8.91457 16.397 8.91457 16.7416 9.25628C17.0861 9.59799 17.0861 10.152 16.7416 10.4937L11.4474 15.7437C11.1029 16.0854 10.5442 16.0854 10.1996 15.7437L7.25844 12.8271C6.91385 12.4853 6.91385 11.9313 7.25844 11.5896C7.60302 11.2479 8.16169 11.2479 8.50627 11.5896L10.8235 13.8876L15.4937 9.25628Z" fill="#4672FF"/>
			</svg>
			<span><?php esc_html_e( 'I want to automatically scan my website', 'uk-cookie-consent' ); ?></span>
		</label></p>
		<p><label for="termly-site-scan-frequency">
			<select name="termly_site_scan[frequency]" id="termly-site-scan-frequency" <?php echo ( '' === checked( 1, $site_scan['enabled'], false ) ? 'disabled="disabled"' : '' ); ?>>
				<option value="trimonthly" <?php selected( 'trimonthly', $site_scan['frequency'] ); ?><?php echo ( property_exists( $auto_scan, 'trimonthly' ) && true === $auto_scan->trimonthly ) ? '' : ' disabled="disabled"'; ?>><?php esc_html_e( 'Every 3 Months', 'uk-cookie-consent' ); ?></option>
				<option value="monthly" <?php selected( 'monthly', $site_scan['frequency'] ); ?><?php echo ( property_exists( $auto_scan, 'monthly' ) && true === $auto_scan->monthly ) ? '' : ' disabled="disabled"'; ?>><?php esc_html_e( 'Every Month', 'uk-cookie-consent' ); ?></option>
				<option value="weekly" <?php selected( 'weekly', $site_scan['frequency'] ); ?><?php echo ( property_exists( $auto_scan, 'weekly' ) && true === $auto_scan->weekly ) ? '' : ' disabled="disabled"'; ?>><?php esc_html_e( 'Every Week', 'uk-cookie-consent' ); ?></option>
			</select>
		</label></p>
		<?php
	}

	public static function menu() {

		add_submenu_page(
			'termly',
			__( 'Site Scan', 'uk-cookie-consent' ),
			__( 'Site Scan', 'uk-cookie-consent' ),
			'manage_options',
			'site-scan',
			[ get_called_class(), 'menu_page' ]
		);

	}

	public static function menu_page() {

		require_once TERMLY_VIEWS . 'site-scan.php';

	}

	public static function handle_new_scan_request() {

		self::$last_request = Termly_API_Controller::call( 'POST', 'website/scan_cookie' );
		add_action( 'admin_notices', [ __CLASS__, 'new_scan_notice' ] );

	}

	public static function new_scan_notice() {

		if ( 204 === wp_remote_retrieve_response_code( self::$last_request ) && ! is_wp_error( self::$last_request ) ) {

			require_once TERMLY_VIEWS . 'new-scan-success-notice.php';

		} else {

			// Set a default to empty in case we don't get a usable error message.
			$error_msg = '';
			if ( is_wp_error( self::$last_request ) ) {

				$error_msg = self::$last_request->get_error_message();

			} elseif ( 400 === wp_remote_retrieve_response_code( self::$last_request ) ) {

				$body = json_decode( wp_remote_retrieve_body( self::$last_request ) );

				if ( property_exists( $body, 'error' ) ) {

					$error_msg = $body->error;

				} else {

					$error_msg = wp_remote_retrieve_response_message( self::$last_request );

				}

			}
			require_once TERMLY_VIEWS . 'new-scan-error-notice.php';

		}

	}

	public static function maybe_update_notice() {

		global $current_screen;

		$site_scan = get_option(
			'termly_site_scan',
			[
				'enabled'   => 0,
			]
		);

		$feature_set_cache_key = 'termly-feature-set';
		$feature_set           = get_transient( $feature_set_cache_key );
		if ( false === $feature_set ) {

			delete_transient( $feature_set_cache_key );

			$request = Termly_API_Controller::call( 'GET', 'feature_set' );
			if ( 200 === wp_remote_retrieve_response_code( $request ) && ! is_wp_error( $request ) ) {

				$feature_set = json_decode( wp_remote_retrieve_body( $request ) );
				set_transient( $feature_set_cache_key, $feature_set, DAY_IN_SECONDS );

			}

		}

		if ( ! isset( $feature_set->auto_scan ) ) {
			return;
		}
		$auto_scan = $feature_set->auto_scan;
		if ( 'termly_page_site-scan' === $current_screen->id && Account_API_Controller::is_free() && 0 !== $site_scan['enabled'] ) {

			require_once TERMLY_VIEWS . 'site-scan-update-notice.php';

		}

	}

	public static function get_last_scanned() {

		$website = get_option( 'termly_website', (object) [ 'current_report_updated_at' => __( 'No Scans Available', 'uk-cookie-consent' ) ] );
		$timestamp = strtotime( $website->current_report_updated_at );
		if ( false !== $timestamp ) {

			$website->current_report_updated_at = sprintf(
				// Translators: The date of the last scan.
				__( 'Last scanned on %s', 'uk-cookie-consent' ),
				gmdate( 'M d, Y', $timestamp )
			);

		}

		return $website->current_report_updated_at;

	}

}
Site_Scan_Controller::hooks();
