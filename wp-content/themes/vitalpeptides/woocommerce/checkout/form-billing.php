<?php
/**
 * Checkout billing form (theme override) — split into two visual sections
 * ("Customer Information" + "Shipping Address") to match the Vital Peptide
 * Science reference checkout.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;

$vp_fields = $checkout->get_checkout_fields( 'billing' );

// Keys that belong to the "Customer Information" group (in display order).
$vp_customer_keys = array( 'vp_researcher_type', 'billing_first_name', 'billing_last_name', 'billing_email', 'billing_phone' );

$vp_customer = array();
$vp_shipping = array();
foreach ( $vp_fields as $key => $field ) {
	if ( in_array( $key, $vp_customer_keys, true ) ) {
		$vp_customer[ $key ] = $field;
	} else {
		$vp_shipping[ $key ] = $field;
	}
}
// Preserve the intended order for the customer group.
$vp_customer_ordered = array();
foreach ( $vp_customer_keys as $key ) {
	if ( isset( $vp_customer[ $key ] ) ) {
		$vp_customer_ordered[ $key ] = $vp_customer[ $key ];
	}
}
?>
<div class="woocommerce-billing-fields vp-checkout-section">

	<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

	<h3 class="vp-checkout-section-title"><?php esc_html_e( 'Customer Information', 'vitalpeptides' ); ?></h3>
	<div class="woocommerce-billing-fields__field-wrapper vp-fields-customer">
		<?php
		foreach ( $vp_customer_ordered as $key => $field ) {
			woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
		}
		?>
	</div>

	<h3 class="vp-checkout-section-title vp-checkout-section-title--spaced"><?php esc_html_e( 'Shipping Address', 'vitalpeptides' ); ?></h3>
	<div class="woocommerce-billing-fields__field-wrapper vp-fields-shipping">
		<?php
		foreach ( $vp_shipping as $key => $field ) {
			woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
		}
		?>
	</div>

	<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
</div>

<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
	<div class="woocommerce-account-fields">
		<?php if ( ! $checkout->is_registration_required() ) : ?>
			<p class="form-row form-row-wide create-account">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( 'Create an account?', 'woocommerce' ); ?></span>
				</label>
			</p>
		<?php endif; ?>

		<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

		<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>
			<div class="create-account">
				<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
					<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
				<?php endforeach; ?>
				<div class="clear"></div>
			</div>
		<?php endif; ?>

		<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
	</div>
<?php endif; ?>
