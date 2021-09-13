<?php
/**
 * This file contains robots.txt file modifications.
 *
 * @package termly
 */

namespace termly;

/**
 * This class handles robots.txt file modifications.
 */
class Robots_Txt {

	/**
	 * Hook into WordPress.
	 *
	 * @return void
	 */
	public static function hooks() {

		\add_filter( 'robots_txt', [ __CLASS__, 'virtual' ], 10, 1 );
		\add_action( 'generate_rewrite_rules', [ __CLASS__, 'check_file' ] );

	}

	/**
	 * Hook into the "virtual" robots.txt that WordPress provides.
	 *
	 * @param  string $file The file content.
	 * @return string $file
	 */
	public static function virtual( $file ) {

		return 'User-agent: Scrapy
Allow: /

' . $file;

	}

	/**
	 * Check for an actual robots.txt file.
	 * Fired after rewrite rules are flushed.
	 * We needed a place where users could trigger this.
	 *
	 * @param WP_Rewrite $rules The WP_Rewrite object.
	 *
	 * @return void
	 */
	public static function check_file( $rules ) {

		// Include filesystem functionality.
		require_once ABSPATH . 'wp-admin/includes/file.php';

		// Check that the robots file exists.
		$robots_path = ABSPATH . '/robots.txt';
		if ( ! file_exists( $robots_path ) || ! is_file( $robots_path ) ) {
			return;
		}

		// Initialize the filesystem API.
		global $wp_filesystem;

		$url = \wp_nonce_url(
			\add_query_arg(
				[
					'page' => 'termly',
				],
				\admin_url( 'admin.php' )
			),
			'termly-robots-nonce'
		);

		// Create and test creds.
		$creds = \request_filesystem_credentials( $url, '', false, false, null );
		if ( false === $creds ) {
			return;
		}

		if ( ! \WP_Filesystem( $creds ) ) {
			// Prompt user to enter credentials.
			\request_filesystem_credentials( $url, '', true, false, null );
			return;
		}

		// Check to see if the robots file already has the rule.
		$robots_content = $wp_filesystem->get_contents( $robots_path );
		$robots_rule = 'User-agent: Scrapy
Allow: /';

		if ( false !== strpos( $robots_content, $robots_rule ) ) {
			return;
		}

		$robots_content = $robots_rule . '

' . $robots_content;

		// Prepend the rule. Robots file is read top to bottom.
		$wp_filesystem->put_contents( $robots_path, $robots_content, FS_CHMOD_FILE );

	}

}

Robots_Txt::hooks();
