<?php
/*
 * Public class
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin public class
 **/
if ( ! class_exists( 'CTCC_Public' ) ) { // Don't initialise if there's already a class activated
	class CTCC_Public {
		public function __construct() {
			//
		}
				/*		 * Initialize the class and start calling our hooks and filters		 * @since 2.0.0		 */		public function init() {			add_action ( 'wp_enqueue_scripts', array ( $this, 'enqueue_scripts' ) );			add_action ( 'wp_head', array ( $this, 'add_css' ) );			add_action ( 'wp_footer', array ( $this, 'add_js' ), 1000 );			add_action ( 'wp_footer', array ( $this, 'add_notification_bar' ), 1000 );		}
		/*
		 * Enqueue styles and scripts
		 * @since 2.0.0
		 */
		public function enqueue_scripts() {
			$ctcc_options_settings = get_option ( 'ctcc_options_settings' );
			$options = get_option ( 'ctcc_styles_settings' );
			if ( isset ( $options['enqueue_styles'] ) ) {
				wp_enqueue_style ( 'cookie-consent-style', CTCC_PLUGIN_URL . 'assets/css/style.css', '2.0.0' );
			}
			wp_enqueue_script ( 'cookie-consent', CTCC_PLUGIN_URL . 'assets/js/uk-cookie-consent-js.js', array ( 'jquery' ), '2.0.0', true );
			wp_localize_script (
				'cookie-consent',
				'ctcc_vars',
				array (
					'expiry' 	=> $ctcc_options_settings['cookie_expiry'],
					'method' 	=> isset ( $ctcc_options_settings['first_page'] ),
					'version'	=> $ctcc_options_settings['cookie_version'],
				)
			);
		}
		
		/*
		 * Add some CSS to the header
		 * @since 2.0.0
		 */
		public function add_css() {
			$options = get_option ( 'ctcc_options_settings' );
			$ctcc_styles_settings = get_option ( 'ctcc_styles_settings' );
			$position_css = 'position: fixed;
				left: 0;
				top: 0;
				width: 100%;';
			// Figure out the bar position
			if ( $ctcc_styles_settings['position'] == 'top-bar' ) {
				$position_css = 'position: fixed;
				left: 0;
				top: 0;
				width: 100%;';
			} else if ( $ctcc_styles_settings['position'] == 'bottom-bar' ) {
				$position_css = 'position: fixed;
				left: 0;
				bottom: 0;
				width: 100%;';
			} else if ( $ctcc_styles_settings['position'] == 'top-right-block' ) {
				$position_css = 'position: fixed;
				right: 20px;
				top: 6%;
				width: 300px;';
			} else if ( $ctcc_styles_settings['position'] == 'top-left-block' ) {
				$position_css = 'position: fixed;
				left: 20px;
				top: 6%;
				width: 300px;';
			} else if ( $ctcc_styles_settings['position'] == 'bottom-left-block' ) {
				$position_css = 'position: fixed;
				left: 20px;
				bottom: 6%;
				width: 300px;';
			} else if ( $ctcc_styles_settings['position'] == 'bottom-right-block' ) {
				$position_css = 'position: fixed;
				right: 20px;
				bottom: 6%;
				width: 300px;';
			}
			// Get our styles
			$text_color = $ctcc_styles_settings['text_color'];
			$position = 'top';
			$bg_color = $ctcc_styles_settings['bg_color'];
			$link_color = $ctcc_styles_settings['link_color'];
			$button_bg = $ctcc_styles_settings['button_bg_color'];
			$button_color = $ctcc_styles_settings['button_color'];
			if ( ! empty ( $ctcc_styles_settings['flat_button'] ) ){
				$button_style = 'border: 0; padding: 6px 9px; border-radius: 3px;';
			} else {
				$button_style = '';
			}
			// Build our CSS
			$css = '<style id="ctcc-css" type="text/css" media="screen">';
			$css .= '
			#catapult-cookie-bar {
				box-sizing: border-box;
				max-height: 0;
				opacity: 0;
				z-index: 99999;
				overflow: hidden;
				color: ' . $text_color . ';
				' . $position_css . '
				background-color: ' . $bg_color . ';
			}
			#catapult-cookie-bar a {
				color: ' . $link_color . ';
			}
			button#catapultCookie {
				background:' . $button_bg . ';
				color: ' . $button_color . ';
				' . $button_style . '
			}
			#catapult-cookie-bar h3 {
				color: ' . $text_color . ';
			}
			.has-cookie-bar #catapult-cookie-bar {
				opacity: 1;
				max-height: 999px;
				min-height: 30px;
			}';
			$css .= '</style>';
			echo $css;
			// Add it to the header
		}
		
		/*
		 * Add some JS to the footer
		 * @since 2.0.0
		 */
		public function add_js() { 
			
			$options = get_option( 'ctcc_options_settings' );
			$ctcc_styles_settings = get_option ( 'ctcc_styles_settings' );
			
			if ( $ctcc_styles_settings['position'] == 'top-bar' || $ctcc_styles_settings['position'] == 'bottom-bar' ) {
				$type = 'bar';
			} else {
				$type = 'block';
			} ?>
			
			<script type="text/javascript">
				jQuery(document).ready(function($){
					<?php if ( isset ( $_GET['cookie'] ) ) { ?>
						catapultDeleteCookie('catAccCookies');
					<?php } ?>
					if(!catapultReadCookie("catAccCookies")){ // If the cookie has not been set then show the bar
						$("html").addClass("has-cookie-bar");
						$("html").addClass("cookie-bar-<?php echo $ctcc_styles_settings['position']; ?>");
						$("html").addClass("cookie-bar-<?php echo $type; ?>");
						<?php // Move the HTML down if the bar is at the top
						if ( $ctcc_styles_settings['position'] == 'top-bar' ) {
						?>
							// Wait for the animation on the html to end before recalculating the required top margin
							$("html").on('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(e) {
								// code to execute after transition ends
								var barHeight = $('#catapult-cookie-bar').outerHeight();
								$("html").css("margin-top",barHeight);
								$("body.admin-bar").css("margin-top",barHeight-32); // Push the body down if the admin bar is active
							});
						<?php } ?>
					}
					<?php  if ( $options['closure'] == 'timed' ) {
						// Add some script if it's on a timer
						$duration = absint($options['duration']) * 1000; ?>
						setTimeout(ctccCloseNotification, <?php echo $duration; ?>);
					<?php  } ?>
					<?php  if ( ! empty ( $options['first_page'] ) ) {
						// Add some script if the notification only displays on the first page ?>
						ctccFirstPage();
					<?php  } ?>
				});
			</script>
			
		<?php }
		
		/*
		 * Add the notification bar itself
		 * @since 2.0.0
		 */
		public function add_notification_bar() {
			
			$ctcc_options_settings = get_option ( 'ctcc_options_settings' );
			$ctcc_content_settings = get_option ( 'ctcc_content_settings' );
			$ctcc_styles_settings = get_option ( 'ctcc_styles_settings' );

			// Check if it's a block or a bar
			$is_block = true;
			if ( $ctcc_styles_settings['position'] == 'top-bar' || $ctcc_styles_settings['position'] == 'bottom-bar' ) {
				$is_block = false; // It's a bar
			}
			
			// Add some classes to the block
			$classes = '';
			if ( $is_block ) {
				if ( ! empty ( $ctcc_styles_settings['rounded_corners'] ) ) {
					$classes .= ' rounded-corners';
				}
				if ( ! empty ( $ctcc_styles_settings['drop_shadow'] ) ) {
					$classes .= ' drop-shadow';
				}
			}
			if ( ! empty ( $ctcc_styles_settings['x_close'] ) ) {
				$classes .= ' use_x_close';
			}
			if ( empty ( $ctcc_styles_settings['display_accept_with_text'] ) ) {
				$classes .= ' float-accept';
			}
			
			// Allowed tags
			$allowed = array (
				'a' => array (
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
				'p' => array()
			);
			
			$content = '';
			$close_content = '';
			
			// Print the notification bar
			$content = '<div id="catapult-cookie-bar" class="' . $classes . '">';
			
			// Add a custom wrapper class if specified
			if ( $ctcc_styles_settings['position'] == 'top-bar' || $ctcc_styles_settings['position'] == 'bottom-bar' ) {
				$content .= '<div class="ctcc-inner ' . esc_attr ( str_replace ( '.', '', $ctcc_styles_settings['container_class'] ) ) . '">';
				$close_content = '</div><!-- custom wrapper class -->';
			}
			
			// Add a title if it's a block
			if ( $ctcc_styles_settings['position'] != 'top-bar' && $ctcc_styles_settings['position'] != 'bottom-bar' ) {
				$content .= sprintf ( '<h3>%s</h3>',
					wp_kses ( $ctcc_content_settings['heading_text'], $allowed )
				);
			}
			
			// Make the Read More link
			$more_text = '';
			if ( $ctcc_content_settings['more_info_text'] ) {
				// Find what page we're linking to
				if ( ! empty ( $ctcc_content_settings['more_info_url'] ) ) {
					// Check the absolute URL first
					$link = $ctcc_content_settings['more_info_url'];
				} else {
					// Use the internal page
					$link = get_permalink ( $ctcc_content_settings['more_info_page'] );
				}
				$more_text = sprintf ( 
					'<a tabindex=1 target="%s" href="%s">%s</a>',
					esc_attr ( $ctcc_content_settings['more_info_target'] ),
					esc_url ( $link ),
					wp_kses ( $ctcc_content_settings['more_info_text'], $allowed )
				);
			}
			
			$button_text = '';
			if ( empty ( $ctcc_styles_settings['x_close'] ) ) {
				$button_text = sprintf ( 
					'<button id="catapultCookie" tabindex=1 onclick="catapultAcceptCookies();">%s</button>',
					wp_kses ( $ctcc_content_settings['accept_text'], $allowed )
				);
			}
			
			// The main bar content
			$content .= sprintf ( 
				'<span class="ctcc-left-side">%s %s</span><span class="ctcc-right-side">%s</span>',
				wp_kses ( $ctcc_content_settings['notification_text'], $allowed ),
				$more_text,
				$button_text
			);
			
			// X close button
			if ( ! empty ( $ctcc_styles_settings['x_close'] ) ) {
				$content .= '<div class="x_close"></div>';
			}
			
			// Close custom wrapper class if used
			$content .= $close_content;

			$content .= '</div><!-- #catapult-cookie-bar -->';
			
			echo apply_filters ( 'catapult_cookie_content', $content, $ctcc_content_settings );

		}

	}
	
}