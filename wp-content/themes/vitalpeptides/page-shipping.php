<?php
/**
 * Shipping page — replicates pages/Shipping.tsx.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

get_header();

vp_page_hero(
	__( 'Shipping Information', 'vitalpeptides' ),
	__( 'Fast, reliable shipping for all your research needs.', 'vitalpeptides' )
);

$vp_highlights = array(
	array( 'icon' => 'clock', 'title' => __( 'Same-Day Shipping', 'vitalpeptides' ), 'desc' => __( 'Orders before 2PM EST ship the same business day.', 'vitalpeptides' ) ),
	array( 'icon' => 'truck', 'title' => __( 'Free Over $150', 'vitalpeptides' ), 'desc' => __( 'Free standard shipping on all orders over $150.', 'vitalpeptides' ) ),
	array( 'icon' => 'package', 'title' => __( 'Secure Packaging', 'vitalpeptides' ), 'desc' => __( 'Temperature-controlled and discreet packaging.', 'vitalpeptides' ) ),
	array( 'icon' => 'map-pin', 'title' => __( 'US Shipping', 'vitalpeptides' ), 'desc' => __( 'Currently available for delivery within the United States.', 'vitalpeptides' ) ),
);

$vp_sections = array(
	array( 'title' => __( 'Processing Times', 'vitalpeptides' ), 'content' => __( 'All orders are processed within 1 business day. Orders placed before 2PM EST Monday-Friday are shipped the same day. Orders placed after 2PM EST or on weekends/holidays will be shipped the next business day.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Shipping Methods', 'vitalpeptides' ), 'content' => __( 'We offer Standard Shipping (2-5 business days), Priority Shipping (1-3 business days), and Overnight Shipping for time-sensitive research. Shipping rates are calculated at checkout based on order weight and destination.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Order Tracking', 'vitalpeptides' ), 'content' => __( "A tracking number is provided via email once your order ships. You can track your package through the carrier's website. Please allow up to 24 hours for tracking information to become active.", 'vitalpeptides' ) ),
	array( 'title' => __( 'Packaging & Handling', 'vitalpeptides' ), 'content' => __( 'All peptides are shipped in temperature-appropriate packaging to maintain product integrity. Lyophilized peptides are stable at ambient temperatures for shipping duration, but we use cold packs for sensitive items when needed.', 'vitalpeptides' ) ),
);
?>

<section class="vp-page-body">
	<div class="vp-container">
		<div class="vp-info-grid vp-info-grid--4">
			<?php foreach ( $vp_highlights as $vp_item ) : ?>
				<div class="vp-info-card vp-info-card--center">
					<div class="vp-info-icon"><?php echo vp_icon( $vp_item['icon'] ); // phpcs:ignore ?></div>
					<h3><?php echo esc_html( $vp_item['title'] ); ?></h3>
					<p><?php echo esc_html( $vp_item['desc'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="vp-prose-sections">
			<?php foreach ( $vp_sections as $vp_section ) : ?>
				<div class="vp-prose-section">
					<h2><?php echo esc_html( $vp_section['title'] ); ?></h2>
					<p><?php echo esc_html( $vp_section['content'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
