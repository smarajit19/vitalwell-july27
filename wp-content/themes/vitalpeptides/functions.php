<?php
/**
 * Vital Peptides theme functions.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

define( 'VP_THEME_VERSION', '1.1.6' );
define( 'VP_THEME_URI', get_template_directory_uri() );

/* --------------------------------------------------------------------------
 * Compliance module (age gate, gated checkout, disclaimers, product specs).
 * ------------------------------------------------------------------------ */
require_once get_template_directory() . '/inc/compliance.php';
require_once get_template_directory() . '/inc/auth.php';
require_once get_template_directory() . '/inc/email-otp.php';

/* --------------------------------------------------------------------------
 * Setup
 * ------------------------------------------------------------------------ */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

	// WooCommerce.
	add_theme_support( 'woocommerce' );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'vitalpeptides' ),
	) );
} );

/* --------------------------------------------------------------------------
 * Assets
 * ------------------------------------------------------------------------ */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'vp-inter', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap', array(), null );

	wp_enqueue_style( 'vp-main', VP_THEME_URI . '/assets/css/main.css',	array(), filemtime( get_template_directory() . '/assets/css/main.css' )
	);
	wp_enqueue_style( 'vp-style', get_stylesheet_uri(), array( 'vp-main' ), VP_THEME_VERSION );

	wp_enqueue_script( 'vp-main', VP_THEME_URI . '/assets/js/main.js', array( 'jquery' ), VP_THEME_VERSION, true );

	wp_localize_script( 'vp-main', 'vpData', array(
		'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
		'wcAjaxUrl'   => class_exists( 'WooCommerce' ) ? WC_AJAX::get_endpoint( '%%endpoint%%' ) : '',
		'checkoutUrl' => class_exists( 'WooCommerce' ) ? wc_get_checkout_url() : '',
		'nonce'       => wp_create_nonce( 'vp_nonce' ),
	) );
} );

/* --------------------------------------------------------------------------
 * Helpers
 * ------------------------------------------------------------------------ */

/**
 * Product meta helper (dosage, purity, badge, member price, rating).
 */
function vp_product_meta( $product, $key, $default = '' ) {
	$value = $product->get_meta( '_vp_' . $key, true );
	return '' !== $value && null !== $value ? $value : $default;
}

/**
 * Star rating markup (uses imported meta rating, falls back to WC rating).
 */
