<?php

namespace termly;

class Termly_API_Controller {

	public static function hooks() {

		// Register our settings.
		add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );

		// Send JSON for PUT and POST verbs.
		add_filter( 'termly_api_args', [ __CLASS__, 'maybe_json_body' ], 10, 4 );
		add_filter( 'termly_api_headers', [ __CLASS__, 'maybe_json_header' ], 10, 5 );

	}

	public static function register_settings() {

		// Register the API Key Setting.
		register_setting( 'termly_api_key', 'termly_api_key', [ 'sanitize_callback' => [ new Termly_API_Model(), 'sanitize_api_key' ] ] );
		add_settings_section( 'termly_api_key_section', '', [ __CLASS__, 'empty_header' ], 'termly_api_key' );

		add_settings_field(
			'termly_site_url',
			__( 'Site Name / URL', 'uk-cookie-consent' ),
			[ __CLASS__, 'site_url_field' ],
			'termly_api_key',
			'termly_api_key_section'
		);

		add_settings_field(
			'termly_api_key',
			__( 'API Key', 'uk-cookie-consent' ),
			[ __CLASS__, 'api_key_field' ],
			'termly_api_key',
			'termly_api_key_section'
		);

		// Register the Sign Up Field Settings.
		register_setting( 'termly_business_info', 'termly_business_info', [ 'sanitize_callback' => [ new Termly_API_Model(), 'sanitize_business_info' ] ] );
		add_settings_section( 'termly_business_info_section', '', [ __CLASS__, 'empty_header' ], 'termly_business_info' );

		add_settings_field(
			'termly_business_info',
			__( 'Business Information', 'uk-cookie-consent' ),
			[ __CLASS__, 'business_info_field' ],
			'termly_business_info',
			'termly_business_info_section'
		);

	}

	public static function empty_header( $args = [] ) {
		// No section title output.
	}

	public static function site_url_field( $args = [] ) {
		$website = \get_option( 'termly_website' );
		?>
		<div class="termly-account-page-field-wrapper">
			<input type="text" name="termly_site_url" id="termly_site_url" class="regular-text" value="<?php echo esc_attr( isset( $website->url ) ? $website->url : '' ); ?>" readonly disabled>
		</div>
		<?php
	}

	public static function api_key_field( $args = [] ) {
		$api_key = get_option( 'termly_api_key', '' );
		?>
		<div class="termly-account-page-field-wrapper">
			<input type="text" name="termly_api_key" id="termly_api_key" class="regular-text" value="<?php echo esc_attr( $api_key ); ?>"<?php echo ! empty( $api_key ) ? ' disabled="disabled"' : ''; ?>>
			<?php if ( $api_key && ! empty( $api_key ) ) { ?>
				<a href="<?php echo esc_attr( Urls::get_disconnect_url() ); ?>"><?php esc_html_e( 'Disconnect Site', 'uk-cookie-consent' ); ?></a>
			<?php } ?>
		</div>
		<?php
	}

	public static function business_info_field( $args = [] ) {
		$user = wp_get_current_user();
		?>
		<div class="termly-form new-user">
			<fieldset>
				<label for="termly-first-name"><?php esc_html_e( 'First Name', 'uk-cookie-consent' ); ?> <span class="required">*</span></label>
				<input type="text" name="termly_business_info[first_name]" id="termly-first-name" class="regular-text" placeholder="<?php esc_attr_e( 'Enter your first name', 'uk-cookie-consent' ); ?>" value="<?php echo esc_attr( $user->first_name ); ?>" required>
			</fieldset>
			<fieldset>
				<label for="termly-last-name"><?php esc_html_e( 'Last Name', 'uk-cookie-consent' ); ?> <span class="required">*</span></label>
				<input type="text" name="termly_business_info[last_name]" id="termly-last-name" class="regular-text" placeholder="<?php esc_attr_e( 'Enter your last name', 'uk-cookie-consent' ); ?>" value="<?php echo esc_attr( $user->last_name ); ?>" required>
			</fieldset>
			<fieldset>
				<label for="termly-email"><?php esc_html_e( 'Email', 'uk-cookie-consent' ); ?> <span class="required">*</span></label>
				<input type="email" name="termly_business_info[email]" id="termly-email" class="regular-text" placeholder="<?php esc_attr_e( 'Enter your email address', 'uk-cookie-consent' ); ?>" value="<?php echo esc_attr( $user->user_email ); ?>" required>
			</fieldset>
			<fieldset>
				<label for="termly-password"><?php esc_html_e( 'Password', 'uk-cookie-consent' ); ?> <span class="required">*</span></label>
				<input type="password" name="termly_business_info[password]" id="termly-password" class="regular-text" placeholder="<?php esc_attr_e( 'Enter your password', 'uk-cookie-consent' ); ?>" required>
			</fieldset>
			<fieldset>
				<label for="termly-confirm-password"><?php esc_html_e( 'Confirm Password', 'uk-cookie-consent' ); ?> <span class="required">*</span></label>
				<input type="password" name="termly_business_info[confirm_password]" id="termly-confirm-password" class="regular-text" placeholder="<?php esc_attr_e( 'Confirm your password', 'uk-cookie-consent' ); ?>" required>
			</fieldset>
			<fieldset>
				<label for="termly-marketing" class="checkbox-container">
					<input type="checkbox" name="termly_business_info[agree_to_marketing]" id="termly-marketing" value="1">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path class="border" d="M3.5 6C3.5 4.61929 4.61929 3.5 6 3.5H18C19.3807 3.5 20.5 4.61929 20.5 6V18C20.5 19.3807 19.3807 20.5 18 20.5H6C4.61929 20.5 3.5 19.3807 3.5 18V6Z" fill="white" stroke="#CED4DA"/>
						<path class="checkmark" fill-rule="evenodd" clip-rule="evenodd" d="M15.4937 9.25628C15.8383 8.91457 16.397 8.91457 16.7416 9.25628C17.0861 9.59799 17.0861 10.152 16.7416 10.4937L11.4474 15.7437C11.1029 16.0854 10.5442 16.0854 10.1996 15.7437L7.25844 12.8271C6.91385 12.4853 6.91385 11.9313 7.25844 11.5896C7.60302 11.2479 8.16169 11.2479 8.50627 11.5896L10.8235 13.8876L15.4937 9.25628Z" fill="#4672FF"/>
					</svg>
					<span><?php esc_html_e( 'I want to receive news, feature updates, discounts, and offers from Termly.', 'uk-cookie-consent' ); ?></span>
				</label>
				<label for="termly-terms" class="checkbox-container">
					<input type="checkbox" name="termly_business_info[agree_to_terms]" id="termly-terms" value="1">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path class="border" d="M3.5 6C3.5 4.61929 4.61929 3.5 6 3.5H18C19.3807 3.5 20.5 4.61929 20.5 6V18C20.5 19.3807 19.3807 20.5 18 20.5H6C4.61929 20.5 3.5 19.3807 3.5 18V6Z" fill="white" stroke="#CED4DA"/>
						<path class="checkmark" fill-rule="evenodd" clip-rule="evenodd" d="M15.4937 9.25628C15.8383 8.91457 16.397 8.91457 16.7416 9.25628C17.0861 9.59799 17.0861 10.152 16.7416 10.4937L11.4474 15.7437C11.1029 16.0854 10.5442 16.0854 10.1996 15.7437L7.25844 12.8271C6.91385 12.4853 6.91385 11.9313 7.25844 11.5896C7.60302 11.2479 8.16169 11.2479 8.50627 11.5896L10.8235 13.8876L15.4937 9.25628Z" fill="#4672FF"/>
					</svg>
					<span><?php echo wp_kses_post( __( 'I acknowledge that I have read and agree to the <a href="https://termly.io/our-terms-of-use/" target="_blank">Terms and Conditions</a> and <a href="https://termly.io/our-privacy-policy/" target="_blank">Privacy Policy</a>.', 'uk-cookie-consent' ) ); ?></span>
				</label>
			</fieldset>
		</div>
		<?php
	}

	public static function add_temporary_auth( $headers = [] ) {

		$temporary_api_key = $_REQUEST['termly_api_key'];
		return [
			'Accept'        => 'application/vnd.wordpress-v1+json',
			'Authorization' => sprintf( 'Bearer %s', $temporary_api_key ),
		];

	}

	public static function remove_auth( $headers = [] ) {

		if ( isset( $headers['Authorization'] ) ) {
			unset( $headers['Authorization'] );
		}
		return $headers;

	}

	public static function maybe_json_body( $body, $endpoint, $verb, $url ) {

		if ( in_array( $verb, [ 'PUT', 'POST' ], true ) ) {

			$body = wp_json_encode( $body );

		}

		return $body;

	}

	public static function maybe_json_header( $headers, $endpoint, $body, $verb, $url ) {

		if ( in_array( $verb, [ 'PUT', 'POST' ], true ) ) {

			$headers['Content-Type'] = 'application/json';

		}

		return $headers;
	}

	public static function call( $verb = 'GET', $endpoint = '', $body = [] ) {

		$verb     = apply_filters( 'termly_api_verb', $verb, $endpoint, $body );
		$url      = apply_filters( 'termly_api_url', sprintf( '%s/%s', TERMLY_API_BASE, $endpoint ), $endpoint, $body, $verb );
		$headers  = apply_filters(
			'termly_api_headers',
			[
				'Accept'        => 'application/vnd.wordpress-v1+json',
				'Authorization' => sprintf( 'Bearer %s', General_Settings_Model::get_authorization() ),
			],
			$endpoint,
			$body,
			$verb,
			$url
		);
		$body     = apply_filters( 'termly_api_args', $body, $endpoint, $verb, $url );
		$response = wp_remote_request(
			$url,
			array(
				'method'  => $verb,
				'headers' => $headers,
				'body'    => $body,
			)
		);

		return $response;

	}

}
Termly_API_Controller::hooks();
