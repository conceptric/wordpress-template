<?php

namespace termly;

class Frontend {

	public static function hooks() {

		// Embed the snippet.
		add_action( 'wp_head', [ __CLASS__, 'embed_banner' ], PHP_INT_MIN );

	}

	public static function embed_banner() {

		if ( 'yes' !== get_option( 'termly_display_banner', 'no' ) ) {
			return;
		}

		$website    = get_option( 'termly_website', (object) [ 'uuid' => 0 ] );
		$auto_block = get_option( 'termly_display_auto_blocker', 'off' );

		printf(
			'<script
				type="text/javascript"
				src="https://app.termly.io/embed.min.js"
				data-auto-block="%s"
				data-website-uuid="%s">
			</script>',
			esc_js( $auto_block ),
			esc_js( $website->uuid )
		);

	}

}

Frontend::hooks();
