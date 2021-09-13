<?php
/**
 * This file contains the General Settings model class.
 *
 * @package termly
 */

namespace termly;

/**
 * This class handles the routing for the dashboard experience.
 */
class General_Settings_Model {

	public static function get_authorization() {

		$api_key = get_option( 'termly_api_key' );
		if ( false !== $api_key ) {
			return $api_key;
		} else {
			return 'NO API KEY SET';
		}

	}

}