function vp_star_rating( $rating, $size = 'sm' ) {
	$out = '<span class="vp-stars vp-stars--' . esc_attr( $size ) . '">';
	for ( $i = 1; $i <= 5; $i++ ) {
		$out .= '<svg class="vp-star ' . ( $i <= floor( (float) $rating ) ? 'is-filled' : '' ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" fill="currentColor" stroke="none"/></svg>';
	}
	return $out . '</span>';
}

/**
 * Rendered product card — replicates ProductCard.tsx.
 */
function vp_product_card( $product ) {
	if ( ! $product instanceof WC_Product ) {
		$product = wc_get_product( $product );
	}
	if ( ! $product ) {
		return;
	}
	$badge   = vp_product_meta( $product, 'badge' );
	$purity  = vp_product_meta( $product, 'purity' );
	$dosage  = vp_product_meta( $product, 'dosage' );
	?>
	<div class="vp-product-card fade-in-section">
		<?php if ( $badge ) : ?>
			<span class="vp-card-badge"><?php echo esc_html( $badge ); ?></span>
		<?php endif; ?>
		<?php if ( $purity ) : ?>
			<span class="vp-card-purity"><?php echo esc_html( $purity ); ?></span>
		<?php endif; ?>

		<a class="vp-card-image" href="<?php echo esc_url( $product->get_permalink() ); ?>" aria-label="<?php echo esc_attr( $product->get_name() ); ?>">
			<?php echo vp_molecule_svg(); // phpcs:ignore ?>
			<?php echo $product->get_image( 'woocommerce_thumbnail', array( 'class' => 'vp-card-img' ) ); // phpcs:ignore ?>
		</a>

		<div class="vp-card-content">
			<div>
				<h3 class="vp-card-name"><?php echo esc_html( $product->get_name() ); ?></h3>
				<p class="vp-card-sub">
					<?php if ( $dosage ) : ?><span><?php echo esc_html( $dosage ); ?> · </span><?php endif; ?>
					<?php esc_html_e( 'Research Chemical', 'vitalpeptides' ); ?>
				</p>
			</div>

			<div class="vp-card-trust">
				<div class="vp-card-trust-item"><?php echo vp_icon( 'shield' ); // phpcs:ignore ?><span>99% HPLC</span></div>
				<div class="vp-card-trust-item"><?php echo vp_icon( 'flask' ); // phpcs:ignore ?><span>3rd Party Tested</span></div>
				<div class="vp-card-trust-item"><?php echo vp_icon( 'snowflake' ); // phpcs:ignore ?><span>Lyophilized</span></div>
				<div class="vp-card-trust-item"><?php echo vp_icon( 'truck' ); // phpcs:ignore ?><span>Fast Shipping</span></div>
			</div>

			<div class="vp-card-footer">
				<div class="vp-card-price"><span class="vp-card-from">From</span><p><?php echo wp_kses_post( wc_price( $product->get_price() ) ); ?></p></div>
				<div class="vp-card-actions">
					<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="vp-btn-card vp-btn-card--outline"><?php echo vp_icon( 'eye' ); // phpcs:ignore ?>View Details</a>
					<button type="button" class="vp-btn-card vp-btn-card--dark vp-add-to-cart" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"><?php echo vp_icon( 'cart-sm' ); // phpcs:ignore ?>Add to Cart</button>
				</div>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Inline SVG icon set (Lucide-equivalent strokes used across the design).
 */
function vp_icon( $name ) {
	$icons = array(
		'shield'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>',
		'flask'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 2v7.527a2 2 0 0 1-.211.896L4.72 20.55a1 1 0 0 0 .9 1.45h12.76a1 1 0 0 0 .9-1.45l-5.069-10.127A2 2 0 0 1 14 9.527V2"/><path d="M8.5 2h7"/><path d="M7 16h10"/></svg>',
		'snowflake' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="2" x2="22" y1="12" y2="12"/><line x1="12" x2="12" y1="2" y2="22"/><path d="m20 16-4-4 4-4"/><path d="m4 8 4 4-4 4"/><path d="m16 4-4 4-4-4"/><path d="m8 20 4-4 4 4"/></svg>',
		'truck'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>',
		'eye'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>',
		'cart-sm'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>',
		'check-circle' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>',
		'file'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>',
		'lock'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
		'clock'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
		'zap'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/></svg>',
		'refresh'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>',
		'users'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
		'headphones'=> '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 18 0v7a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3"/></svg>',
		'book'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 7v14"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/></svg>',
		'package'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/><path d="M12 22V12"/><path d="m3.3 7 8.7 5 8.7-5"/></svg>',
		'percent'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" x2="5" y1="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>',
		'globe'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>',
		'star'      => '<svg viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
		'search'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>',
		'chevron-down' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>',
		'chevron-left' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>',
		'chevron-right' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>',
		'plus'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>',
		'minus'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>',
		'trash'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>',
		'x'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>',
		'bag'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
		'arrow-left'=> '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>',
		'mail'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>',
		'phone'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
		'map-pin'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>',
		'alert'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>',
		'scale'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1"/><path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1"/><path d="M7 21h10"/><path d="M12 3v18"/><path d="M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2"/></svg>',
		'microscope'=> '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18h8"/><path d="M3 22h18"/><path d="M14 22a7 7 0 1 0 0-14h-1"/><path d="M9 14h2"/><path d="M9 12a2 2 0 0 1-2-2V6h6v4a2 2 0 0 1-2 2Z"/><path d="M12 6V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3"/></svg>',
		'brain'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5a3 3 0 1 0-5.997.125 4 4 0 0 0-2.526 5.77 4 4 0 0 0 .556 6.588A4 4 0 1 0 12 18Z"/><path d="M12 5a3 3 0 1 1 5.997.125 4 4 0 0 1 2.526 5.77 4 4 0 0 1-.556 6.588A4 4 0 1 1 12 18Z"/><path d="M15 13a4.5 4.5 0 0 1-3-4 4.5 4.5 0 0 1-3 4"/></svg>',
		'dna'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m10 16 1.5 1.5"/><path d="m14 8-1.5-1.5"/><path d="M15 2c-1.798 1.998-2.518 3.995-2.807 5.993"/><path d="m16.5 10.5 1 1"/><path d="m17 6-2.891-2.891"/><path d="M2 15c6.667-6 13.333 0 20-6"/><path d="m20 9 .891.891"/><path d="M3.109 14.109 4 15"/><path d="m6.5 12.5 1 1"/><path d="m7 18 2.891 2.891"/><path d="M9 22c1.798-1.998 2.518-3.995 2.807-5.993"/></svg>',
		'building'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>',
		'trending'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>',
		'dollar'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
		'bar-chart' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/></svg>',
		'gift'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/></svg>',
		'rotate'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>',
		'x-circle'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>',
		'target'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>',
		'beaker'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 3h15"/><path d="M6 3v16a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V3"/><path d="M6 14h12"/></svg>',
		'info'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>',
		'thumbs-up' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2a3.13 3.13 0 0 1 3 3.88Z"/></svg>',
		'user'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
		'badge-check' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/></svg>',
	);
	return isset( $icons[ $name ] ) ? '<span class="vp-icon vp-icon--' . esc_attr( $name ) . '">' . $icons[ $name ] . '</span>' : '';
}

/**
 * Molecular pattern SVG background used inside product card image areas.
 */
function vp_molecule_svg() {
	return '<svg class="vp-molecule-bg" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">'
		. '<circle cx="60" cy="80" r="8" stroke="currentColor" stroke-width="1.5"/><circle cx="120" cy="50" r="6" stroke="currentColor" stroke-width="1.5"/><circle cx="100" cy="120" r="10" stroke="currentColor" stroke-width="1.5"/>'
		. '<line x1="66" y1="76" x2="114" y2="54" stroke="currentColor"/><line x1="66" y1="86" x2="94" y2="114" stroke="currentColor"/>'
		. '<circle cx="280" cy="60" r="7" stroke="currentColor" stroke-width="1.5"/><circle cx="340" cy="90" r="9" stroke="currentColor" stroke-width="1.5"/><circle cx="310" cy="140" r="6" stroke="currentColor" stroke-width="1.5"/>'
		. '<line x1="286" y1="64" x2="334" y2="86" stroke="currentColor"/><line x1="336" y1="96" x2="314" y2="136" stroke="currentColor"/>'
		. '<circle cx="200" cy="300" r="8" stroke="currentColor" stroke-width="1.5"/><circle cx="150" cy="340" r="6" stroke="currentColor" stroke-width="1.5"/><circle cx="260" cy="350" r="7" stroke="currentColor" stroke-width="1.5"/>'
		. '<line x1="194" y1="306" x2="156" y2="336" stroke="currentColor"/><line x1="206" y1="306" x2="254" y2="346" stroke="currentColor"/>'
		. '<circle cx="40" cy="240" r="5" stroke="currentColor" stroke-width="1.5"/><circle cx="80" cy="220" r="5" stroke="currentColor" stroke-width="1.5"/><circle cx="120" cy="240" r="5" stroke="currentColor" stroke-width="1.5"/><circle cx="160" cy="220" r="5" stroke="currentColor" stroke-width="1.5"/>'
		. '<line x1="45" y1="238" x2="75" y2="224" stroke="currentColor"/><line x1="85" y1="222" x2="115" y2="238" stroke="currentColor"/><line x1="125" y1="238" x2="155" y2="224" stroke="currentColor"/>'
		. '<circle cx="320" cy="260" r="5" stroke="currentColor" stroke-width="1.5"/><circle cx="360" cy="240" r="5" stroke="currentColor" stroke-width="1.5"/><line x1="325" y1="258" x2="355" y2="244" stroke="currentColor"/>'
		. '</svg>';
}

/* --------------------------------------------------------------------------
 * WooCommerce tweaks
 * ------------------------------------------------------------------------ */

// Remove default Woo wrappers/hooks we replace with custom templates.
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

// Cart count + drawer fragments (keeps drawer and badge in sync after AJAX add-to-cart).
add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) {
	ob_start();
	vp_cart_count_badge();
	$fragments['.vp-cart-count-wrap'] = ob_get_clean();

	ob_start();
	vp_cart_drawer_content();
	$fragments['#vp-drawer-body'] = ob_get_clean();

	return $fragments;
} );

