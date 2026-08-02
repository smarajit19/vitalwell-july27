<?php
/**
 * Featured peptides carousel — replicates FeaturedPeptidesCarousel.tsx.
 * Pulls first 7 published products, autoplaying scroll-snap carousel with nav arrows.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$vp_featured = wc_get_products( array(
	'limit'   => 7,
	'status'  => 'publish',
	'orderby' => 'menu_order',
	'order'   => 'ASC',
) );

if ( empty( $vp_featured ) ) {
	return;
}
?>
<section class="vp-featured" aria-label="<?php esc_attr_e( 'Featured Peptides', 'vitalpeptides' ); ?>">
	<div class="vp-container">
		<div class="vp-section-head fade-in-section">
			<h2><?php esc_html_e( 'Featured Peptides', 'vitalpeptides' ); ?></h2>
			<p><?php esc_html_e( 'Pharmaceutical-grade research compounds — third-party tested, COA verified, and trusted by thousands.', 'vitalpeptides' ); ?></p>
		</div>

		<div class="vp-carousel-wrap">
			<button type="button" class="vp-carousel-arrow vp-carousel-prev" aria-label="<?php esc_attr_e( 'Previous', 'vitalpeptides' ); ?>" disabled><?php echo vp_icon( 'chevron-left' ); // phpcs:ignore ?></button>
			<button type="button" class="vp-carousel-arrow vp-carousel-next" aria-label="<?php esc_attr_e( 'Next', 'vitalpeptides' ); ?>"><?php echo vp_icon( 'chevron-right' ); // phpcs:ignore ?></button>

			<div class="vp-carousel" data-autoplay="6000">
				<div class="vp-carousel-track">
					<?php foreach ( $vp_featured as $vp_product ) : ?>
						<div class="vp-carousel-item">
							<?php vp_product_card( $vp_product ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>
