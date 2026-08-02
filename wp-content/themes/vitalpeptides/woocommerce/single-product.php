<?php
/**
 * Single product — replicates pages/ProductDetail.tsx
 * (neumorphic panel, sticky image column, badges, qty + add to cart, content cards, related grid).
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$product = wc_get_product( get_the_ID() );
	if ( ! $product ) {
		continue;
	}

	$vp_shop_url = get_permalink( wc_get_page_id( 'shop' ) );
	$vp_badge    = vp_product_meta( $product, 'badge' );
	$vp_purity   = vp_get_spec( $product, 'sci_purity' );
	$vp_dosage   = vp_product_meta( $product, 'dosage' );
	$vp_member   = vp_product_meta( $product, 'member_price' );
	$vp_price    = (float) $product->get_price();
	?>

	<main class="vp-product-page">
		<div class="vp-container vp-product-container">
			<!-- Breadcrumb -->
			<nav class="vp-breadcrumb">
				<a href="<?php echo esc_url( $vp_shop_url ); ?>"><?php echo vp_icon( 'arrow-left' ); // phpcs:ignore ?><?php esc_html_e( 'Shop', 'vitalpeptides' ); ?></a>
				<span>/</span>
				<span class="vp-breadcrumb-current"><?php echo esc_html( $product->get_name() ); ?> <?php echo esc_html( $vp_dosage ); ?></span>
			</nav>

			<div class="vp-product-panel">
				<div class="vp-product-grid">
					<!-- Image column (sticky on desktop) -->
					<div class="vp-product-media">
						<div class="vp-product-image">
							<?php if ( $vp_badge ) : ?>
								<span class="vp-card-badge"><?php echo esc_html( $vp_badge ); ?></span>
							<?php endif; ?>
							<?php echo $product->get_image( 'woocommerce_single' ); // phpcs:ignore ?>
						</div>

						<div class="vp-product-benefits">
							<div class="vp-neu-tile"><?php echo vp_icon( 'shield' ); // phpcs:ignore ?><span class="vp-neu-tile-title"><?php esc_html_e( '99%+ Purity', 'vitalpeptides' ); ?></span><span class="vp-neu-tile-sub"><?php esc_html_e( 'HPLC Verified', 'vitalpeptides' ); ?></span></div>
							<div class="vp-neu-tile"><?php echo vp_icon( 'file' ); // phpcs:ignore ?><span class="vp-neu-tile-title"><?php esc_html_e( 'COA Included', 'vitalpeptides' ); ?></span><span class="vp-neu-tile-sub"><?php esc_html_e( 'Full Transparency', 'vitalpeptides' ); ?></span></div>
							<div class="vp-neu-tile"><?php echo vp_icon( 'lock' ); // phpcs:ignore ?><span class="vp-neu-tile-title"><?php esc_html_e( 'Secure Checkout', 'vitalpeptides' ); ?></span><span class="vp-neu-tile-sub"><?php esc_html_e( '256-bit SSL', 'vitalpeptides' ); ?></span></div>
						</div>
					</div>

					<!-- Info column -->
					<div class="vp-product-info">
						<div>
							<p class="vp-product-cat"><?php esc_html_e( 'Research Chemical', 'vitalpeptides' ); ?></p>
							<h1 class="vp-product-title"><?php echo esc_html( $product->get_name() ); ?></h1>
							<?php if ( $vp_dosage ) : ?><p class="vp-product-dosage"><?php echo esc_html( $vp_dosage ); ?></p><?php endif; ?>
						</div>

						<div class="vp-product-price">
							<div class="vp-product-price-row">
								<span class="vp-product-price-current"><?php echo wp_kses_post( wc_price( $vp_price ) ); ?></span>
								<span class="vp-product-price-compare"><?php echo wp_kses_post( wc_price( $vp_price * 1.25 ) ); ?></span>
							</div>
							<?php if ( $vp_member ) : ?>
								<p class="vp-product-member-price"><?php esc_html_e( 'Member Price:', 'vitalpeptides' ); ?> <?php echo wp_kses_post( wc_price( $vp_member ) ); ?></p>
							<?php endif; ?>
						</div>

						<div class="vp-product-badges">
							<div class="vp-neu-tile"><?php echo vp_icon( 'shield' ); // phpcs:ignore ?><span class="vp-neu-tile-title"><?php echo esc_html( $vp_purity ); ?></span><span class="vp-neu-tile-sub"><?php esc_html_e( 'Purity', 'vitalpeptides' ); ?></span></div>
							<div class="vp-neu-tile"><?php echo vp_icon( 'flask' ); // phpcs:ignore ?><span class="vp-neu-tile-title"><?php esc_html_e( '3rd Party', 'vitalpeptides' ); ?></span><span class="vp-neu-tile-sub"><?php esc_html_e( 'Lab Tested', 'vitalpeptides' ); ?></span></div>
							<div class="vp-neu-tile"><?php echo vp_icon( 'snowflake' ); // phpcs:ignore ?><span class="vp-neu-tile-title"><?php esc_html_e( 'Lyophilized', 'vitalpeptides' ); ?></span><span class="vp-neu-tile-sub"><?php esc_html_e( 'Powder', 'vitalpeptides' ); ?></span></div>
						</div>

						<div class="vp-product-notes">
							<div class="vp-product-note"><?php echo vp_icon( 'check-circle' ); // phpcs:ignore ?><span><?php esc_html_e( 'Freeze dried powder. Reconstitute before use.', 'vitalpeptides' ); ?></span></div>
							<div class="vp-product-note"><?php echo vp_icon( 'check-circle' ); // phpcs:ignore ?><span><?php esc_html_e( 'Refrigerate after use. Keep frozen for long-term storage.', 'vitalpeptides' ); ?></span></div>
						</div>

						<!-- Qty + Add to cart -->
						<div class="vp-product-buy" id="vp-buy-row" data-price="<?php echo esc_attr( $vp_price ); ?>" data-currency="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>">
							<div class="vp-qty-box">
								<button type="button" class="vp-qty-minus" aria-label="<?php esc_attr_e( 'Decrease quantity', 'vitalpeptides' ); ?>"><?php echo vp_icon( 'minus' ); // phpcs:ignore ?></button>
								<span class="vp-qty-value">1</span>
								<button type="button" class="vp-qty-plus" aria-label="<?php esc_attr_e( 'Increase quantity', 'vitalpeptides' ); ?>"><?php echo vp_icon( 'plus' ); // phpcs:ignore ?></button>
							</div>
							<button type="button" class="vp-btn vp-btn--gradient vp-product-add vp-add-to-cart" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
								<?php esc_html_e( 'Add to Cart', 'vitalpeptides' ); ?> — <span class="vp-buy-total"><?php echo wp_kses_post( wc_price( $vp_price ) ); ?></span>
							</button>
						</div>

						<div class="vp-product-shipinfo">
							<div><?php echo vp_icon( 'clock' ); // phpcs:ignore ?><span><?php esc_html_e( 'Ships within 24h', 'vitalpeptides' ); ?></span></div>
							<span class="vp-shipinfo-divider">|</span>
							<div><?php echo vp_icon( 'truck' ); // phpcs:ignore ?><span><?php esc_html_e( 'Free shipping over $150', 'vitalpeptides' ); ?></span></div>
						</div>

						<div class="vp-product-trust-tags">
							<div class="vp-trust-tag"><?php echo vp_icon( 'file' ); // phpcs:ignore ?><span><?php esc_html_e( 'COA Available', 'vitalpeptides' ); ?></span></div>
							<div class="vp-trust-tag"><?php echo vp_icon( 'flask' ); // phpcs:ignore ?><span><?php esc_html_e( 'Lab Verified Batch', 'vitalpeptides' ); ?></span></div>
							<div class="vp-trust-tag"><?php echo vp_icon( 'lock' ); // phpcs:ignore ?><span><?php esc_html_e( 'Secure Checkout', 'vitalpeptides' ); ?></span></div>
						</div>

						<!-- Scientific specification table (chemical / research data only) -->
						<div class="vp-product-content">
							<?php vp_render_spec_table( $product ); ?>
						</div>
					</div>
				</div>
			</div>

			<!-- Related products -->
			<?php
			$vp_related_ids = wc_get_related_products( $product->get_id(), 4 );
			if ( $vp_related_ids ) :
				?>
				<section class="vp-related">
					<h2><?php esc_html_e( 'Related Products', 'vitalpeptides' ); ?></h2>
					<div class="related-grid vp-related-grid">
						<?php foreach ( $vp_related_ids as $vp_rid ) { vp_product_card( $vp_rid ); } ?>
					</div>
				</section>
			<?php endif; ?>
		</div>

		<!-- Sticky mobile add-to-cart bar -->
		<div class="vp-sticky-bar" hidden>
			<div class="vp-qty-box vp-qty-box--bar">
				<button type="button" class="vp-qty-minus" aria-label="<?php esc_attr_e( 'Decrease quantity', 'vitalpeptides' ); ?>"><?php echo vp_icon( 'minus' ); // phpcs:ignore ?></button>
				<span class="vp-qty-value">1</span>
				<button type="button" class="vp-qty-plus" aria-label="<?php esc_attr_e( 'Increase quantity', 'vitalpeptides' ); ?>"><?php echo vp_icon( 'plus' ); // phpcs:ignore ?></button>
			</div>
			<button type="button" class="vp-btn vp-btn--gradient vp-product-add vp-add-to-cart" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
				<?php esc_html_e( 'Add to Cart', 'vitalpeptides' ); ?> — <span class="vp-buy-total"><?php echo wp_kses_post( wc_price( $vp_price ) ); ?></span>
			</button>
		</div>
	</main>

<?php endwhile; ?>

<?php get_footer(); ?>
