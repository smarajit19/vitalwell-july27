<?php
/**
 * Vital Peptides — Compliance module.
 *
 * Centralises every marketing/legal-compliance behaviour so it can be
 * reviewed, deployed, or removed as one unit:
 *   - 21+ age verification prompt (site-wide)
 *   - Gated checkout (login required, no guest checkout)
 *   - Researcher-type field + research-use agreement at checkout
 *   - Global research-only footer disclaimer
 *   - Product scientific specification fields (CAS, formula, MW, purity,
 *     storage, batch/lot, analysis) + front-end spec table
 *   - Removal of product reviews/ratings (testimonials)
 *   - Safety-net hiding of prohibited products
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

/* ==========================================================================
 * 0. Central compliance text
 * ==========================================================================
 * Keep the legally-reviewed strings in one place. Confirm exact wording with
 * the client / MPA before go-live (see DEPLOYMENT-COMPLIANCE.md).
 */

/**
 * The exact global footer disclaimer shown on every page.
 */
function vp_footer_disclaimer_text() {
	return __(
		'All products sold by Vital Peptide Science are intended strictly for laboratory and scientific research use only. They are NOT for human or animal consumption, and are not drugs, foods, cosmetics, or medical devices. Products are not intended to diagnose, treat, cure, or prevent any disease or condition. By purchasing, the buyer affirms they are a qualified researcher and will handle all products in accordance with applicable laws and regulations. Must be 21 years of age or older to purchase.',
		'vitalpeptides'
	);
}

/**
 * The research-use agreement the buyer must accept at checkout.
 */
function vp_research_use_agreement_text() {
	return __(
		'I certify that I am at least 21 years old and a qualified researcher. I understand these products are sold strictly for laboratory research use only and are NOT for human or animal consumption. I accept full responsibility for lawful use.',
		'vitalpeptides'
	);
}

/* ==========================================================================
 * 1. 21+ Age verification prompt (site-wide)
 * ==========================================================================
 * A blocking overlay shown until the visitor confirms they are 21+. The
 * choice is stored in localStorage so it is asked once per browser. Never
 * shown in the admin area or to logged-in users who already verified.
 */

