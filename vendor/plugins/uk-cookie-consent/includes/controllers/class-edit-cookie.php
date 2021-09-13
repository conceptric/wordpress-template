<?php
/**
 * This class handles the edit/add cookie page and functionality
 *
 * @package termly
 */

namespace termly;

// If the Termly API Controller has not been included
if ( ! class_exists( 'Termly_API_Controller' ) ) {
	require_once TERMLY_CONTROLLERS . 'class-termly-api-controller.php';
}

/**
 * This class handles the routing for the dashboard experience.
 */
class Edit_Cookie {

	public static $name_prefix = 'termly-edit-cookie-';

	/**
	 * hooks    Hooks into WordPress for this class
	 *
	 * @return void
	 */
	public static function hooks() {

		add_action( 'admin_menu', [ __CLASS__, 'edit_page' ] );
		add_filter( 'parent_file', [ __CLASS__, 'highlight' ], 10, 1 );

	}

	/**
	 * edit_page    Adds submenu page for edit page with no parent
	 *
	 * @return void
	 */
	public static function edit_page() {

		add_submenu_page(
			null,
			__( 'Edit Cookie', 'uk-cookie-consent' ),
			'',
			'manage_options',
			'termly-edit-cookie',
			[ __CLASS__, 'edit_page_view' ]
		);

	}

	/**
	 * edit_page_view    The view for the edit page.
	 * Also triggers edit/add functionality if action is set
	 *
	 * @return void
	 */
	public static function edit_page_view() {

		// Handle editing or adding a cookie if there is an action set in the request
		if ( isset( $_REQUEST['action'] ) ) {
			$status = self::handle_crud();
		}

		// Whether this is editing or adding new
		// Cookie ID is only set if we are editing
		$editing = isset( $_GET['cookie_id'] );

		// Name prefix for ids
		$name_prefix = self::$name_prefix;

		// If a cookie has been added, an additional
		// array value is added which is the cookie id
		if ( isset( $status ) && 3 === count( $status ) ) {
			$cookie_id = $status[2];
			$editing = true;
		}

		// By default, cookie is set to false
		$cookie = false;

		if ( $editing ) {

			if ( isset( $_GET['cookie_id'] ) ) {
				$cookie_id = intval( $_GET['cookie_id'] );
			}

			$scan_results = Termly_API_Controller::call( 'GET', 'report', [ 'with_cookies' => 'true' ] );

			if ( 200 !== wp_remote_retrieve_response_code( $scan_results ) || is_wp_error( $scan_results ) ) {
				return;
			}

			$results = json_decode( wp_remote_retrieve_body( $scan_results ) );

			if ( ! property_exists( $results, 'cookies' ) ) {
				return;
			}

			$results = $results->cookies;

			foreach ( $results as $result ) {
				if ( $result->id === $cookie_id ) {
					$cookie = $result;
				}
			}

			if ( ! $cookie ) {
				return;
			}

		}

		require_once TERMLY_VIEWS . 'edit-cookie.php';

	}

	/**
	 * handle_crud    Handle editing and adding a cookie
	 *
	 * @return array [0] is success/error [1] is message [2] (optional) is cookie id
	 */
	public static function handle_crud() {

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'termly_cookie_nonce' ) ) {
			die();
		}

		$action = sanitize_text_field( $_REQUEST['action'] );

		// Check for required fields
		$required_fields = [
			'name', 'category', 'domain',
		];

		foreach ( $required_fields as $field ) {
			if ( ! isset( $_REQUEST[ $field ] ) || '' === $_REQUEST[ $field ] ) {
				return [
					'error',
					__( 'Please fill out all required fields', 'uk-cookie-consent' ),
				];
			}
		}

		// Delete the transient which stores cookie list
		delete_transient( 'termly-site-scan-results' );

		// Arguments for edit/add cookie
		$arg_keys = [
			'name', 'category', 'expire', 'tracker_type',
			'country', 'domain', 'service', 'service_policy_link',
			'source', 'value', 'en_us',
		];

		$args = [];

		// Add arguments of they are set
		foreach ( $arg_keys as $key ) {
			if ( ! isset( $_REQUEST[ $key ] ) ) {
				continue;
			}
			$args[ $key ] = sanitize_text_field( $_REQUEST[ $key ] );
		}

		// Unslash the args
		$args = wp_unslash( $args );

		// Edit a cookie
		if ( 'edit' === $action ) {
			$status = self::edit_cookie( $args );
			return $status;
		}

		// Add a cookie
		if ( 'add' === $action ) {
			$status = self::add_cookie( $args );
			return $status;
		}

		return [];

	}

	/**
	 * edit_cookie
	 *
	 * @param  array $args Post arguments
	 *
	 * @return array [0] is success/error [1] is message [2] is cookie id
	 */
	public static function edit_cookie( $args ) {

		// These fields cannot be edited
		$non_editable_fields = [ 'name', 'expire', 'tracker_type', 'domain', ];

		// Loop through and remove non editable fields if they are set
		foreach ( $non_editable_fields as $field ) {
			if ( ! isset( $args[ $field ] ) ) {
				continue;
			}
			unset( $args[ $field ] );
		}

		// Get the cookie ID
		$cookie_id = intval( $_REQUEST['cookie_id'] );

		// PUT request to the API
		$response = Termly_API_Controller::call( 'PUT', 'cookies/' . $cookie_id, $args );

		if ( 200 === wp_remote_retrieve_response_code( $response ) && ! is_wp_error( $response ) ) {
			// Return success
			return [
				'success',
				__( 'Cookie updated', 'uk-cookie-consent' ),
				$cookie_id,
			];
		}

		// Return failure
		return [
			'error',
			__( 'Cookie update failed', 'uk-cookie-consent' ),
			$cookie_id,
		];

	}

	/**
	 * add_cookie
	 *
	 * @param  array $args Post arguments
	 *
	 * @return array [0] is success/error [1] is message [2] is cookie id
	 */
	public static function add_cookie( $args ) {

		// POST request to the API
		$response = Termly_API_Controller::call( 'POST', 'cookies', $args );

		if ( 201 === wp_remote_retrieve_response_code( $response ) && ! is_wp_error( $response ) ) {

			$body = json_decode( $response['body'] );
			$cookie_id = $body->id;

			// Success message
			$success = [
				'success',
				__( 'Cookie added', 'uk-cookie-consent' ),
			];

			// If not adding another, set cookie id to go to edit screen
			if ( 'add_another' !== $_REQUEST['submit'] ) {
				$success[] = intval( $cookie_id );
			}

			return $success;
		}

		// Return failure
		return [
			'error',
			__( 'Adding cookie failed', 'uk-cookie-consent' ),
		];

	}

	/**
	 * Highlight the "Cookie Management" submenu page when on the edit cookie page
	 *
	 * @param  string $parent
	 *
	 * @return string
	 */
	public static function highlight( $parent ) {
		global $plugin_page;
		if ( 'termly-edit-cookie' === $plugin_page ) {
			$plugin_page = 'cookie-management';
		}
		return $parent;
	}

}

Edit_Cookie::hooks();
