<?php
/**
 * Handles the display of the Main Termly admin page.
 *
 * @package termly
 */

?>
<div class="wrap termly termly-main-menu">

	<div class="termly-content-wrapper">

		<div class="termly-content-cell termly-left-column">
			<div class="termly-content-header">
				<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 256 256" xml:space="preserve">
					<title><?php esc_html_e( 'Termly', 'uk-cookie-consent' ); ?></title>
					<path fill="#547eed" d="M151.22,104.05l34.83-34.11H84.46V204.2l101.6-100.15H151.22z M128,255C57.61,255,1,198.39,1,128S57.61,1,128,1 s127,56.61,127,127S198.39,255,128,255z"/>
				</svg>
				<h1><?php esc_html_e( 'Termly', 'uk-cookie-consent' ); ?></h1>
				<div class="termly-dashboard-link-container">
					<a href="<?php echo esc_attr( termly\Urls::get_dashboard_link() ); ?>" target="_blank">
						<span><?php esc_html_e( 'Go to Termly Dashboard', 'uk-cookie-consent' ); ?></span>
						<svg width="8" height="11" viewBox="0 0 8 11" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M2.08997 10.91L7.08997 5.91L2.08997 0.910004L0.909973 2.09L4.74997 5.91L0.909973 9.73L2.08997 10.91Z" fill="#4672FF"/>
						</svg>
					</a>
				</div>
			</div>
			<?php
			if ( isset( $_GET['settings-updated'] ) ) {

				settings_errors( 'termly_api_key' );

			}
			?>
			<hr class="wp-header-end">

			<div class="content termly-existing-user">
				<h2 class="title"><?php esc_html_e( 'Welcome to Termly', 'uk-cookie-consent' ); ?></h2>
				<p><?php esc_html_e( 'You have successfully connected your Termly account. You can go to your Termly Dashboard at any time to manage your account and access the full suite of legal and data compliance tools.', 'uk-cookie-consent' ); ?></p>
				<form action='options.php' method='post'>
				<?php
					settings_fields( 'termly_api_key' );
					do_settings_sections( 'termly_api_key' );
				?>
				</form>
			</div>

		</div>

		<?php if ( termly\Account_API_Controller::is_free() ) { ?>
		<div class="termly-content-cell termly-right-column">

			<?php include TERMLY_VIEWS . 'upgrade-notice-sidebar.php'; ?>

		</div>
		<?php } ?>

	</div>

</div>
