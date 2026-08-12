<?php
/**
 * Plugin Name: Vital Peptides - CheckoutChamp Gateway
 * Description: Charges WooCommerce orders through CheckoutChamp's order import API.
 * Version: 1.0.0
 * Requires Plugins: woocommerce
 *
 * CheckoutChamp credentials are configured under WooCommerce > Settings > Payments.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'plugins_loaded', 'vp_checkoutchamp_gateway_init', 20 );

/**
 * Register the CheckoutChamp gateway after WooCommerce is available.
 */
function vp_checkoutchamp_gateway_init() {
	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		return;
	}

	class VP_CheckoutChamp_Gateway extends WC_Payment_Gateway {
		const API_URL     = 'https://api.checkoutchamp.com/order/import/';
		const CAMPAIGN_ID = 126;
		const PRODUCT_ID  = 11243;

		/** @var string */
		public $login_id;

		/** @var string */
		public $api_password;

		public function __construct() {
			$this->id                 = 'vp_checkoutchamp';
			$this->method_title       = __( 'CheckoutChamp', 'vp-checkoutchamp' );
			$this->method_description = __( 'Process card payments through CheckoutChamp.', 'vp-checkoutchamp' );
			$this->has_fields         = true;
			$this->supports           = array( 'products' );

			$this->init_form_fields();
			$this->init_settings();

			$this->title        = $this->get_option( 'title', __( 'Credit / Debit Card', 'vp-checkoutchamp' ) );
			$this->description  = $this->get_option( 'description', __( 'Pay securely using your credit or debit card.', 'vp-checkoutchamp' ) );
			$this->enabled      = $this->get_option( 'enabled', 'no' );
			$this->login_id     = $this->get_option( 'login_id', '' );
			$this->api_password = $this->get_option( 'api_password', '' );

			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		}

		public function init_form_fields() {
			$this->form_fields = array(
				'enabled' => array(
					'title'   => __( 'Enable/Disable', 'vp-checkoutchamp' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable CheckoutChamp payments', 'vp-checkoutchamp' ),
					'default' => 'no',
				),
				'title' => array(
					'title'   => __( 'Title', 'vp-checkoutchamp' ),
					'type'    => 'text',
					'default' => __( 'Credit / Debit Card', 'vp-checkoutchamp' ),
				),
				'description' => array(
					'title'   => __( 'Description', 'vp-checkoutchamp' ),
					'type'    => 'textarea',
					'default' => __( 'Pay securely using your credit or debit card.', 'vp-checkoutchamp' ),
				),
				'login_id' => array(
					'title'       => __( 'CheckoutChamp login ID', 'vp-checkoutchamp' ),
					'type'        => 'text',
					'description' => __( 'Provided by CheckoutChamp. Do not use the example value in production.', 'vp-checkoutchamp' ),
				),
				'api_password' => array(
					'title' => __( 'CheckoutChamp API password', 'vp-checkoutchamp' ),
					'type'  => 'password',
				),
			);
		}

		/** Render card inputs. Values are never saved to the WooCommerce order. */
		public function payment_fields() {
			if ( $this->description ) {
				echo wpautop( wp_kses_post( $this->description ) );
			}
			?>
			<fieldset id="wc-<?php echo esc_attr( $this->id ); ?>-cc-form" class="wc-credit-card-form wc-payment-form">
				<p class="form-row form-row-wide">
					<label for="vp-cc-number"><?php esc_html_e( 'Card number', 'vp-checkoutchamp' ); ?> <span class="required">*</span></label>
					<input id="vp-cc-number" name="vp_cc_number" type="text" inputmode="numeric" autocomplete="cc-number" maxlength="19" />
				</p>
				<p class="form-row form-row-first">
					<label for="vp-cc-expiry"><?php esc_html_e( 'Expiry (MM / YY)', 'vp-checkoutchamp' ); ?> <span class="required">*</span></label>
					<input id="vp-cc-expiry" name="vp_cc_expiry" type="text" inputmode="numeric" autocomplete="cc-exp" placeholder="MM / YY" maxlength="7" />
				</p>
				<p class="form-row form-row-last">
					<label for="vp-cc-cvc"><?php esc_html_e( 'Card security code', 'vp-checkoutchamp' ); ?> <span class="required">*</span></label>
					<input id="vp-cc-cvc" name="vp_cc_cvc" type="password" inputmode="numeric" autocomplete="cc-csc" maxlength="4" />
				</p>
				<div class="clear"></div>
			</fieldset>
			<?php
		}

		public function validate_fields() {
			$number = preg_replace( '/\D+/', '', isset( $_POST['vp_cc_number'] ) ? wp_unslash( $_POST['vp_cc_number'] ) : '' );
			$expiry = preg_replace( '/\D+/', '', isset( $_POST['vp_cc_expiry'] ) ? wp_unslash( $_POST['vp_cc_expiry'] ) : '' );
			$cvc    = preg_replace( '/\D+/', '', isset( $_POST['vp_cc_cvc'] ) ? wp_unslash( $_POST['vp_cc_cvc'] ) : '' );

			if ( ! preg_match( '/^\d{13,19}$/', $number ) || ! preg_match( '/^\d{4}$/', $expiry ) || ! preg_match( '/^\d{3,4}$/', $cvc ) ) {
				wc_add_notice( __( 'Please enter valid card details.', 'vp-checkoutchamp' ), 'error' );
				return false;
			}

			return true;
		}

		public function process_payment( $order_id ) {
			$order = wc_get_order( $order_id );
			if ( ! $order || ! $this->login_id || ! $this->api_password ) {
				wc_add_notice( __( 'Card payment is not configured. Please contact us for assistance.', 'vp-checkoutchamp' ), 'error' );
				return array( 'result' => 'failure' );
			}

			$number = preg_replace( '/\D+/', '', wp_unslash( $_POST['vp_cc_number'] ) );
			$expiry = preg_replace( '/\D+/', '', wp_unslash( $_POST['vp_cc_expiry'] ) );
			$cvc    = preg_replace( '/\D+/', '', wp_unslash( $_POST['vp_cc_cvc'] ) );
			$month  = substr( $expiry, 0, 2 );
			$year   = '20' . substr( $expiry, 2, 2 );

			$payload = array(
				'loginId'          => $this->login_id,
				'password'         => $this->api_password,
				'firstName'        => $order->get_billing_first_name(),
				'lastName'         => $order->get_billing_last_name(),
				'address1'         => $order->get_billing_address_1(),
				'address2'         => $order->get_billing_address_2(),
				'postalCode'       => $order->get_billing_postcode(),
				'city'             => $order->get_billing_city(),
				'state'            => $order->get_billing_state(),
				'country'          => $order->get_billing_country(),
				'emailAddress'     => $order->get_billing_email(),
				'phoneNumber'      => $order->get_billing_phone(),
				'shipFirstName'    => $order->get_shipping_first_name() ?: $order->get_billing_first_name(),
				'shipLastName'     => $order->get_shipping_last_name() ?: $order->get_billing_last_name(),
				'shipAddress1'     => $order->get_shipping_address_1() ?: $order->get_billing_address_1(),
				'shipAddress2'     => $order->get_shipping_address_2() ?: $order->get_billing_address_2(),
				'shipPostalCode'   => $order->get_shipping_postcode() ?: $order->get_billing_postcode(),
				'shipCity'         => $order->get_shipping_city() ?: $order->get_billing_city(),
				'shipState'        => $order->get_shipping_state() ?: $order->get_billing_state(),
				'shipCountry'      => $order->get_shipping_country() ?: $order->get_billing_country(),
				'paySource'        => 'CREDITCARD',
				'cardNumber'       => $number,
				'cardMonth'        => $month,
				'cardYear'         => $year,
				'cardSecurityCode' => $cvc,
				'campaignId'       => self::CAMPAIGN_ID,
				'product1_id'      => self::PRODUCT_ID,
				'product1_qty'     => 1,
			);

			$response = wp_remote_post( self::API_URL, array(
				'timeout' => 45,
				'body'    => $payload,
			) );

			// Remove all card values from memory as soon as the request is complete.
			unset( $payload['cardNumber'], $payload['cardSecurityCode'], $number, $cvc );

			if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) < 200 || wp_remote_retrieve_response_code( $response ) >= 300 ) {
				$order->add_order_note( 'CheckoutChamp payment failed to reach the API.' );
				wc_add_notice( __( 'Your payment could not be processed. Please check your details and try again.', 'vp-checkoutchamp' ), 'error' );
				return array( 'result' => 'failure' );
			}

			$body   = wp_remote_retrieve_body( $response );
			$parsed = json_decode( $body, true );
			if ( ! is_array( $parsed ) ) {
				$parsed = array();
				parse_str( $body, $parsed ); // CheckoutChamp may return a query-string response.
			}

			if ( empty( $parsed['responseCode'] ) || '100' !== (string) $parsed['responseCode'] ) {
				$order->add_order_note( 'CheckoutChamp declined the payment: ' . sanitize_text_field( $parsed['responseMessage'] ?? 'Unknown response' ) );
				wc_add_notice( __( 'Your card was declined. Please use another payment method or contact your bank.', 'vp-checkoutchamp' ), 'error' );
				return array( 'result' => 'failure' );
			}

			if ( is_array( $parsed ) && ! empty( $parsed['orderId'] ) ) {
				$order->update_meta_data( '_vp_checkoutchamp_order_id', sanitize_text_field( $parsed['orderId'] ) );
			}

			$order->payment_complete();
			$order->add_order_note( sprintf( 'CheckoutChamp payment approved (campaign %d, product %d).', self::CAMPAIGN_ID, self::PRODUCT_ID ) );
			WC()->cart->empty_cart();

			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);
		}
	}
}

add_filter( 'woocommerce_payment_gateways', 'vp_checkoutchamp_add_gateway' );

function vp_checkoutchamp_add_gateway( $gateways ) {
	$gateways[] = 'VP_CheckoutChamp_Gateway';
	return $gateways;
}
