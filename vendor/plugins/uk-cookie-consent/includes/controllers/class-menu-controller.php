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
class Menu_Controller {

	public static function hooks() {

		// Register Submenus by extending this class.
		add_action( 'admin_menu', [ get_called_class(), 'menu' ] );

	}

}
