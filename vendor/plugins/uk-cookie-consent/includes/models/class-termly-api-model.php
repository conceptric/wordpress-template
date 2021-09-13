<?php
/**
 * This file contains the Termly API model class.
 *
 * @package termly
 */

namespace termly;

/**
 * This class handles the routing for the dashboard experience.
 */
class Termly_API_Model {

	public static function store_business_settings_from_api() {

		$termly_website = get_option( 'termly_website' );
		$termly_business_settings = wp_parse_args(
			(array) $termly_website,
			[
				'company_legal_name' => '',
				'company_email'      => '',
				'company_phone'      => '',
				'company_fax'        => '',
				'company_address'    => '',
				'company_country'    => '',
				'company_state'      => '',
				'company_city'       => '',
				'company_zip'        => '',
			]
		);

		update_option(
			'termly_business_settings',
			[
				'company_name'      => $termly_business_settings['company_legal_name'],
				'email'             => $termly_business_settings['company_email'],
				'phone'             => $termly_business_settings['company_phone'],
				'fax'               => $termly_business_settings['company_fax'],
				'address'           => $termly_business_settings['company_address'],
				'country'           => $termly_business_settings['company_country'],
				'state'             => $termly_business_settings['company_state'],
				'city'              => $termly_business_settings['company_city'],
				'postal_code'       => $termly_business_settings['company_zip'],
				'agree_to_policies' => '1'
			],
			false
		);

	}

	public static function update_style_data() {

		$banner_key            = 'termly_banner';
		$cookie_preference_key = 'termly_cookie_preference_button';
		$website_key           = 'termly_website';

		Termly_API_Controller::call( 'GET', 'style' );
		$response = Termly_API_Controller::call( 'GET', 'website' );
		if ( 200 === wp_remote_retrieve_response_code( $response ) && ! is_wp_error( $response ) ) {

			// Cache the website object.
			$website = json_decode( wp_remote_retrieve_body( $response ) );
			update_option( $website_key, $website, false );

			if ( property_exists( $website, 'code_snippet' ) ) {

				// Get the code snippet.
				if ( property_exists( $website->code_snippet, 'banner' ) ) {

					$banner = $website->code_snippet->banner;
					update_option( $banner_key, $banner, false );

				}

				// Get the cookie preference button.
				if ( property_exists( $website->code_snippet, 'cookie_preference_button' ) ) {

					$cookie_preference_button = $website->code_snippet->cookie_preference_button;
					update_option( $cookie_preference_key, $cookie_preference_button, false );

				}

			}

		} else {

			error_log( print_r( [ $response ], true ) );

		}

	}

