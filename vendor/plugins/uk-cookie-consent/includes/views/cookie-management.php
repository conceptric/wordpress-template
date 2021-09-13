<?php
/**
 * Handles the display of the Cookie Management admin page.
 *
 * @package termly
 */

?>
<div class="wrap termly termly-cookie-management">

	<div class="termly-content-wrapper">

		<div class="termly-content-cell termly-left-column">
			<div class="termly-content-header">
				<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 256 256" xml:space="preserve">
					<title><?php esc_html_e( 'Termly', 'uk-cookie-consent' ); ?></title>
					<path fill="#547eed" d="M151.22,104.05l34.83-34.11H84.46V204.2l101.6-100.15H151.22z M128,255C57.61,255,1,198.39,1,128S57.61,1,128,1 s127,56.61,127,127S198.39,255,128,255z"/>
				</svg>
				<h1><?php esc_html_e( 'Cookie Management', 'uk-cookie-consent' ); ?></h1>
				<a href="<?php echo esc_attr( termly\Urls::get_new_cookie_url() ); ?>" class="page-title-action" class="new-cookie">
					<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M11.8334 6.83332H6.83335V11.8333H5.16669V6.83332H0.166687V5.16666H5.16669V0.166656H6.83335V5.16666H11.8334V6.83332Z" fill="white"/>
					</svg>
					<?php esc_html_e( 'New Cookie', 'uk-cookie-consent' ); ?>
				</a>
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

			<div class="content cookie-management">

				<p>
				<?php
				printf(
					wp_kses(
						__(
							'Your site needs a cookie policy to remain compliant. View <a href="%s">policies</a> to create one.',
							'uk-cookie-consent'
						),
						[
							'a' => [ 'href' => [] ],
						]
					),
					add_query_arg(
						[
							'page' => 'termly-policies',
						],
						admin_url( 'admin.php' )
					)
				);
				?>
				</p>

				<form method="get">

				<?php
					require_once TERMLY_CONTROLLERS . 'class-site-scan-results-list-table.php';
					$site_scan_table = new termly\Site_Scan_Results_List_Table();
					$site_scan_table->search_box( __( 'Search Cookies', 'uk-cookie-consent' ), 'cookie' );
					$site_scan_table->prepare_items();
					$site_scan_table->display();
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
