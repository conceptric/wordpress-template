<?php
/**
 * Handles the display of the Banner Settings admin page.
 *
 * @package termly
 */
?>
<div class="wrap termly termly-banner-settings">

	<div class="termly-content-wrapper">

		<div class="termly-content-cell termly-left-column">
			<div class="termly-content-header">
				<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 256 256" xml:space="preserve">
					<title><?php esc_html_e( 'Termly', 'uk-cookie-consent' ); ?></title>
					<path fill="#547eed" d="M151.22,104.05l34.83-34.11H84.46V204.2l101.6-100.15H151.22z M128,255C57.61,255,1,198.39,1,128S57.61,1,128,1 s127,56.61,127,127S198.39,255,128,255z"/>
				</svg>
				<h1><?php esc_html_e( 'Banner Settings', 'uk-cookie-consent' ); ?></h1>
				<div class="termly-dashboard-link-container">
					<a href="<?php echo esc_attr( termly\Urls::get_dashboard_link() ); ?>" target="_blank">
						<span><?php esc_html_e( 'Go to Termly Dashboard', 'uk-cookie-consent' ); ?></span>
						<svg width="8" height="11" viewBox="0 0 8 11" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M2.08997 10.91L7.08997 5.91L2.08997 0.910004L0.909973 2.09L4.74997 5.91L0.909973 9.73L2.08997 10.91Z" fill="#4672FF"/>
						</svg>
					</a>
				</div>
			</div>

			<?php settings_errors( 'termly_banner_settings' ); ?>
			<hr class="wp-header-end">

			<div class="content banner-settings">
				<form action='options.php' method='post'>
					<!-- Consent Banner -->
					<div class="consent-banner-heading">
						<h2 class="title"><?php esc_html_e( 'Consent Banner', 'uk-cookie-consent' ); ?></h2>
						<p>
							<a href="<?php echo esc_url( termly\Urls::get_customize_banner_link() ); ?>" target="_blank">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M11.7778 6H9C7.34315 6 6 7.34315 6 9V16C6 17.6569 7.34315 19 9 19H16C17.6569 19 19 17.6569 19 16V13.3924" stroke="white" stroke-width="2"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M18 6.99997H14V4.99997H20V11H18V6.99997Z" fill="white"/>
									<path d="M18.8147 6.18585L13.0481 11.9524" stroke="white" stroke-width="2"/>
								</svg>
								<span><?php esc_html_e( 'Customize Banner', 'uk-cookie-consent' ); ?></span>
							</a>
						</p>
					</div>
					<p><?php esc_html_e( 'The Consent Banner lets you request consent for for up to 10,000 monthly unique visitors. It automatically detects and adjusts to support a visitors regional data regulations and includes a preference center where they can manage their consent preferences.', 'uk-cookie-consent' ); ?></p>
					<p><label class="checkbox-container">
						<?php $display_banner = 'yes' === get_option( 'termly_display_banner', 'no' ); ?>
						<input
							value="yes"
							type="checkbox"
							name="termly_banner_settings[display_banner]"
							<?php checked( $display_banner ); ?>
						>
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path class="border" d="M3.5 6C3.5 4.61929 4.61929 3.5 6 3.5H18C19.3807 3.5 20.5 4.61929 20.5 6V18C20.5 19.3807 19.3807 20.5 18 20.5H6C4.61929 20.5 3.5 19.3807 3.5 18V6Z" fill="white" stroke="#CED4DA"/>
							<path class="checkmark" fill-rule="evenodd" clip-rule="evenodd" d="M15.4937 9.25628C15.8383 8.91457 16.397 8.91457 16.7416 9.25628C17.0861 9.59799 17.0861 10.152 16.7416 10.4937L11.4474 15.7437C11.1029 16.0854 10.5442 16.0854 10.1996 15.7437L7.25844 12.8271C6.91385 12.4853 6.91385 11.9313 7.25844 11.5896C7.60302 11.2479 8.16169 11.2479 8.50627 11.5896L10.8235 13.8876L15.4937 9.25628Z" fill="#4672FF"/>
						</svg>
						<span><?php esc_html_e( 'Enable Consent Banner', 'uk-cookie-consent' ); ?></span>
					</label></p>
					<!-- Auto Blocker -->
					<h2 class="title"><?php esc_html_e( 'Auto Blocker', 'uk-cookie-consent' ); ?></h2>
					<p><?php
					printf(
						wp_kses(
							__(
								'<a href="%1$s" target="_blank">Auto Blocker</a> will automatically prevent scripts from running on your site until a visitor consents to their delivery. If you do not use Auto Blocker, make sure to set up <a href="%2$s" target="_blank">manual blocking</a> to remain compliant.',
								'uk-cookie-consent'
							),
							[ 'a' => [ 'href' => [], 'target' => [] ] ]
						),
						'https://help.termly.io/support/solutions/articles/69000108867-how-does-auto-blocker-work-',
						'https://help.termly.io/support/solutions/articles/69000108889-blocking-javascript-third-party-cookies-manually'
					);
					?></p>
					<p><label class="checkbox-container">
						<?php $auto_block = 'on' === get_option( 'termly_display_auto_blocker', 'off' ); ?>
						<input
							value="on"
							type="checkbox"
							name="termly_banner_settings[auto_block]"
							<?php checked( $auto_block ); ?>
						>
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path class="border" d="M3.5 6C3.5 4.61929 4.61929 3.5 6 3.5H18C19.3807 3.5 20.5 4.61929 20.5 6V18C20.5 19.3807 19.3807 20.5 18 20.5H6C4.61929 20.5 3.5 19.3807 3.5 18V6Z" fill="white" stroke="#CED4DA"/>
							<path class="checkmark" fill-rule="evenodd" clip-rule="evenodd" d="M15.4937 9.25628C15.8383 8.91457 16.397 8.91457 16.7416 9.25628C17.0861 9.59799 17.0861 10.152 16.7416 10.4937L11.4474 15.7437C11.1029 16.0854 10.5442 16.0854 10.1996 15.7437L7.25844 12.8271C6.91385 12.4853 6.91385 11.9313 7.25844 11.5896C7.60302 11.2479 8.16169 11.2479 8.50627 11.5896L10.8235 13.8876L15.4937 9.25628Z" fill="#4672FF"/>
						</svg>
						<span><?php esc_html_e( 'Enable Auto Blocker', 'uk-cookie-consent' ); ?></span>
					</label></p>
					<!-- Preference Center Button -->
					<h2 class="title"><?php esc_html_e( 'Preference Center Button', 'uk-cookie-consent' ); ?></h2>
					<p><?php esc_html_e( 'Your site visitors must be able to change their cookie preferences at any time. Add the code below to your website to add a button that will open your Cookie Preference Center, where your visitors will be able to easily change their consent settings.', 'uk-cookie-consent' ); ?></p>
					<div class="preference-center-snippet">
						<label>
							<span class="screen-reader-text"><?php esc_html_e( 'HTML snippet for the preference center button' ); ?></span>
							<textarea id="termly-preference-center-snippet" readonly rows="1"><?php
							echo esc_html( trim( get_option( 'termly_cookie_preference_button', '' ) ) );
							?></textarea>
						</label>
						<button type="button" id="termly-copy-preference-center-snippet" class="button">
						<?php
						esc_html_e( 'Copy to clipboard', 'uk-cookie-consent' );
						?>
						</button>
					</div>
					<?php
					settings_fields( 'termly_banner_settings' );
					do_settings_sections( 'termly_banner_settings' );
					submit_button();
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