	public static function sanitize_api_key( $value ) {

		static $api_key_count = 0;
		$message              = null;
		$type                 = null;

		if ( 1 > $api_key_count ) {

			if ( '' !== $value ) {

				// Test the API with the submitted value.
				if ( ! isset( $_REQUEST['termly_api_key'] ) ) {
					$_REQUEST['termly_api_key'] = $value;
				}
				add_filter( 'termly_api_headers', [ new Termly_API_Controller(), 'add_temporary_auth' ] );
				$response = Termly_API_Controller::call( 'GET', 'website' );
				remove_filter( 'termly_api_headers', [ new Termly_API_Controller(), 'add_temporary_auth' ] );
				if ( 200 === wp_remote_retrieve_response_code( $response ) && ! is_wp_error( $response ) ) {

					if ( false === get_option( 'termly_api_key' ) ) {

						// Set the message.
						$type    = 'updated';
						$message = __( 'The API Key has been successfully saved.', 'uk-cookie-constent' );

					} else {

						// Set the message.
						$type    = 'updated';
						$message = __( 'The API Key has been successfully updated.', 'uk-cookie-constent' );

					}

					update_option( 'termly_website', json_decode( wp_remote_retrieve_body( $response ) ), false );
					self::store_business_settings_from_api();

					$_REQUEST['_wp_http_referer'] = str_replace(
						home_url(),
						'',
						add_query_arg(
							[
								'page' => 'site-scan',
							],
							admin_url( 'admin.php' )
						)
					);

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

			} else {

				$type    = 'error';
				$message = __( 'The API Key can not be empty.', 'uk-cookie-constent' );
				$value   = '';

			}

			add_settings_error(
				'termly_api_key',
				esc_attr( 'settings_updated' ),
				$message,
				$type
			);

		}

		++$api_key_count;

		return $value;

	}

	public static function sanitize_business_info( $value ) {

		static $business_info_count = 0;
		$message                    = null;
		$type                       = null;

		if ( 1 > $business_info_count ) {

			if ( ! isset( $value['agree_to_terms'] ) ) {

				$type = 'error';
				$message = __( 'You must agree to the terms before creating an account.', 'uk-cookie-consent' );
				$value   = [];

			} elseif ( array_search( '', $value, true ) !== false ) {

				$type    = 'error';
				$message = __( 'All text fields must be filled in completely.', 'uk-cookie-constent' );
				$value   = [];

			} else {

				$value = wp_parse_args(
					$value,
					[
						'first_name'         => '',
						'last_name'          => '',
						'email'              => '',
						'password'           => '',
						'confirm_password'   => '',
						'agree_to_marketing' => '0',
						'agree_to_terms'     => '0',
					]
				);

				// Remove the authentication because this request doesn't come from a a specific user account.
				add_filter( 'termly_api_headers', [ new Termly_API_Controller(), 'remove_auth' ] );
				$response = Termly_API_Controller::call(
					'POST',
					'users/sign_up',
					[
						'first_name'             => $value['first_name'],
						'last_name'              => $value['last_name'],
						'email'                  => $value['email'],
						'password'               => $value['password'],
						'password_confirmation'  => $value['confirm_password'],
						'accept_marketing_offer' => boolval( $value['agree_to_marketing'] ),
						'agree_policy'           => boolval( $value['agree_to_terms'] ),
						'website_url'            => home_url(),
					]
				);
				remove_filter( 'termly_api_headers', [ new Termly_API_Controller(), 'remove_auth' ] );
				$body = json_decode( wp_remote_retrieve_body( $response ) );

				if ( 201 === wp_remote_retrieve_response_code( $response ) && ! is_wp_error( $response ) ) {

					// Set the message.
					$type    = 'updated';
					$message = __( 'You have signed up successfully! You can now start scanning your website.', 'uk-cookie-constent' );
					$value   = 'connected';

					update_option( 'termly_api_key', $body->website_token, false );

					self::update_style_data();
					self::store_business_settings_from_api();

					$_REQUEST['_wp_http_referer'] = str_replace(
						home_url(),
						'',
						add_query_arg(
							[
								'page' => 'site-scan',
							],
							admin_url( 'admin.php' )
						)
					);

				} else {

					if ( is_wp_error( $response ) ) {

						$message = $response->get_error_message();

					} elseif ( 400 === wp_remote_retrieve_response_code( $response ) ) {

						$type    = 'error';
						$message = __( 'Your account could not be created for the following reasons:', 'uk-cookie-constent' );
						$value   = '';

						if ( count( $body[0] ) > 1 ) {

							$message .= '<ul>';
							foreach ( $body[0] as $error ) {
								$message .= sprintf( '<li>- %s</li>', $error );
							}
							$message .= '</ul>';

						} else {

							$message .= $body[0][0];

						}

					}

					$value = '';

				}

			}

			add_settings_error(
				'termly_business_info',
				esc_attr( 'settings_updated' ),
				$message,
				$type
			);

		}

		++$business_info_count;

		return $value;

	}

	public static function sanitize_business_settings( $value ) {

		static $business_settings_count = 0;
		$message                        = null;
		$type                           = null;

		if ( 1 > $business_settings_count ) {

			if ( ! isset( $value['agree_to_policies'] ) ) {

				$type    = 'error';
				$message = __( 'You must agree to the update confirmation before updating your information.', 'uk-cookie-consent' );
				$value   = [];

			} else {

				$value = wp_parse_args(
					$value,
					[
						'company_name' => '',
						'email'        => '',
						'phone'        => '',
						'fax'          => '',
						'address'      => '',
						'country'      => '',
						'state'        => '',
						'city'         => '',
						'postal_code'  => '',
					]
				);

				// Remove the authentication because this request doesn't come from a a specific user account.
				$response = Termly_API_Controller::call(
					'PUT',
					'website',
					[
						'company_legal_name' => $value['company_name'],
						'company_email'      => $value['email'],
						'company_phone'      => $value['phone'],
						'company_fax'        => $value['fax'],
						'company_address'    => $value['address'],
						'company_zip'        => $value['postal_code'],
						'company_state'      => $value['state'],
						'company_city'       => $value['city'],
						'company_country'    => $value['country'],
					]
				);
				if ( 200 === wp_remote_retrieve_response_code( $response ) && ! is_wp_error( $response ) ) {

					$website = json_decode( wp_remote_retrieve_body( $response ) );

					// Set the message.
					$type    = 'updated';
					$message = __( 'Your business information has been updated.', 'uk-cookie-constent' );

					update_option( 'termly_website', $website, false );

					if ( property_exists( $website, 'code_snippet' ) ) {

						// Get the code snippet.
						if ( property_exists( $website->code_snippet, 'banner' ) ) {

							$banner = $website->code_snippet->banner;
							update_option( 'termly_banner', $banner, false );

						}

						// Get the cookie preference button.
						if ( property_exists( $website->code_snippet, 'cookie_preference_button' ) ) {

							$cookie_preference_button = $website->code_snippet->cookie_preference_button;
							update_option( 'termly_cookie_preference_button', $cookie_preference_button, false );

						}

					}

				} else {

					if ( is_wp_error( $response ) ) {

						$message = $response->get_error_message();

					} elseif ( 400 === wp_remote_retrieve_response_code( $response ) ) {

						$type    = 'error';
						$message = __( 'Your business information could not be updated because the request encountered an error. Please try again shortly..', 'uk-cookie-constent' );
						$value   = [];

					}

					$value = [];

				}

			}

			add_settings_error(
				'termly_business_settings',
				esc_attr( 'settings_updated' ),
				$message,
				$type
			);

		}

		++$business_settings_count;

		return $value;

	}

}
