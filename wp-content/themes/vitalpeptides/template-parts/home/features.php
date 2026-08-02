<?php
/**
 * Features bento grid — replicates FeaturesSection.tsx.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

$vp_shop_url = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' );
?>
<section class="vp-features" aria-label="<?php esc_attr_e( 'Features', 'vitalpeptides' ); ?>">
	<div class="vp-container">
		<h2 class="vp-features-title"><?php esc_html_e( 'Everything you need to succeed', 'vitalpeptides' ); ?></h2>
		<div class="vp-features-grid">

			<div class="vp-feature-card vp-col-6">
				<h3><?php esc_html_e( 'Join a community of researchers', 'vitalpeptides' ); ?></h3>
				<p><?php esc_html_e( 'Every purchase unlocks access to our private Discord community. Connect with fellow enthusiasts, share insights, and get real-time support.', 'vitalpeptides' ); ?></p>
				<a href="<?php echo esc_url( $vp_shop_url ); ?>" class="vp-btn vp-btn--ghost vp-btn--sm"><?php esc_html_e( 'Shop & Join Community', 'vitalpeptides' ); ?></a>
				<div class="vp-feature-icon"><?php echo vp_icon( 'users' ); // phpcs:ignore ?></div>
			</div>

			<div class="vp-feature-card vp-col-6">
				<h3><?php esc_html_e( 'Lab-grade quality meets research-friendly pricing', 'vitalpeptides' ); ?></h3>
				<p><?php esc_html_e( 'U.S.-based provider sourcing from GMP-compliant manufacturers. Every batch undergoes rigorous third-party testing with full documentation.', 'vitalpeptides' ); ?></p>
				<a href="<?php echo esc_url( $vp_shop_url ); ?>" class="vp-btn vp-btn--ghost vp-btn--sm"><?php esc_html_e( 'Shop USA tested Peptides', 'vitalpeptides' ); ?></a>
				<div class="vp-feature-icon"><?php echo vp_icon( 'flask' ); // phpcs:ignore ?></div>
			</div>

			<div class="vp-feature-card vp-col-6 vp-feature-card--slim">
				<h3><?php esc_html_e( 'Expert support whenever you need it', 'vitalpeptides' ); ?></h3>
				<div class="vp-feature-icon vp-feature-icon--sm"><?php echo vp_icon( 'headphones' ); // phpcs:ignore ?></div>
			</div>

			<div class="vp-feature-card vp-col-6 vp-row-2">
				<h3><?php esc_html_e( 'Extensive research library at your fingertips', 'vitalpeptides' ); ?></h3>
				<p><?php esc_html_e( 'Access our comprehensive collection of research articles, studies, and educational resources.', 'vitalpeptides' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/research/' ) ); ?>" class="vp-btn vp-btn--ghost vp-btn--sm"><?php esc_html_e( 'Explore Research Library', 'vitalpeptides' ); ?></a>
				<div class="vp-feature-icon vp-feature-icon--lg"><?php echo vp_icon( 'book' ); // phpcs:ignore ?></div>
			</div>

			<div class="vp-feature-card vp-col-6 vp-feature-card--slim">
				<h3><?php esc_html_e( 'Anywhere in the US, as fast as next day', 'vitalpeptides' ); ?></h3>
				<div class="vp-feature-icon vp-feature-icon--sm"><?php echo vp_icon( 'truck' ); // phpcs:ignore ?></div>
			</div>

			<div class="vp-feature-card vp-col-12 vp-feature-card--wide">
				<div class="vp-feature-wide-copy">
					<h3><?php esc_html_e( '60-day money-back guarantee & free shipment protection', 'vitalpeptides' ); ?></h3>
					<p><?php esc_html_e( 'Not satisfied? Full refund within 60 days, no questions asked. Every order is protected against damage, loss, or theft in transit.', 'vitalpeptides' ); ?></p>
				</div>
				<div class="vp-feature-wide-actions">
					<a href="<?php echo esc_url( $vp_shop_url ); ?>" class="vp-btn vp-btn--ghost vp-btn--sm"><?php esc_html_e( 'Shop With Confidence', 'vitalpeptides' ); ?></a>
					<div class="vp-feature-icon vp-feature-icon--inline"><?php echo vp_icon( 'shield' ); // phpcs:ignore ?></div>
				</div>
			</div>

		</div>
	</div>
</section>