/**
 * Cart count badge.
 */
function vp_cart_count_badge() {
	$count = ( class_exists( 'WooCommerce' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
	echo '<span class="vp-cart-count-wrap">';
	if ( $count > 0 ) {
		echo '<span class="vp-cart-count">' . esc_html( $count ) . '</span>';
	}
	echo '</span>';
}

/**
 * Cart drawer inner content (items, suggestions, footer) — replicates CartDrawer.tsx.
 */
function vp_cart_drawer_content() {
	if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) {
		return;
	}
	$cart = WC()->cart;
	?>
	<div id="vp-drawer-body">
		<div class="vp-drawer-scroll">
			<?php if ( $cart->is_empty() ) : ?>
				<div class="vp-drawer-empty">
					<?php echo vp_icon( 'bag' ); // phpcs:ignore ?>
					<p class="vp-drawer-empty-title"><?php esc_html_e( 'Your cart is empty', 'vitalpeptides' ); ?></p>
					<p class="vp-drawer-empty-sub"><?php esc_html_e( 'Add items to get started', 'vitalpeptides' ); ?></p>
				</div>
			<?php else : ?>
				<div class="vp-drawer-items">
					<?php foreach ( $cart->get_cart() as $key => $item ) :
						$product = $item['data'];
						if ( ! $product || ! $product->exists() ) { continue; }
						$dosage = vp_product_meta( $product, 'dosage' );
						?>
						<div class="vp-drawer-item" data-cart-key="<?php echo esc_attr( $key ); ?>">
							<div class="vp-drawer-item-img"><?php echo $product->get_image( 'woocommerce_gallery_thumbnail' ); // phpcs:ignore ?></div>
							<div class="vp-drawer-item-info">
								<h3><?php echo esc_html( $product->get_name() ); ?></h3>
								<?php if ( $dosage ) : ?><span class="vp-drawer-item-dosage"><?php echo esc_html( $dosage ); ?></span><?php endif; ?>
								<p class="vp-drawer-item-price"><?php echo wp_kses_post( wc_price( $product->get_price() ) ); ?></p>
								<div class="vp-drawer-item-qty">
									<button type="button" class="vp-qty-btn vp-drawer-minus" aria-label="<?php esc_attr_e( 'Decrease quantity', 'vitalpeptides' ); ?>"><?php echo vp_icon( 'minus' ); // phpcs:ignore ?></button>
									<span class="vp-drawer-qty"><?php echo esc_html( $item['quantity'] ); ?></span>
									<button type="button" class="vp-qty-btn vp-drawer-plus" aria-label="<?php esc_attr_e( 'Increase quantity', 'vitalpeptides' ); ?>"><?php echo vp_icon( 'plus' ); // phpcs:ignore ?></button>
									<button type="button" class="vp-drawer-remove" aria-label="<?php esc_attr_e( 'Remove item', 'vitalpeptides' ); ?>"><?php echo vp_icon( 'trash' ); // phpcs:ignore ?></button>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php
			// "You might also like" — up to 4 random products not in cart.
			$cart_ids = array();
			foreach ( $cart->get_cart() as $item ) {
				$cart_ids[] = $item['product_id'];
			}
			$suggestions = wc_get_products( array(
				'limit'   => 4,
				'orderby' => 'rand',
				'exclude' => $cart_ids,
				'status'  => 'publish',
			) );
			if ( $suggestions ) :
				?>
				<div class="vp-drawer-suggestions">
					<h3><?php esc_html_e( 'You might also like', 'vitalpeptides' ); ?></h3>
					<div class="vp-drawer-suggestion-grid">
						<?php foreach ( $suggestions as $sp ) : ?>
							<div class="vp-drawer-suggestion">
								<div class="vp-drawer-suggestion-img"><?php echo $sp->get_image( 'woocommerce_gallery_thumbnail' ); // phpcs:ignore ?></div>
								<span class="vp-drawer-suggestion-name"><?php echo esc_html( $sp->get_name() ); ?></span>
								<?php $sd = vp_product_meta( $sp, 'dosage' ); if ( $sd ) : ?><span class="vp-drawer-suggestion-dosage"><?php echo esc_html( $sd ); ?></span><?php endif; ?>
								<span class="vp-drawer-suggestion-price"><?php echo wp_kses_post( wc_price( $sp->get_price() ) ); ?></span>
								<button type="button" class="vp-drawer-suggestion-add vp-add-to-cart" data-product-id="<?php echo esc_attr( $sp->get_id() ); ?>"><?php echo vp_icon( 'plus' ); // phpcs:ignore ?><?php esc_html_e( 'Add', 'vitalpeptides' ); ?></button>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( ! $cart->is_empty() ) : ?>
			<div class="vp-drawer-footer">
				<div class="vp-drawer-subtotal">
					<span><?php esc_html_e( 'Subtotal', 'vitalpeptides' ); ?></span>
					<strong><?php echo wp_kses_post( wc_price( $cart->get_subtotal() ) ); ?></strong>
				</div>
				<?php
				$vp_checkout_url = wc_get_checkout_url();
				$vp_checkout_url = ! is_user_logged_in() && function_exists( 'vp_auth_url' )
					? vp_auth_url( 'signup', $vp_checkout_url )
					: $vp_checkout_url;
				?>
				<a href="<?php echo esc_url( $vp_checkout_url ); ?>" class="vp-btn vp-btn--primary vp-drawer-checkout"><?php esc_html_e( 'Checkout', 'vitalpeptides' ); ?></a>
				<p class="vp-drawer-shipping-note"><?php esc_html_e( 'Free shipping on orders over $150', 'vitalpeptides' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

/* --------------------------------------------------------------------------
 * AJAX: cart quantity update / remove (drawer controls)
 * ------------------------------------------------------------------------ */
add_action( 'wp_ajax_vp_update_cart_item', 'vp_ajax_update_cart_item' );
add_action( 'wp_ajax_nopriv_vp_update_cart_item', 'vp_ajax_update_cart_item' );
function vp_ajax_update_cart_item() {
	check_ajax_referer( 'vp_nonce', 'nonce' );
	$key = isset( $_POST['cart_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_key'] ) ) : '';
	$qty = isset( $_POST['quantity'] ) ? (int) $_POST['quantity'] : 0;

	if ( $key && WC()->cart->get_cart_item( $key ) ) {
		if ( $qty <= 0 ) {
			WC()->cart->remove_cart_item( $key );
		} else {
			WC()->cart->set_quantity( $key, $qty );
		}
	}
	WC_AJAX::get_refreshed_fragments();
}

/* --------------------------------------------------------------------------
 * AJAX: newsletter signup (homepage CTA section)
 * ------------------------------------------------------------------------ */
add_action( 'wp_ajax_vp_newsletter', 'vp_ajax_newsletter' );
add_action( 'wp_ajax_nopriv_vp_newsletter', 'vp_ajax_newsletter' );
function vp_ajax_newsletter() {
	check_ajax_referer( 'vp_nonce', 'nonce' );
	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Please enter a valid email address', 'vitalpeptides' ) ) );
	}
	$subscribers = get_option( 'vp_newsletter_subscribers', array() );
	if ( in_array( $email, $subscribers, true ) ) {
		wp_send_json_success( array( 'message' => __( "You're already subscribed!", 'vitalpeptides' ) ) );
	}
	$subscribers[] = $email;
	update_option( 'vp_newsletter_subscribers', $subscribers );
	wp_send_json_success( array( 'message' => __( 'Successfully subscribed! 🎉', 'vitalpeptides' ) ) );
}

/* --------------------------------------------------------------------------
 * Page hero helper (dark navy hero used by secondary pages)
 * ------------------------------------------------------------------------ */
function vp_page_hero( $title, $subtitle = '', $badge_icon = '', $badge_text = '', $radial = false ) {
	?>
	<section class="vp-page-hero<?php echo $radial ? ' vp-page-hero--radial' : ''; ?>">
		<div class="vp-container vp-page-hero-inner">
			<?php if ( $badge_text ) : ?>
				<div class="vp-hero-badge"><?php echo vp_icon( $badge_icon ); // phpcs:ignore ?><?php echo esc_html( $badge_text ); ?></div>
			<?php endif; ?>
			<h1><?php echo esc_html( $title ); ?></h1>
			<?php if ( $subtitle ) : ?><p><?php echo esc_html( $subtitle ); ?></p><?php endif; ?>
		</div>
	</section>
	<?php
}
