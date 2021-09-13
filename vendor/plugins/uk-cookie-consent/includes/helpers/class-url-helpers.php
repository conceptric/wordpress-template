<?php

namespace termly;

class Urls {

	public static function get_disconnect_url() {

		return wp_nonce_url(
			add_query_arg(
				[
					'page'   => 'termly',
					'action' => 'disconnect',
				],
				admin_url( 'admin.php' )
			),
			'reset-termly'
		);

	}

	public static function get_plans_url( $campaign = 'site-scan' ) {

		return add_query_arg(
			[
				'utm_source'   => 'wordpress',
				'utm_medium'   => 'notice',
				'utm_campaign' => $campaign,
			],
			'https://app.termly.io/user/products'
		);

	}

	public static function get_compare_plans_url( $campaign = 'site-scan' ) {

		return add_query_arg(
			[
				'utm_source'   => 'wordpress',
				'utm_medium'   => 'notice',
				'utm_campaign' => $campaign,
			],
			'https://app.termly.io/user/products'
		);

	}

	public static function get_new_cookie_url() {

		return add_query_arg(
			[
				'page'   => 'termly-edit-cookie',
			],
			admin_url( 'admin.php' )
		);

	}

	public static function get_sign_up_url() {

		return add_query_arg(
			[
				'page'   => 'termly',
				'action' => 'sign-up',
			],
			admin_url( 'admin.php' )
		);

	}

	public static function get_scan_url() {

		return add_query_arg(
			[
				'page' => 'site-scan',
			],
			admin_url( 'admin.php' )
		);

	}

	public static function get_cookie_management_url() {

		return add_query_arg(
			[
				'page' => 'cookie-management',
			],
			admin_url( 'admin.php' )
		);

	}

	/**
	 * get_edit_cookie_link
	 *
	 * @param int $item_id The current cookie id.
	 *
	 * @return void
	 */
	public static function get_edit_cookie_link( $item_id ) {
		return add_query_arg(
			[
				'page'      => 'termly-edit-cookie',
				'cookie_id' => $item_id,
			],
			admin_url( 'admin.php' )
		);
	}

	public static function get_delete_cookie_link( $item_id, $args ) {

		return add_query_arg(
			[
				'page'             => 'cookie-management',
				'action'           => 'delete',
				'cookie'           => $item_id,
				'_wpnonce'         => wp_create_nonce( 'bulk-' . $args['plural'] ),
				'_wp_http_referer' => remove_query_arg( [ 'action', 'action2', 'cookie', ], $_SERVER['REQUEST_URI'] ),
			],
			admin_url( 'admin.php' )
		);

	}

	/**
	 * Get the customize banner link specific to site ID.
	 *
	 * @return string
	 */
	public static function get_dashboard_link() {

		return 'https://app.termly.io/dashboard/';

	}

	/**
	 * Get the customize banner link specific to site ID.
	 *
	 * @return string
	 */
	public static function get_customize_banner_link() {
		$website_id = self::get_website_id();
		return sprintf(
			'https://app.termly.io/dashboard/website/%s/banner-settings',
			rawurlencode( $website_id )
		);
	}

	/**
	 * Get the policies cookie policy link specific to site ID.
	 *
	 * @return string
	 */
	public static function get_policies_cookie_policy_link() {
		$website_id = self::get_website_id();
		return sprintf(
			'https://app.termly.io/dashboard/website/%s/cookie-policy',
			rawurlencode( $website_id )
		);
	}

	/**
	 * Get the policies privacy policy link specific to site ID.
	 *
	 * @return string
	 */
	public static function get_policies_privacy_policy_link() {
		$website_id = self::get_website_id();
		return sprintf(
			'https://app.termly.io/dashboard/website/%s/privacy-policy',
			rawurlencode( $website_id )
		);
	}

	/**
	 * Get the policies terms and conditions link specific to site ID.
	 *
	 * @return string
	 */
	public static function get_policies_terms_and_conditions_link() {
		$website_id = self::get_website_id();
		return sprintf(
			'https://app.termly.io/dashboard/website/%s/terms-of-service',
			rawurlencode( $website_id )
		);
	}

	/**
	 * Get the policies disclaimer link specific to site ID.
	 *
	 * @return string
	 */
	public static function get_policies_disclaimer_link() {
		$website_id = self::get_website_id();
		return sprintf(
			'https://app.termly.io/dashboard/website/%s/disclaimer',
			rawurlencode( $website_id )
		);
	}

	/**
	 * Get the policies return policy link specific to site ID.
	 *
	 * @return string
	 */
	public static function get_policies_return_policy_link() {
		$website_id = self::get_website_id();
		return sprintf(
			'https://app.termly.io/dashboard/website/%s/refund-policy',
			rawurlencode( $website_id )
		);
	}

	/**
	 * Get website ID.
	 *
	 * @return integer
	 */
	public static function get_website_id() {

		$website = get_option( 'termly_website', (object) [ 'uuid' => 0 ] );
		return $website->uuid;
	}

}
