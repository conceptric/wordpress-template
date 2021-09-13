<?php

namespace termly;

// \WP_List_Table is not loaded automatically so we need to load it in our application.
if ( ! class_exists( '\WP_List_Table' ) ) {

	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Site_Scan_Results_List_Table extends \WP_List_Table {

	/**
	 * Prepare the items for the table to process
	 *
	 * @return void
	 */
	public function prepare_items() {

		// Handle delete action
		$this->handle_delete_action();

		$columns  = $this->get_columns();
		$hidden   = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$data = $this->table_data();
		usort( $data, [ &$this, 'sort_data' ] );

		// Handle filtering by category
		if ( isset( $_GET['cookie-category'] ) && '0' !== $_GET['cookie-category'] ) {
			$data = array_filter( $data, function( $cookie ) {
				$category = sanitize_text_field( $_GET['cookie-category'] );
				return $cookie->category === $category;
			} );
		}

		// Handle filtering by domain
		if ( isset( $_GET['cookie-domain'] ) && '0' !== $_GET['cookie-domain'] ) {
			$data = array_filter( $data, function( $cookie ) {
				$domain = sanitize_text_field( $_GET['cookie-domain'] );
				return $cookie->domain === $domain;
			} );
		}

		// Handle searching
		if ( isset( $_GET['s'] ) && '' !== $_GET['s'] ) {

			$query = sanitize_text_field( $_GET['s'] );

			// Start an array for the search results
			$search_results = [];

			// Loop through the data and add string matches to search results
			foreach ( $data as $item ) {
				// Test name, description, category, and domain
				if (
					self::search_for_string( $query, $item->name ) ||
					self::search_for_string( $query, $item->en_us ) ||
					self::search_for_string( $query, $item->category ) ||
					self::search_for_string( $query, $item->domain )
				) {
					$search_results[] = $item;
				}
			}

			$data = $search_results;

		}

		$per_page     = 20;
		$current_page = $this->get_pagenum();
		$total_items  = count( $data );

		if ( $total_items > 0 ) {
			$this->set_pagination_args(
				[
					'total_items' => $total_items,
					'per_page'    => $per_page,
				]
			);
		}

		$data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );

		$this->_column_headers = [ $columns, $hidden, $sortable ];
		$this->items           = $data;

	}

	/**
	 * @return bool
	 */
	public function has_items() {

		$data = self::table_data();
		return count( $data );

	}

	/**
	 */
	public function no_items() {

		echo esc_html( __( 'No cookies found.', 'uk-cookie-consent' ) );

	}

	/**
	 * @return array
	 */
	protected function get_bulk_actions() {

		$actions = array();

		if ( current_user_can( 'manage_options' ) ) {

			$actions['delete'] = __( 'Delete Permanently', 'uk-cookie-consent' );

		}

		return $actions;

	}

	/**
	 * Override the parent columns method. Defines the columns to use in your listing table
	 *
	 * @return Array
	 */
	public function get_columns() {

		$columns = [
			'cb'          => '<input type="checkbox" />',
			'name'        => __( 'Cookie Name', 'uk-cookie-consent' ),
			'description' => __( 'Description', 'uk-cookie-consent' ),
			'category'    => __( 'Category', 'uk-cookie-consent' ),
			'domain'      => __( 'Domain', 'uk-cookie-consent' ),
			'new'         => __( 'Status', 'uk-cookie-consent' ),
		];

		return $columns;

	}

	/**
	 * Define which columns are hidden
	 *
	 * @return Array
	 */
	public function get_hidden_columns() {
		return [];
	}

	/**
	 * Define the sortable columns
	 *
	 * @return Array
	 */
	public function get_sortable_columns() {

		return [
			'name' => [
				'name',
				false,
			],
			'category' => [
				'category',
				false,
			],
			'domain' => [
				'domain',
				false,
			],
			'new' => [
				'new',
				false,
			],
		];

	}

	/**
	 * Get the table data
	 *
	 * @return Array
	 */
	private function table_data() {

		$data = [];

		$data = get_transient( 'termly-site-scan-results' );
		if ( false === $data ) {

			delete_transient( 'termly-site-scan-results' );

			$scan_results = Termly_API_Controller::call( 'GET', 'report', [ 'with_cookies' => 'true' ] );
			if ( 200 === wp_remote_retrieve_response_code( $scan_results ) && ! is_wp_error( $scan_results ) ) {

				$results = json_decode( wp_remote_retrieve_body( $scan_results ) );
				if ( property_exists( $results, 'cookies' ) ) {

					$data = $results->cookies;
					set_transient( 'termly-site-scan-results', $data, DAY_IN_SECONDS );

				}

			} else {
				$data = [];
			}

		}

		return $data;
	}

	/**
	 * Displays a categories drop-down for filtering on the Cookie list table.
	 */
	protected function categories_dropdown() {

		// This field ensures that the page is correct
		echo '<input name="page" value="cookie-management" type="hidden">';

		$data       = self::table_data();
		$categories = array_unique( wp_list_pluck( $data, 'category' ) );
		sort( $categories );

		echo '<label class="screen-reader-text" for="cookie-category">' . __( 'Filter by category', 'uk-cookie-consent' ) . '</label>';
		echo '<select name="cookie-category">';
		echo '<option value="0">All Categories</option>';
		foreach ( $categories as $category ) {

			printf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $category ),
				selected( isset( $_GET['cookie-category'] ) && $_GET['cookie-category'] === $category, true, false ),
				esc_html( ucwords( implode( ' ', explode( '_', $category ) ) ) )
			);

		}
		echo '</select>';

	}

	/**
	 * Displays a domains drop-down for filtering on the Cookie list table.
	 */
	protected function domains_dropdown() {

		$data    = self::table_data();
		$domains = array_unique( wp_list_pluck( $data, 'domain' ) );
		sort( $domains );

		echo '<label class="screen-reader-text" for="cookie-domain">' . __( 'Filter by domain', 'uk-cookie-consent' ) . '</label>';
		echo '<select name="cookie-domain">';
		echo '<option value="0">All Domains</option>';
		foreach ( $domains as $domain ) {

			printf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $domain ),
				selected( isset( $_GET['cookie-domain'] ) && $_GET['cookie-domain'] === $domain, true, false ),
				esc_html( $domain )
			);

		}
		echo '</select>';

	}

	/**
	 * @param string $which
	 */
	protected function extra_tablenav( $which ) {
		?>
		<div class="alignleft actions">
		<?php
		if ( 'top' === $which ) {
			ob_start();

			$this->categories_dropdown();
			$this->domains_dropdown();

			$output = ob_get_clean();

			if ( ! empty( $output ) ) {
				echo $output;
				submit_button( __( 'Filter' ), '', 'filter_action', false, array( 'id' => 'termly-cookie-query-submit' ) );
			}
		}
		?>
		</div>
		<?php
		/**
		 * Fires immediately following the closing "actions" div in the tablenav for the posts
		 * list table.
		 *
		 * @since 4.4.0
		 *
		 * @param string $which The location of the extra table nav markup: 'top' or 'bottom'.
		 */
		do_action( 'manage_termly_cookies_extra_tablenav', $which );
	}

	/**
	 * @return array
	 */
	protected function get_table_classes() {
		return array( 'termly', 'widefat', 'fixed', 'striped' );
	}

	/**
	 * Generates and displays row action links.
	 *
	 * @since 4.3.0
	 *
	 * @param object $item        Cookie being acted upon.
	 * @param string $column_name Current column name.
	 * @param string $primary     Primary column name.
	 * @return string Row actions output for posts.
	 */
	protected function handle_row_actions( $item, $column_name, $primary = 'name' ) {

		if ( $primary !== $column_name ) {
			return '';
		}

		$can_edit_post    = current_user_can( 'manage_options' );
		$actions          = array();

		if ( $can_edit_post ) {
			$actions['edit'] = sprintf(
				'<a href="%s" aria-label="%s">%s</a>',
				Urls::get_edit_cookie_link( $item->id ),
				/* translators: %s: Cookie title. */
				esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;', 'uk-cookie-consent' ), $item->name ) ),
				__( 'Edit', 'uk-cookie-consent' )
			);
		}

		if ( current_user_can( 'manage_options' ) ) {
			$actions['delete'] = sprintf(
				'<a href="%s" class="submitdelete" aria-label="%s">%s</a>',
				Urls::get_delete_cookie_link( $item->id, $this->_args ),
				/* translators: %s: Cookie title. */
				esc_attr( sprintf( __( 'Delete &#8220;%s&#8221; permanently', 'uk-cookie-consent' ), $item->name ) ),
				__( 'Delete Permanently', 'uk-cookie-consent' )
			);
		}

		$actions = apply_filters( 'cookie_row_actions', $actions, $item );

		return $this->row_actions( $actions );
	}

	/**
	 * Gets the name of the default primary column.
	 *
	 * @since 4.3.0
	 *
	 * @return string Name of the default primary column, in this case, 'name'.
	 */
	protected function get_default_primary_column_name() {
		return 'name';
	}

	/**
	 * Handles the checkbox column output.
	 *
	 * @param object $item The current cookie object.
	 */
	public function column_cb( $item ) {

		if ( current_user_can( 'manage_options' ) ) :
			?>
			<label class="screen-reader-text" for="cb-select-<?php echo esc_attr( $item->id ); ?>">
				<?php
					/* translators: %s: Cookie name. */
					printf( __( 'Select %s' ), esc_attr( $item->name ) );
				?>
			</label>
			<input id="cb-select-<?php echo esc_attr( $item->id ); ?>" type="checkbox" name="cookie[]" value="<?php echo esc_attr( $item->id ); ?>" />
			<?php
		endif;

	}

	/**
	 * Define what data to show on each column of the table
	 *
	 * @param array  $item        Data.
	 * @param string $column_name Current column name.
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {

		switch ( $column_name ) {

			case 'name':
				$value = $item->name;
				break;

			case 'description':
				$value = $item->en_us;
				break;

			case 'category':
				$value = $item->category;
				break;

			case 'domain':
				$value = $item->domain;
				break;

			case 'new':
				$value = sprintf(
					'<span class="termly-existing-cookie">%s</span>',
					__( 'Existing', 'uk-cookie-consent' )
				);
				if ( true === $item->new ) {
					$value = sprintf(
						'<span class="termly-new-cookie">%s</span>',
						__( 'New', 'uk-cookie-consent' )
					);
				}
				break;

			default:
				$value = print_r( $item, true ) ;

		}

		return $value;

	}

	/**
	 * Allows you to sort the data by the variables set in the $_GET.
	 *
	 * @param string $a The first item to compare.
	 * @param string $b The second item to compare.
	 * @return mixed
	 */
	private function sort_data( $a, $b ) {

		// Set defaults.
		$orderby = 'name';
		$order   = 'asc';

		// If orderby is set, use this as the sort column.
		if ( array_key_exists( 'orderby', $_GET ) && ! empty( $_GET['orderby'] ) ) {
			$orderby = $_GET['orderby'];
		}

		// If order is set use this as the order.
		if ( array_key_exists( 'order', $_GET ) && ! empty( $_GET['order'] ) ) {
			$order = $_GET['order'];
		}

		$result = strcmp( $a->$orderby, $b->$orderby );

		if ( 'asc' === $order ) {
			return $result;
		}

		return -$result;

	}

	/**
	 * search_for_string
	 *
	 * @param  string $query The search query
	 * @param  string $string_to_search The text to be searched
	 *
	 * @return bool True if found, false if not found
	 */
	public static function search_for_string( $query, $string_to_search ) {

		// First, make everything lowercase
		$query = strtolower( $query );
		$string_to_search = strtolower( $string_to_search );

		// If anything is empty, return false
		if ( '' === $string_to_search || '' === $query ) {
			return false;
		}

		// Test for occurence of the query
		return false !== strpos( $string_to_search, $query );

	}

	/**
	 * handle_delete_action Handle deleting one or more cookies
	 *
	 * @return void
	 */
	public function handle_delete_action() {

		// Not using $this->current_action to prevent interference with filtering
		if ( ! isset( $_REQUEST['action'] ) || 'delete' !== $_REQUEST['action'] ) {
			return;
		}

		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-' . $this->_args['plural'] ) ) {
			return;
		}

		if ( ! isset( $_REQUEST['cookie'] ) ) {
			return;
		}

		// Collect cookies. They may be a single cookie id or an array of cookie ids
		$cookies = [];
		if ( is_array( $_REQUEST['cookie'] ) ) {
			foreach ( $_REQUEST['cookie'] as $cookie ) {
				$cookies[] = intval( $cookie );
			}
		} else {
			$cookies[] = intval( $_REQUEST['cookie'] );
		}

		$cookies_success_deleted = [];
		$cookies_error_deleted = [];

		// Retrieve the table data
		$table_data = $this->table_data();

		// Delete cookies
		foreach ( $cookies as $cookie_id ) {

			// Get the correct cookie name
			$cookie_name = false;
			foreach ( $table_data as $value ) {
				if ( $cookie_id === $value->id ) {
					$cookie_name = $value->name;
				}
			}

			// If the cookie name was not found, then this request is outdated
			if ( ! $cookie_name ) {
				continue;
			}

			// DELETE request to the API
			$response = Termly_API_Controller::call( 'DELETE', 'cookies/' . $cookie_id );

			if ( 200 === wp_remote_retrieve_response_code( $response ) && ! is_wp_error( $response ) ) {
				// Add to success array
				$cookies_success_deleted[] = $cookie_name;
			} else {
				// Add to error array
				$cookies_error_deleted[] = $cookie_name;
			}

		}

		// Delete the scan results transient so we
		// have to re-retrieve accurate results
		delete_transient( 'termly-site-scan-results' );

		// Display message with cookies that were successfully deleted
		if ( count( $cookies_success_deleted ) ) {
			$cookies = implode( ', ', $cookies_success_deleted );
			printf(
				'<div class="updated notice notice-success">
					<p>%s: <strong>%s</strong></p>
				</div>',
				1 === count( $cookies_success_deleted ) ?
					esc_html__( 'The following cookie has been deleted', 'uk-cookie-consent' ) :
					esc_html__( 'The following cookies have been deleted', 'uk-cookie-consent' ),
				$cookies
			);
		}

		// Display message with cookies that errored when deleted
		if ( count( $cookies_error_deleted ) ) {
			$cookies = implode( ', ', $cookies_error_deleted );
			printf(
				'<div class="notice-error notice">
					<p>%s: <strong>%s</strong></p>
				</div>',
				1 === count( $cookies_error_deleted ) ?
					esc_html__( 'Unable to delete the following cookie', 'uk-cookie-consent' ) :
					esc_html__( 'Unable to delete the following cookies', 'uk-cookie-consent' ),
				$cookies
			);
		}

	}

}
