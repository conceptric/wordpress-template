<?php
/**
 * This view is shown on the site scan settings page for free users.
 *
 * @package termly
 */

?>
<div class="notice notice-error">
	<p>
		<strong><?php esc_html_e( 'Your current plan only provides quarterly cookie scans.', 'uk-cookie-consent' ); ?></strong>
		<?php esc_html_e( 'To keep your banner up to date and ensure compliance, we recommend a minimum of weekly scans.', 'uk-cookie-consent' ); ?>
		<a href="<?php echo esc_url( \termly\Urls::get_plans_url( 'site-scan' ) ); ?>" target="_blank"><?php esc_html_e( 'View Plans', 'uk-cookie-consent' ); ?></a>
	</p>
</div>
