<?php
/**
 * This file contains the Site Scan model class.
 *
 * @package termly
 */

namespace termly;

/**
 * This class handles the routing for the dashboard experience.
 */
class Site_Scan_Model {

	public static function sanitize_site_scan( $value ) {

		static $pass_count = 0;
		$message           = null;
		$type              = null;

		$value = wp_parse_args(
			$value,
			[
				'enabled'   => 0,
				'frequency' => 'trimonthly',
			]
		);

		if ( 1 > $pass_count ) {

			if ( '' !== $value ) {

				$response = Termly_API_Controller::call( 'PUT', 'website/scan_settings', [ 'scan_enabled' => boolval( $value['enabled'] ), 'scan_period' => $value['frequency'], ] );
				if ( 200 === wp_remote_retrieve_response_code( $response ) && ! is_wp_error( $response ) ) {

					$type = 'updated';
					if ( false === get_option( 'termly_site_scan' ) ) {

						// Set the message.
						$message = __( 'Your scan settings have been successfully saved.', 'uk-cookie-constent' );

					} else {

						// Set the message.
						$message = __( 'Your scan settings have been successfully updated.', 'uk-cookie-constent' );

					}

				} else {

					$type    = 'error';
					$message = __( 'The API Key entered is not valid.', 'uk-cookie-constent' );
					$value   = '';

					if ( is_wp_error( $response ) ) {

						$message = $response->get_error_message();

					} elseif ( 400 === wp_remote_retrieve_response_code( $response ) ) {

						$body = json_decode( wp_remote_retrieve_body( $response ) );

						if ( property_exists( $body, 'error' ) ) {

							$message = $body->error;

						} else {

							$message = wp_remote_retrieve_response_message( $response );

						}

					}

				}

				add_settings_error(
					'termly_site_scan',
					esc_attr( 'settings_updated' ),
					$message,
					$type
				);

			}

		}

		++$pass_count;

		return $value;

	}

}
