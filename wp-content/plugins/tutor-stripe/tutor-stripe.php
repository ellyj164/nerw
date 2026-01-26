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
require_once __DIR__ . '/vendor/autoload.php';

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
