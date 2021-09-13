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
				<h1><?php esc_html_e( 'Policies', 'uk-cookie-consent' ); ?></h1>
				<div class="termly-dashboard-link-container">
					<a href="<?php echo esc_attr( termly\Urls::get_dashboard_link() ); ?>" target="_blank">
						<span><?php esc_html_e( 'Go to Termly Dashboard', 'uk-cookie-consent' ); ?></span>
						<svg width="8" height="11" viewBox="0 0 8 11" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M2.08997 10.91L7.08997 5.91L2.08997 0.910004L0.909973 2.09L4.74997 5.91L0.909973 9.73L2.08997 10.91Z" fill="#4672FF"/>
						</svg>
					</a>
				</div>
			</div>

			<hr class="wp-header-end">

			<div class="content policies">
				<p><?php esc_html_e( 'Generate free attorney-crafted documents and policies that can easily be revised over time as regulation and your business changes. You can customize the look and feel to match your brand, then immediately embed them on your site.', 'uk-cookie-consent' ); ?></p>
				<div class="policy-block">
					<div class="policy-left">
						<div class="policy-header-container">
							<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect x="3" y="20" width="17" height="17" rx="2" fill="#00C999"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M22.1231 2C23.4606 2 24.76 2.16162 26.0027 2.46626C25.4676 3.23337 25.154 4.16436 25.154 5.16802C25.154 6.70917 25.8933 8.07898 27.0392 8.94722C26.8517 9.50156 26.7502 10.095 26.7502 10.712C26.7502 13.7738 29.2514 16.256 32.3368 16.256C32.9015 16.256 33.4467 16.1728 33.9605 16.0182C34.1898 18.3104 35.826 20.1919 38 20.8004C36.6656 28.3017 30.0649 34 22.1231 34C13.2185 34 6 26.8366 6 18C6 9.16344 13.2185 2 22.1231 2ZM18.7694 14.6719C19.6509 14.6719 20.3655 13.9627 20.3655 13.0879C20.3655 12.2131 19.6509 11.5039 18.7694 11.5039C17.8878 11.5039 17.1732 12.2131 17.1732 13.0879C17.1732 13.9627 17.8878 14.6719 18.7694 14.6719ZM26.7502 24.1758C26.7502 25.0506 26.0356 25.7598 25.154 25.7598C24.2725 25.7598 23.5578 25.0506 23.5578 24.1758C23.5578 23.301 24.2725 22.5918 25.154 22.5918C26.0356 22.5918 26.7502 23.301 26.7502 24.1758Z" fill="#4672FF"/>
								<circle cx="31.5" cy="6.5" r="1.5" fill="#B6C5FF"/>
								<ellipse cx="15.5845" cy="23.5918" rx="1.59618" ry="1.584" fill="white"/>
							</svg>
							<h2 class="title"><?php esc_html_e( 'Cookie Policy', 'uk-cookie-consent' ); ?></h2>
						</div>
						<p><?php esc_html_e( 'Once your cookies have been categorized, our tool will automatically create a cookie policy perfectly tailored to your business.', 'uk-cookie-consent' ); ?></p>
					</div>
					<p class="policy-right">
						<a href="<?php echo esc_attr( termly\Urls::get_policies_cookie_policy_link() ); ?>" target="_blank">
							<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M11.7778 6.5H9C7.34315 6.5 6 7.84315 6 9.5V16.5C6 18.1569 7.34315 19.5 9 19.5H16C17.6569 19.5 19 18.1569 19 16.5V13.8924" stroke="white" stroke-width="2"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M18 7.49997H14V5.49997H20V11.5H18V7.49997Z" fill="white"/>
								<path d="M18.8149 6.68585L13.0483 12.4524" stroke="white" stroke-width="2"/>
							</svg>
							<span><?php esc_html_e( 'Manage', 'uk-cookie-consent' ); ?></span>
						</a>
					</p>
				</div>
				<div class="policy-block">
					<div class="policy-left">
						<div class="policy-header-container">
							<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M16.3074 2.39987L31.0133 8.98811V18.792C31.0133 21.7724 30.3728 24.6482 29.0918 27.4195C27.8107 30.1384 26.0722 32.4652 23.8761 34.3999C21.6016 36.3868 19.0787 37.7201 16.3074 38.3999C13.5362 37.7201 11.0133 36.3868 8.73882 34.3999C6.54273 32.4652 4.80418 30.1384 3.52313 27.4195C2.24208 24.6482 1.60156 21.7724 1.60156 18.792V8.98811L16.3074 2.39987Z" fill="#4672FF"/>
								<path d="M22.0157 30.9403C23.6933 29.4603 25.0214 27.6803 26 25.6003L5 19.0003C5 21.2803 5.4893 23.4803 6.4679 25.6003C7.44651 27.6803 8.7746 29.4603 10.4522 30.9403C12.1897 32.4603 14.117 33.4803 16.234 34.0003C18.3509 33.4803 20.2782 32.4603 22.0157 30.9403Z" fill="#B6C5FF"/>
								<ellipse cx="28.6002" cy="11.5995" rx="9.6" ry="9.6" fill="#00C999"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M27.5218 16.0005L34.0005 9.48986L32.3935 8.00052L27.5218 12.9966L24.6074 10.2703L23.0005 11.7597L27.5218 16.0005Z" fill="white"/>
							</svg>
							<h2 class="title"><?php esc_html_e( 'Privacy Policy', 'uk-cookie-consent' ); ?></h2>
						</div>
						<p><?php esc_html_e( 'Fit to meet the requirements of privacy laws and third-party platforms, including: GDPR, CCPA, CalOPPA, Google Analytics, AdSense, ecommerce laws.', 'uk-cookie-consent' ); ?></p>
					</div>
					<p class="policy-right">
						<a href="<?php echo esc_attr( termly\Urls::get_policies_privacy_policy_link() ); ?>" target="_blank">
							<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M11.7778 6.5H9C7.34315 6.5 6 7.84315 6 9.5V16.5C6 18.1569 7.34315 19.5 9 19.5H16C17.6569 19.5 19 18.1569 19 16.5V13.8924" stroke="white" stroke-width="2"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M18 7.49997H14V5.49997H20V11.5H18V7.49997Z" fill="white"/>
								<path d="M18.8149 6.68585L13.0483 12.4524" stroke="white" stroke-width="2"/>
							</svg>
							<span><?php esc_html_e( 'Manage', 'uk-cookie-consent' ); ?></span>
						</a>
					</p>
				</div>
				<div class="policy-block">
					<div class="policy-left">
						<div class="policy-header-container">
							<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect x="4" width="24" height="30" rx="2" fill="#00C999"/>
								<rect x="13" y="10" width="24" height="30" rx="2" fill="#4672FF"/>
								<path d="M17 17.8516H24.4724" stroke="white" stroke-width="3"/>
								<path d="M17 26.2297H33" stroke="#B5C4FF" stroke-width="3"/>
							</svg>
							<h2 class="title"><?php esc_html_e( 'Terms and Conditions', 'uk-cookie-consent' ); ?></h2>
						</div>
						<p><?php esc_html_e( 'Generate terms and conditions designed for your blog, website, app, SaaS, or ecommerce site. Establish guidelines and rights for your platform. ', 'uk-cookie-consent' ); ?></p>
					</div>
					<p class="policy-right">
						<a href="<?php echo esc_attr( termly\Urls::get_policies_terms_and_conditions_link() ); ?>" target="_blank">
							<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M11.7778 6.5H9C7.34315 6.5 6 7.84315 6 9.5V16.5C6 18.1569 7.34315 19.5 9 19.5H16C17.6569 19.5 19 18.1569 19 16.5V13.8924" stroke="white" stroke-width="2"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M18 7.49997H14V5.49997H20V11.5H18V7.49997Z" fill="white"/>
								<path d="M18.8149 6.68585L13.0483 12.4524" stroke="white" stroke-width="2"/>
							</svg>
							<span><?php esc_html_e( 'Manage', 'uk-cookie-consent' ); ?></span>
						</a>
					</p>
				</div>
				<div class="policy-block">
					<div class="policy-left">
						<div class="policy-header-container">
							<svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
								<circle cx="29" cy="21" r="12" fill="#00C999"/>
								<path d="M19.1285 6.54945C19.5108 5.8698 20.4893 5.86981 20.8716 6.54945L37.1618 35.5097C37.5368 36.1763 37.055 37 36.2902 37H3.70961C2.94478 37 2.46307 36.1763 2.83805 35.5097L19.1285 6.54945Z" fill="#4672FF"/>
								<path d="M25 22L30.1934 31.5211C30.5568 32.1875 30.0745 33 29.3155 33H9L25 22Z" fill="#B5C4FF"/>
								<rect x="19" y="16" width="2" height="11" fill="white"/>
								<rect x="19" y="29" width="2" height="2" fill="white"/>
							</svg>
							<h2 class="title"><?php esc_html_e( 'Disclaimer', 'uk-cookie-consent' ); ?></h2>
						</div>
						<p><?php esc_html_e( 'Whatever disclosure policy you need — from a medical or affiliate disclaimer to a basic blog disclaimer — our generator has you covered.', 'uk-cookie-consent' ); ?></p>
					</div>
					<p class="policy-right">
						<a href="<?php echo esc_attr( termly\Urls::get_policies_disclaimer_link() ); ?>" target="_blank">
							<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M11.7778 6.5H9C7.34315 6.5 6 7.84315 6 9.5V16.5C6 18.1569 7.34315 19.5 9 19.5H16C17.6569 19.5 19 18.1569 19 16.5V13.8924" stroke="white" stroke-width="2"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M18 7.49997H14V5.49997H20V11.5H18V7.49997Z" fill="white"/>
								<path d="M18.8149 6.68585L13.0483 12.4524" stroke="white" stroke-width="2"/>
							</svg>
							<span><?php esc_html_e( 'Manage', 'uk-cookie-consent' ); ?></span>
						</a>
					</p>
				</div>
				<div class="policy-block">
					<div class="policy-left">
						<div class="policy-header-container">
							<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M2 9H35C36.1046 9 37 9.89543 37 11V36C37 37.1046 36.1046 38 35 38H2V9Z" fill="#4672FF"/>
								<path d="M20 23C20 20.2386 22.2386 18 25 18H37.6471C37.842 18 38 18.158 38 18.3529V27.6471C38 27.842 37.842 28 37.6471 28H25C22.2386 28 20 25.7614 20 23Z" fill="#B5C4FF"/>
								<circle cx="26" cy="23" r="2" fill="white"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M2 9L31 2V9H2Z" fill="#00C999"/>
							</svg>
							<h2 class="title"><?php esc_html_e( 'Return Policy', 'uk-cookie-consent' ); ?></h2>
						</div>
						<p><?php esc_html_e( 'Our return policy generator tailors each policy specifically to your platform, no matter the size, type, or location of the business you operate.', 'uk-cookie-consent' ); ?></p>
					</div>
					<p class="policy-right">
						<a href="<?php echo esc_attr( termly\Urls::get_policies_return_policy_link() ); ?>" target="_blank">
							<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M11.7778 6.5H9C7.34315 6.5 6 7.84315 6 9.5V16.5C6 18.1569 7.34315 19.5 9 19.5H16C17.6569 19.5 19 18.1569 19 16.5V13.8924" stroke="white" stroke-width="2"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M18 7.49997H14V5.49997H20V11.5H18V7.49997Z" fill="white"/>
								<path d="M18.8149 6.68585L13.0483 12.4524" stroke="white" stroke-width="2"/>
							</svg>
							<span><?php esc_html_e( 'Manage', 'uk-cookie-consent' ); ?></span>
						</a>
					</p>
				</div>
			</div>

		</div>

		<?php if ( termly\Account_API_Controller::is_free() ) { ?>
		<div class="termly-content-cell termly-right-column">

			<?php include TERMLY_VIEWS . 'upgrade-notice-sidebar.php'; ?>

		</div>
		<?php } ?>

	</div>

</div>
