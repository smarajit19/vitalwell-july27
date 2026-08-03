<?php
/**
 * Checkout Form (theme override).
 *
 * Adds a two-column layout (customer details | sticky order summary) and a
 * styled sign-in / create-account gate for non-registered visitors, matching
 * the Vital Peptide Science design system.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	$vp_auth_url  = home_url( '/auth/' );
	$vp_redirect  = wc_get_checkout_url();
	$vp_login_url = add_query_arg( array( 'view' => 'login', 'redirect_to' => $vp_redirect ), $vp_auth_url );
	$vp_signup_url = add_query_arg( array( 'view' => 'signup', 'redirect_to' => $vp_redirect ), $vp_auth_url );
	?>
	<div class="vp-checkout-gate">
		<div class="vp-checkout-gate-icon"><?php echo vp_icon( 'lock' ); // phpcs:ignore ?></div>
		<h2><?php esc_html_e( 'Sign in to complete your order', 'vitalpeptides' ); ?></h2>
		<p><?php echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'Checkout is restricted to registered, approved researchers. Please sign in to your account to place an order.', 'vitalpeptides' ) ) ); ?></p>
		<div class="vp-checkout-gate-actions">
			<a href="<?php echo esc_url( $vp_signup_url ); ?>" class="vp-btn vp-btn--gradient"><?php esc_html_e( 'Create Account', 'vitalpeptides' ); ?></a>
			<a href="<?php echo esc_url( $vp_login_url ); ?>" class="vp-btn vp-btn--ghost"><?php esc_html_e( 'Sign In', 'vitalpeptides' ); ?></a>
		</div>
	</div>
	<?php
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout vp-checkout-form" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__( 'Checkout', 'woocommerce' ); ?>">

	<div class="vp-checkout-grid">

		<div class="vp-checkout-main">
			<h1 class="vp-checkout-title"><?php esc_html_e( 'Checkout', 'vitalpeptides' ); ?></h1>

			<?php if ( $checkout->get_checkout_fields() ) : ?>

				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

				<div class="col2-set" id="customer_details">
					<div class="col-1">
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
					</div>

					<div class="col-2">
						<?php do_action( 'woocommerce_checkout_shipping' ); ?>
					</div>
				</div>

				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

			<?php endif; ?>

			<!-- Payment + place order live under the form (reference layout) -->
			<div class="vp-checkout-payment">
				<?php woocommerce_checkout_payment(); ?>
			</div>

			<div class="vp-checkout-trust">
				<span><?php echo vp_icon( 'shield' ); // phpcs:ignore ?><?php esc_html_e( 'Secure & Encrypted Checkout', 'vitalpeptides' ); ?></span>
				<span><?php echo vp_icon( 'lock' ); // phpcs:ignore ?><?php esc_html_e( 'SSL Protected', 'vitalpeptides' ); ?></span>
				<span><?php echo vp_icon( 'flask' ); // phpcs:ignore ?><?php esc_html_e( 'Research Use Only', 'vitalpeptides' ); ?></span>
			</div>
		</div>

		<aside class="vp-checkout-aside">
			<div class="vp-checkout-summary-card">
				<h3 id="order_review_heading"><?php esc_html_e( 'Order Summary', 'vitalpeptides' ); ?></h3>

				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php woocommerce_order_review(); ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
			</div>
		</aside>

	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
