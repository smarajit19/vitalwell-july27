<?php
/**
 * Testimonial ticker — replicates TestimonialTicker.tsx (fixed label + infinite scroll).
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

$vp_testimonials = array(
	array( 'text' => __( 'Fast delivery, quality products', 'vitalpeptides' ), 'name' => 'Sarah' ),
	array( 'text' => __( 'Love the whole experience', 'vitalpeptides' ), 'name' => 'Bethany' ),
	array( 'text' => __( 'Amazing quality, every time', 'vitalpeptides' ), 'name' => 'Jake' ),
	array( 'text' => __( "Best peptides I've ever used", 'vitalpeptides' ), 'name' => 'Michael' ),
	array( 'text' => __( 'Customer service is top notch', 'vitalpeptides' ), 'name' => 'Lisa' ),
	array( 'text' => __( 'Purity you can actually trust', 'vitalpeptides' ), 'name' => 'David' ),
	array( 'text' => __( 'My go-to supplier, hands down', 'vitalpeptides' ), 'name' => 'Emily' ),
	array( 'text' => __( 'Consistent results, batch after batch', 'vitalpeptides' ), 'name' => 'Ryan' ),
);
?>
<section class="vp-ticker">
	<div class="vp-ticker-row">
		<div class="vp-ticker-label">
			<div class="vp-ticker-stars">
				<?php for ( $vp_i = 0; $vp_i < 4; $vp_i++ ) : ?><span class="vp-ticker-star"><?php echo vp_icon( 'star' ); // phpcs:ignore ?></span><?php endfor; ?>
				<span class="vp-ticker-star vp-ticker-star--half"><?php echo vp_icon( 'star' ); // phpcs:ignore ?></span>
			</div>
			<span class="vp-ticker-tag"><?php esc_html_e( 'Loved by Customers', 'vitalpeptides' ); ?></span>
		</div>

		<div class="vp-ticker-scroll">
			<div class="vp-ticker-track">
				<?php foreach ( array_merge( $vp_testimonials, $vp_testimonials ) as $vp_t ) : ?>
					<span class="vp-ticker-item">"<?php echo esc_html( $vp_t['text'] ); ?>" – <strong><?php echo esc_html( $vp_t['name'] ); ?></strong></span>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
