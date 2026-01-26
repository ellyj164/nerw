<?php

namespace TutorStripe;

use Ollyo\PaymentHub\Contracts\Payment\ConfigContract;
use Ollyo\PaymentHub\Payments\Stripe\Config;
use Tutor\Ecommerce\Settings;
use Tutor\PaymentGateways\Configs\PaymentUrlsTrait;
use TutorPro\Ecommerce\Config as EcommerceConfig;

/**
 * StripeConfig class.
 *
 * This class handles the configuration for the Stripe payment gateway.
 * It extends the BaseConfig class and implements the ConfigContract interface.
 *
 * @since 1.0.0
 */
class StripeConfig extends Config implements ConfigContract {

	use PaymentUrlsTrait;

	/**
	 * Environment setting.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $environment;

	/**
	 * Secret key.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $secret_key;

	/**
	 * Public key.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $public_key;

	/**
	 * Webhook signature key.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $webhook_signature_key;

	/**
	 * The name of the payment gateway.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $name = 'stripe';

	/**
	 * Constructor.
	 *
	 * Initializes the StripeConfig object.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();
		$settings    = Settings::get_payment_gateway_settings( 'stripe' );
		$config_keys = array_keys( EcommerceConfig::get_stripe_config_keys( 'stripe' ) );
		foreach ( $config_keys as $key ) {
			if ( 'webhook_url' !== $key ) {
				$this->$key = $this->get_field_value( $settings, $key );
			}
		}
	}

	/**
	 * Retrieves the mode of the Stripe payment gateway.
	 *
	 * @since 1.0.0
	 *
	 * @return string The mode of the payment gateway ('test' or 'live').
	 */
	public function getMode(): string {
		return $this->environment;
	}

	/**
	 * Retrieves the secret key for the Stripe payment gateway.
	 *
	 * @since 1.0.0
	 *
	 * @return string The secret key.
	 */
	public function getSecretKey(): string {
		return $this->secret_key;
	}

	/**
	 * Retrieves the public key for the Stripe payment gateway.
	 *
	 * @since 1.0.0
	 *
	 * @return string The public key.
	 */
	public function getPublicKey(): string {
		return $this->public_key;
	}

	/**
	 * Retrieves the webhook key for the Stripe payment gateway.
	 *
	 * @since 1.0.0
	 *
	 * @return string The public key.
	 */
	public function getWebhookSecretKey(): string {
		return $this->webhook_signature_key;
	}

	/**
	 * Determine whether payment gateway configured properly
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function is_configured() {
		return $this->public_key && $this->secret_key && $this->webhook_signature_key;
	}

}
