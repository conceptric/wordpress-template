<?php global $current_screen; ?>
<div class="termly-upgrade-sidebar">

	<h2><?php esc_html_e( 'Upgrade to Termly Pro', 'uk-cookie-consent' ); ?></h2>

	<ul>
		<li><?php esc_html_e( '30 Day Money Back Guarantee', 'uk-cookie-consent' ); ?></li>
		<li><?php esc_html_e( 'Multi-Language Support', 'uk-cookie-consent' ); ?></li>
		<li><?php esc_html_e( 'Scheduled Cookie Scans', 'uk-cookie-consent' ); ?></li>
		<li><?php esc_html_e( 'Scroll-to-Consent Functionality', 'uk-cookie-consent' ); ?></li>
		<li><?php esc_html_e( 'More Customization and Automation Options', 'uk-cookie-consent' ); ?></li>
	</ul>

	<div class="upgrade-plan-links">

		<a href="<?php echo esc_attr( termly\Urls::get_plans_url( str_replace( [ 'termly_page_', 'toplevel_page_', 'admin_page_termly-' ], '', $current_screen->id ) ) ); ?>" class="termly-button green" target="_blank">
			<?php esc_html_e( 'Upgrade to Pro', 'uk-cookie-consent' ); ?>
		</a>

		<a href="<?php echo esc_attr( termly\Urls::get_compare_plans_url( str_replace( [ 'termly_page_', 'toplevel_page_', 'admin_page_termly-' ], '', $current_screen->id ) ) ); ?>" target="_blank" class="compare-plans">
			<?php esc_html_e( 'Compare Plans', 'uk-cookie-consent' ); ?>
		</a>

	</div>

</div>
