<?php
/**
 * This view is shown when a new Cookie Scan is triggered.
 *
 * @package termly
 */

?>
<div class="notice notice-error is-dismissable">
	<p>
		<?php esc_html_e( 'A new Cookie Scan could not be initiated because of an error.', 'uk-cookie-consent' ); ?>
		<?php

			// $error_msg comes from Site_Scan_Controller::new_scan_notice()
			echo esc_html( $error_msg );

		?>
	</p>
</div>
