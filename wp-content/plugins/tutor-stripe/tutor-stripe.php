<?php
/**
 * Plugin Name:     Tutor Stripe
 * Plugin URI:      https://tutorlms.com
 * Description:     Stripe payment integration for Tutor LMS
 * Author:          Themeum
 * Author URI:      https://tutorlms.com
 * Text Domain:     tutor-stripe
 * Domain Path:     /languages
 * Version:         1.0.1
 *
 * @package         TutorStripe
 */

// Your code starts here.

/**
 * Guard against fatal errors if Composer dependencies are missing.
 * This prevents site-wide crashes when vendor/ directory is not present
 * (e.g., after fresh GitHub pull without running composer install).
 */
$autoload_file = __DIR__ . '/vendor/autoload.php';
if ( ! file_exists( $autoload_file ) ) {
	/**
	 * Show admin notice when dependencies are missing.
	 * This provides clear feedback to site administrators about the issue.
	 */
	add_action(
		'admin_notices',
		function() {
			$class   = 'notice notice-error';
			$message = sprintf(
				/* translators: %s: plugin name */
				__( '<strong>%s:</strong> Composer dependencies are missing. Please run <code>composer install</code> in the plugin directory, or reinstall the plugin from a complete package.', 'tutor-stripe' ),
				'Tutor Stripe'
			);
			printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), wp_kses_post( $message ) );
		}
	);

	/**
	 * Prevent plugin initialization when dependencies are missing.
	 * This gracefully degrades functionality instead of causing fatal errors.
	 */
	return;
}

require_once $autoload_file;

// Define plugin meta info.
define( 'TUTOR_STRIPE_VERSION', '1.0.1' );
define( 'TUTOR_STRIPE_URL', plugin_dir_url( __FILE__ ) );
define( 'TUTOR_STRIPE_PATH', plugin_dir_path( __FILE__ ) );
define( 'TUTOR_STRIPE_PAYMENTS_DIR', trailingslashit( TUTOR_STRIPE_PATH . 'src/Payments' ) );

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

add_action(
	'plugins_loaded',
	function() {
		if ( is_plugin_active( 'tutor/tutor.php' ) && is_plugin_active( 'tutor-pro/tutor-pro.php' ) ) {
			new TutorStripe\Init();
		}
	},
	100
);
