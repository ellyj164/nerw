<?php
/**
 * Payment gateway concrete class
 *
 * @package Tutor\Ecommerce
 * @author Themeum
 * @link https://themeum.com
 * @since 1.0.0
 */

namespace TutorStripe;

use Ollyo\PaymentHub\Payments\Stripe\Stripe;
use Tutor\PaymentGateways\GatewayBase;

/**
 * Stripe payment gateway class
 */
class StripeGateway extends GatewayBase {

	/**
	 * Payment gateway root dir name
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $dir_name = 'Stripe';

	/**
	 * Payment gateway config class
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $config_class = StripeConfig::class;

	/**
	 * Payment core class
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $payment_class = Stripe::class;

	/**
	 * Root dir name of payment gateway src
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_root_dir_name():string {
		return $this->dir_name;
	}

	/**
	 * Payment class from payment hub
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_payment_class():string {
		return $this->payment_class;
	}

	/**
	 * Payment config class
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_config_class():string {
		return $this->config_class;
	}

	/**
	 * Return autoload file
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_autoload_file() {
		return '';
	}
}
