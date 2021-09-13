<?php
/**
 * This file contains the Policies controller class.
 *
 * @package termly
 */

namespace termly;

/**
 * This class handles the routing for the dashboard experience.
 */
class Policies_Controller extends Menu_Controller {

	public static function hooks() {

		add_action( 'admin_menu', [ __CLASS__, 'menu' ] );

	}

	public static function menu() {

		add_submenu_page(
			'termly',
			__( 'Policies', 'uk-cookie-consent' ),
			__( 'Policies', 'uk-cookie-consent' ),
			'manage_options',
			'termly-policies',
			[ __CLASS__, 'menu_page' ]
		);

	}

	public static function menu_page() {

		require_once TERMLY_VIEWS . 'policies.php';

	}

}
Policies_Controller::hooks();