add_action( 'wp_enqueue_scripts', 'vp_age_gate_assets', 20 );
function vp_age_gate_assets() {
	$css = '
	.vp-age-gate{position:fixed;inset:0;z-index:99999;display:flex;align-items:center;justify-content:center;padding:24px;background:rgba(8,15,30,.92);backdrop-filter:blur(6px);}
	.vp-age-gate[hidden]{display:none;}
	.vp-age-gate__box{max-width:460px;width:100%;background:#fff;border-radius:20px;padding:40px 32px;text-align:center;box-shadow:0 30px 80px rgba(0,0,0,.4);}
	.vp-age-gate__logo{height:40px;margin:0 auto 20px;display:block;}
	.vp-age-gate__box h2{font-size:1.6rem;font-weight:800;margin:0 0 8px;color:#0f1b30;}
	.vp-age-gate__box p{font-size:.95rem;line-height:1.55;color:#4a5568;margin:0 0 24px;}
	.vp-age-gate__actions{display:flex;gap:12px;flex-direction:column;}
	.vp-age-gate__btn{padding:14px 20px;border-radius:12px;font-weight:700;font-size:1rem;cursor:pointer;border:none;width:100%;}
	.vp-age-gate__btn--yes{background:linear-gradient(135deg,#0ea5a4,#0d9488);color:#fff;}
	.vp-age-gate__btn--no{background:#f1f5f9;color:#334155;}
	.vp-age-gate__fine{margin-top:18px;font-size:.75rem;color:#94a3b8;line-height:1.5;}
	body.vp-age-locked{overflow:hidden;}

	/* Global footer disclaimer */
	.vp-footer-disclaimer{border-top:1px solid rgba(255,255,255,.12);margin-top:8px;padding:20px 0 4px;}
	.vp-footer-disclaimer p{font-size:.72rem;line-height:1.65;color:rgba(255,255,255,.55);margin:0;max-width:1000px;}

	/* Footer business contact / DBA block */
	.vp-footer-contact{font-style:normal;margin-top:16px;display:flex;flex-direction:column;gap:8px;}
	.vp-footer-contact span{display:flex;align-items:flex-start;gap:8px;font-size:.82rem;color:rgba(255,255,255,.7);line-height:1.45;}
	.vp-footer-contact a{color:rgba(255,255,255,.7);text-decoration:none;}
	.vp-footer-contact a:hover{color:#fff;}
	.vp-footer-contact .vp-icon{flex-shrink:0;width:16px;height:16px;margin-top:1px;}

	/* Product specification table */
	.vp-spec{margin-top:8px;}
	.vp-spec-title{font-size:1.15rem;font-weight:700;margin:0 0 14px;color:#0f1b30;}
	.vp-spec-table{width:100%;border-collapse:collapse;font-size:.92rem;}
	.vp-spec-table th,.vp-spec-table td{text-align:left;padding:11px 14px;border:1px solid #e2e8f0;vertical-align:top;}
	.vp-spec-table th{width:40%;background:#f8fafc;font-weight:600;color:#334155;}
	.vp-spec-table td{color:#0f1b30;}
	.vp-spec-ruo{margin-top:14px;font-size:.78rem;color:#64748b;font-style:italic;line-height:1.55;}
	';
	wp_register_style( 'vp-age-gate', false );
	wp_enqueue_style( 'vp-age-gate' );
	wp_add_inline_style( 'vp-age-gate', $css );

	$js = '
	(function(){
		var KEY="vp_age_verified_v1";
		if(localStorage.getItem(KEY)==="1"){return;}
		document.addEventListener("DOMContentLoaded",function(){
			var gate=document.getElementById("vp-age-gate");
			if(!gate){return;}
			gate.hidden=false;
			document.body.classList.add("vp-age-locked");
			gate.querySelector(".vp-age-gate__btn--yes").addEventListener("click",function(){
				localStorage.setItem(KEY,"1");
				gate.hidden=true;
				document.body.classList.remove("vp-age-locked");
			});
			gate.querySelector(".vp-age-gate__btn--no").addEventListener("click",function(){
				window.location.href="https://www.google.com";
			});
		});
	})();';
	wp_add_inline_script( 'vp-main', $js );
}

/**
 * Age-gate markup (printed in the footer).
 */
add_action( 'wp_footer', 'vp_age_gate_markup', 5 );
function vp_age_gate_markup() {
	if ( is_admin() ) {
		return;
	}
	?>
	<div id="vp-age-gate" class="vp-age-gate" hidden role="dialog" aria-modal="true" aria-labelledby="vp-age-gate-title">
		<div class="vp-age-gate__box">
			<img class="vp-age-gate__logo" src="<?php echo esc_url( VP_THEME_URI . '/assets/images/LogoMain.svg' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<h2 id="vp-age-gate-title"><?php esc_html_e( 'Age Verification', 'vitalpeptides' ); ?></h2>
			<p><?php esc_html_e( 'You must be 21 years of age or older to enter this website. All products are for laboratory research use only and are not for human consumption. Please confirm your age to continue.', 'vitalpeptides' ); ?></p>
			<div class="vp-age-gate__actions">
				<button type="button" class="vp-age-gate__btn vp-age-gate__btn--yes"><?php esc_html_e( 'I am 21 or older — Enter', 'vitalpeptides' ); ?></button>
				<button type="button" class="vp-age-gate__btn vp-age-gate__btn--no"><?php esc_html_e( 'I am under 21 — Exit', 'vitalpeptides' ); ?></button>
			</div>
			<p class="vp-age-gate__fine"><?php echo esc_html( vp_footer_disclaimer_text() ); ?></p>
		</div>
	</div>
	<?php
}

/* ==========================================================================
 * 2. Gated checkout — login required, no guest checkout
 * ==========================================================================
 */

// Force-disable guest checkout regardless of the stored option value.
add_filter( 'pre_option_woocommerce_enable_guest_checkout', function () {
	return 'no';
} );

// Encourage account login on the checkout page.
add_filter( 'pre_option_woocommerce_enable_checkout_login_reminder', function () {
	return 'yes';
} );

/**
 * Force checkout to require an account (no guest checkout). With this on,
 * WooCommerce natively shows a login form on the checkout page and blocks
 * order placement for anyone not signed in — the checkout page stays
 * reachable (it is not redirected away), it just cannot be completed by a
 * guest. This is the standard, robust way to fully gate checkout.
 */
add_filter( 'pre_option_woocommerce_enable_signup_and_login_from_checkout', function () {
	return 'no';
} );

// Make the "must be logged in" gate message explicit for researchers.
add_filter( 'woocommerce_checkout_must_be_logged_in_message', function () {
	return __( 'Checkout is restricted to registered, approved researchers. Please sign in to your account to place an order.', 'vitalpeptides' );
} );

/**
 * After logging in from the checkout page, send the researcher back to
 * checkout so they can complete the order.
 */
add_filter( 'woocommerce_login_redirect', 'vp_login_redirect_to_checkout', 10, 1 );
function vp_login_redirect_to_checkout( $redirect ) {
	if ( isset( $_REQUEST['redirect_to'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$target = esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
		if ( $target ) {
			return $target;
		}
	}
	return $redirect;
}

/* ==========================================================================
 * 3. Checkout fields — researcher type + research-use agreement
 * ==========================================================================
 * These use the classic (shortcode) checkout hooks. The Checkout page must use
 * the [woocommerce_checkout] shortcode (see DEPLOYMENT-COMPLIANCE.md).
 */

/**
 * Researcher-type dropdown + field reordering to match the reference checkout
 * (Customer Information: type, name, email, phone → Shipping: address, city,
 * state, ZIP, country). Company field removed.
 */
add_filter( 'woocommerce_checkout_fields', 'vp_add_researcher_type_field' );
function vp_add_researcher_type_field( $fields ) {
	$fields['billing']['vp_researcher_type'] = array(
		'type'     => 'select',
		'label'    => __( 'Researcher / organization type', 'vitalpeptides' ),
		'required' => true,
		'class'    => array( 'form-row-wide' ),
		'priority' => 5,
		'options'  => array(
			''                => __( 'Please select…', 'vitalpeptides' ),
			'academic'        => __( 'Academic / University', 'vitalpeptides' ),
			'institutional'   => __( 'Government / Institutional Laboratory', 'vitalpeptides' ),
			'commercial'      => __( 'Commercial / Industry Laboratory', 'vitalpeptides' ),
			'independent'     => __( 'Independent Researcher', 'vitalpeptides' ),
			'other'           => __( 'Other Research Entity', 'vitalpeptides' ),
		),
	);

	// Reorder to mirror the reference: name → email → phone → address block → country.
	$priorities = array(
		'billing_first_name' => 10,
		'billing_last_name'  => 20,
		'billing_email'      => 30,
		'billing_phone'      => 40,
		'billing_address_1'  => 50,
		'billing_address_2'  => 60,
		'billing_city'       => 70,
		'billing_state'      => 80,
		'billing_postcode'   => 90,
		'billing_country'    => 100,
	);
	foreach ( $priorities as $key => $prio ) {
		if ( isset( $fields['billing'][ $key ] ) ) {
			$fields['billing'][ $key ]['priority'] = $prio;
		}
	}
	// Email + phone required and full-width, phone up with contact info.
	if ( isset( $fields['billing']['billing_email'] ) ) {
		$fields['billing']['billing_email']['class'] = array( 'form-row-wide' );
	}
	if ( isset( $fields['billing']['billing_phone'] ) ) {
		$fields['billing']['billing_phone']['class']    = array( 'form-row-wide' );
		$fields['billing']['billing_phone']['required'] = true;
	}
	// Remove the company field (not in the reference design).
	unset( $fields['billing']['billing_company'] );

	return $fields;
}

/**
 * Move the coupon form out of the top of the page and into the order-summary
 * card (matching the reference), keeping WooCommerce's coupon behaviour, and
 * add the free-shipping progress indicator beneath it.
 */
add_action( 'wp', function () {
	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
		add_action( 'woocommerce_checkout_before_order_review', 'woocommerce_checkout_coupon_form', 10 );
		add_action( 'woocommerce_checkout_before_order_review', 'vp_free_shipping_progress', 20 );
	}
} );

/**
 * Free-shipping threshold (surfaces the store's existing shipping term).
 * Filterable; defaults to 150. Return 0 to hide the progress bar entirely.
 */
function vp_free_shipping_threshold() {
	$threshold = 0;
	if ( class_exists( 'WooCommerce' ) && WC()->cart ) {
		// Try to read a Free Shipping method min amount from the packages' zone.
		$packages = WC()->cart->get_shipping_packages();
		if ( ! empty( $packages ) ) {
			$zone = function_exists( 'wc_get_shipping_zone' ) ? wc_get_shipping_zone( reset( $packages ) ) : null;
			if ( $zone ) {
				foreach ( $zone->get_shipping_methods( true ) as $method ) {
					if ( 'free_shipping' === $method->id && ! empty( $method->min_amount ) ) {
						$threshold = (float) $method->min_amount;
						break;
					}
				}
			}
		}
	}
	if ( ! $threshold ) {
		$threshold = 150;
	}
	return (float) apply_filters( 'vp_free_shipping_threshold', $threshold );
}

/**
 * Render the free-shipping progress bar in the order summary.
 */
function vp_free_shipping_progress() {
  if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) {
    return;
  }
  $threshold = vp_free_shipping_threshold();
  if ( $threshold <= 0 ) {
    return;
  }
  $subtotal = (float) WC()->cart->get_displayed_subtotal();
  $pct      = min( 100, $threshold > 0 ? round( ( $subtotal / $threshold ) * 100 ) : 100 );
  echo '<div class="vp-ship-progress">';
  // vp_icon() returns trusted, hard-coded inline SVG — echo it raw. (wp_kses_post
  // would strip the <svg>, leaving an empty icon span.)
  if ( $subtotal >= $threshold ) {
    echo '<p class="vp-ship-progress-label">' . vp_icon( 'truck' ) . ' <span>' . esc_html__( "You've unlocked FREE Shipping", 'vitalpeptides' ) . '</span></p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
  } else {
    $remaining = wc_price( $threshold - $subtotal );
    echo '<p class="vp-ship-progress-label">' . vp_icon( 'truck' ) . ' <span>' . sprintf(
      /* translators: %s: remaining amount */
      wp_kses_post( __( "You're <strong>%s</strong> away from FREE Shipping", 'vitalpeptides' ) ),
      wp_kses_post( $remaining )
    ) . '</span></p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
  }
  echo '<div class="vp-ship-progress-track"><span style="width:' . esc_attr( $pct ) . '%"></span></div>';
  echo '</div>';
}

/**
 * Relabel the checkout "Billing details" heading to "Customer Information".
 */
add_filter( 'gettext', 'vp_relabel_billing_heading', 20, 3 );
function vp_relabel_billing_heading( $translated, $text, $domain ) {
	if ( 'woocommerce' === $domain && is_checkout() ) {
		if ( 'Billing details' === $text ) {
			return __( 'Customer Information', 'vitalpeptides' );
		}
		if ( 'Apply coupon' === $text ) {
			return __( 'Apply', 'vitalpeptides' );
		}
	}
	return $translated;
}

/**
 * Research-use agreement checkbox, rendered just before the "Place order" area
 * (alongside the built-in Terms & Conditions checkbox).
 */
add_action( 'woocommerce_review_order_before_submit', 'vp_render_research_use_checkbox' );
function vp_render_research_use_checkbox() {
	woocommerce_form_field(
		'vp_research_use_agree',
		array(
			'type'     => 'checkbox',
			'class'    => array( 'form-row', 'vp-checkout-agree' ),
			'label'    => vp_research_use_agreement_text(),
			'required' => true,
		),
		WC()->checkout ? WC()->checkout->get_value( 'vp_research_use_agree' ) : ''
	);
}

/**
 * Server-side validation for the added fields.
 */
add_action( 'woocommerce_checkout_process', 'vp_validate_checkout_compliance' );
function vp_validate_checkout_compliance() {
	if ( empty( $_POST['vp_researcher_type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		wc_add_notice( __( 'Please select your researcher / organization type.', 'vitalpeptides' ), 'error' );
	}
	if ( empty( $_POST['vp_research_use_agree'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		wc_add_notice( __( 'You must confirm the research-use agreement before placing your order.', 'vitalpeptides' ), 'error' );
	}
}

/**
 * Persist the compliance data on the order.
 */
add_action( 'woocommerce_checkout_create_order', 'vp_save_checkout_compliance', 10, 2 );
function vp_save_checkout_compliance( $order, $data ) {
	if ( ! empty( $_POST['vp_researcher_type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$order->update_meta_data( '_vp_researcher_type', sanitize_text_field( wp_unslash( $_POST['vp_researcher_type'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
	}
	if ( ! empty( $_POST['vp_research_use_agree'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$order->update_meta_data( '_vp_research_use_agree', 'yes' );
	}
}

/**
 * Show the compliance data in the admin order screen.
 */
add_action( 'woocommerce_admin_order_data_after_billing_address', 'vp_admin_show_checkout_compliance' );
function vp_admin_show_checkout_compliance( $order ) {
	$type  = $order->get_meta( '_vp_researcher_type' );
	$agree = $order->get_meta( '_vp_research_use_agree' );
	if ( $type || $agree ) {
		echo '<p><strong>' . esc_html__( 'Researcher type', 'vitalpeptides' ) . ':</strong> ' . esc_html( $type ? $type : '—' ) . '<br>';
		echo '<strong>' . esc_html__( 'Research-use agreement', 'vitalpeptides' ) . ':</strong> ' . esc_html( 'yes' === $agree ? __( 'Accepted', 'vitalpeptides' ) : __( 'Not recorded', 'vitalpeptides' ) ) . '</p>';
	}
}

/* ==========================================================================
 * 4. Remove product reviews / ratings (treated as testimonials)
 * ==========================================================================
 */
add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
	unset( $tabs['reviews'] );
	return $tabs;
}, 98 );

// Disable the review comment form / rating stars support, site-wide.
add_filter( 'woocommerce_product_review_comment_form_args', '__return_empty_array' );
add_filter( 'wc_product_enable_reviews', '__return_false' );
add_filter( 'pre_option_woocommerce_enable_reviews', function () {
	return 'no';
} );

/* ==========================================================================
 * 5. Prohibited products — safety net
 * ==========================================================================
 * Products whose names match prohibited categories are hidden from the shop,
 * search, and REST even if they remain published. The authoritative removal is
 * setting them to Draft (see deployment guide); this is defence in depth.
 */
function vp_prohibited_name_patterns() {
	return array(
		'alcohol wipe',
		'bac water',
		'bacteriostatic',
		'hcg',
		'hgh',
		'human growth hormone',
		'injection',
		'syringe',
		'needle',
		'nasal spray',
		'tablet',
	);
}

/**
 * True when a product name matches a prohibited pattern.
 */
function vp_is_prohibited_product_name( $name ) {
	$name = strtolower( $name );
	foreach ( vp_prohibited_name_patterns() as $pattern ) {
		if ( false !== strpos( $name, $pattern ) ) {
			return true;
		}
	}
	return false;
}

add_action( 'pre_get_posts', 'vp_hide_prohibited_products' );
function vp_hide_prohibited_products( $q ) {
	if ( is_admin() || ! $q->is_main_query() ) {
		return;
	}
	if ( 'product' !== $q->get( 'post_type' ) && ! ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) ) {
		return;
	}
	// Exclude by title match.
	$excluded = get_transient( 'vp_prohibited_ids' );
	if ( false === $excluded ) {
		$excluded = array();
		$all      = get_posts( array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'post_status'    => 'publish',
		) );
		foreach ( $all as $pid ) {
			if ( vp_is_prohibited_product_name( get_the_title( $pid ) ) ) {
				$excluded[] = $pid;
			}
		}
		set_transient( 'vp_prohibited_ids', $excluded, HOUR_IN_SECONDS );
	}
	if ( ! empty( $excluded ) ) {
		$q->set( 'post__not_in', array_merge( (array) $q->get( 'post__not_in' ), $excluded ) );
	}
}

/* ==========================================================================
 * 6. Product scientific specification fields
 * ==========================================================================
 * Adds admin fields under Product data → General and renders a clean
 * specification table on the single-product page (no therapeutic content).
 */

/**
 * Definition of the scientific spec fields.
 */
function vp_spec_fields() {
	return array(
		'cas_number'         => __( 'CAS Number', 'vitalpeptides' ),
		'molecular_formula'  => __( 'Molecular Formula', 'vitalpeptides' ),
		'molecular_weight'   => __( 'Molecular Weight (g/mol)', 'vitalpeptides' ),
		'sci_purity'         => __( 'Purity', 'vitalpeptides' ),
		'storage_conditions' => __( 'Storage Conditions', 'vitalpeptides' ),
		'batch_number'       => __( 'Batch / Lot Number', 'vitalpeptides' ),
		'analysis_details'   => __( 'Analysis Details (COA)', 'vitalpeptides' ),
	);
}

/**
 * Default (generic, non-therapeutic) values used when a field is empty.
 * CAS / formula / MW / batch are intentionally left blank so no inaccurate
 * chemical data is ever displayed — the client populates these per product.
 */
function vp_spec_default( $key ) {
	$defaults = array(
		'sci_purity'         => __( '≥98% (HPLC)', 'vitalpeptides' ),
		'storage_conditions' => __( 'Store lyophilized powder at -20°C, protected from light. After reconstitution, store at 2–8°C.', 'vitalpeptides' ),
		'analysis_details'   => __( 'HPLC and Mass Spectrometry. Certificate of Analysis available on request.', 'vitalpeptides' ),
	);
	return isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
}

/**
 * Get a spec value (meta, falling back to a safe default).
 */
function vp_get_spec( $product, $key ) {
	$val = $product->get_meta( '_vp_' . $key, true );
	if ( '' === $val || null === $val ) {
		$val = vp_spec_default( $key );
	}
	return $val;
}

// Admin: render fields in the Product data → General panel.
add_action( 'woocommerce_product_options_general_product_data', 'vp_render_spec_fields' );
function vp_render_spec_fields() {
	echo '<div class="options_group">';
	echo '<p class="form-field"><strong>' . esc_html__( 'Scientific Specifications', 'vitalpeptides' ) . '</strong></p>';
	foreach ( vp_spec_fields() as $key => $label ) {
		woocommerce_wp_text_input( array(
			'id'          => '_vp_' . $key,
			'label'       => $label,
			'desc_tip'    => true,
			'description' => __( 'Displayed in the product specification table.', 'vitalpeptides' ),
		) );
	}
	echo '</div>';
}

// Admin: save fields.
add_action( 'woocommerce_process_product_meta', 'vp_save_spec_fields' );
function vp_save_spec_fields( $post_id ) {
	foreach ( vp_spec_fields() as $key => $label ) {
		$field = '_vp_' . $key;
		if ( isset( $_POST[ $field ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
		}
	}
}

/**
 * Render the scientific specification table for a product.
 * Rows with no value (and no default) are omitted.
 */
function vp_render_spec_table( $product ) {
	$rows = array();
	foreach ( vp_spec_fields() as $key => $label ) {
		$val = vp_get_spec( $product, $key );
		if ( '' !== $val ) {
			$rows[ $label ] = $val;
		}
	}
	if ( empty( $rows ) ) {
		return;
	}
	echo '<div class="vp-spec">';
	echo '<h2 class="vp-spec-title">' . esc_html__( 'Product Specifications', 'vitalpeptides' ) . '</h2>';
	echo '<table class="vp-spec-table"><tbody>';
	foreach ( $rows as $label => $val ) {
		echo '<tr><th scope="row">' . esc_html( $label ) . '</th><td>' . esc_html( $val ) . '</td></tr>';
	}
	echo '</tbody></table>';
	echo '<p class="vp-spec-ruo">' . esc_html__( 'For laboratory research use only. Not for human or animal consumption. Not a drug, food, or supplement.', 'vitalpeptides' ) . '</p>';
	echo '</div>';
}

/* ==========================================================================
 * 7. Neutralise therapeutic category labels on the front-end
 * ==========================================================================
 * Renames the displayed category names to neutral scientific labels without
 * changing slugs/URLs. Adjust the map to taste.
 */
function vp_category_label_map() {
	return array(
		'recovery'  => __( 'Peptides', 'vitalpeptides' ),
		'glp'        => __( 'Peptides', 'vitalpeptides' ),
		'growth'     => __( 'Peptides', 'vitalpeptides' ),
		'metabolic'  => __( 'Peptides', 'vitalpeptides' ),
		'longevity'  => __( 'Peptides', 'vitalpeptides' ),
		'support'    => __( 'Research Support', 'vitalpeptides' ),
	);
}

/**
 * Move the coupon block + free-shipping progress bar to BELOW the product/
 * totals summary — done in JS so it survives WooCommerce's AJAX order-review
 * refresh and works with any (dynamic) set of products. Idempotent.
 */
add_action( 'wp_enqueue_scripts', 'vp_checkout_reorder_summary', 30 );
function vp_checkout_reorder_summary() {
  if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
    return;
  }
  $js = <<<'JS'
(function(){
  // Coupon + free-shipping blocks (rendered by PHP as direct children of the card).
  var COUPON = ['.woocommerce-form-coupon-toggle','.form-row-first','.form-row-last','.clear','.vp-ship-progress'];
  function relocate(){
    var card = document.querySelector('.vp-checkout-summary-card');
    if(!card){ return; }
    var review = card.querySelector('#order_review');
    if(!review){ return; }
    var table = review.querySelector('.woocommerce-checkout-review-order-table');
    if(!table){ return; }

    // 1) Coupon + progress wrapper — kept OUTSIDE the review table so WooCommerce's
    //    AJAX refresh (which replaces that table) never destroys the coupon field.
    var wrap = document.getElementById('vp-summary-extras');
    if(!wrap){
      wrap = document.createElement('div');
      wrap.id = 'vp-summary-extras';
      wrap.className = 'vp-summary-extras';
      wrap.style.margin = '6px 0';
    }
    COUPON.forEach(function(sel){
      Array.prototype.filter.call(card.children, function(el){
        return el.matches && el.matches(sel);
      }).forEach(function(el){ wrap.appendChild(el); });
    });

    // 2) Totals table — move the review table's <tfoot> (subtotal/shipping/total)
    //    into its own table so the coupon can sit BETWEEN products and totals.
    //    Re-synced on every refresh (WooCommerce re-renders the <tfoot> each time).
    var totals = document.getElementById('vp-order-totals');
    if(!totals){
      totals = document.createElement('table');
      totals.id = 'vp-order-totals';
      totals.className = 'shop_table vp-order-totals-table';
      totals.style.width = '100%';
    }
    var freshTfoot = table.querySelector('tfoot');
    if(freshTfoot){
      var oldTfoot = totals.querySelector('tfoot');
      if(oldTfoot){ oldTfoot.remove(); }
      totals.appendChild(freshTfoot);
    }

    // 3) Final order inside #order_review: products table → coupon → totals.
    review.appendChild(table);
    if(wrap.children.length){ review.appendChild(wrap); }
    if(totals.querySelector('tfoot')){ review.appendChild(totals); }
  }
  if(document.readyState === 'loading'){ document.addEventListener('DOMContentLoaded', relocate); }
  else { relocate(); }
  if(window.jQuery){ window.jQuery(document.body).on('updated_checkout', relocate); }
})();
JS;
  wp_add_inline_script( 'vp-main', $js );
}