<?php
/**
 * Trust strip — replicates TrustStrip.tsx (4 neumorphic badges).
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

$vp_badges = array(
	array( 'icon' => 'shield', 'title' => __( '99% Purity', 'vitalpeptides' ), 'subtitle' => __( '10mg & 5mg vials', 'vitalpeptides' ), 'accent' => 'hsl(174 63% 47%)' ),
	array( 'icon' => 'flask', 'title' => __( 'Third-Party Tested', 'vitalpeptides' ), 'subtitle' => __( 'Third-party tested & verified', 'vitalpeptides' ), 'accent' => 'hsl(200 60% 50%)' ),
	array( 'icon' => 'truck', 'title' => __( 'Fast Fulfillment', 'vitalpeptides' ), 'subtitle' => __( 'Same-day before 2PM EST', 'vitalpeptides' ), 'accent' => 'hsl(174 63% 47%)' ),
	array( 'icon' => 'file', 'title' => __( 'With Every Batch', 'vitalpeptides' ), 'subtitle' => __( 'Certificate of Analysis included', 'vitalpeptides' ), 'accent' => 'hsl(48 70% 55%)' ),
);
?>
<section class="vp-trust-strip" aria-label="<?php esc_attr_e( 'Trust indicators', 'vitalpeptides' ); ?>">
	<div class="vp-container">
		<div class="vp-trust-grid">
			<?php foreach ( $vp_badges as $vp_badge ) : ?>
				<div class="vp-trust-item">
					<div class="vp-trust-icon" style="background-color: color-mix(in srgb, <?php echo esc_attr( $vp_badge['accent'] ); ?> 15%, transparent); color: <?php echo esc_attr( $vp_badge['accent'] ); ?>;">
						<?php echo vp_icon( $vp_badge['icon'] ); // phpcs:ignore ?>
					</div>
					<div class="vp-trust-copy">
						<p class="vp-trust-title"><?php echo esc_html( $vp_badge['title'] ); ?></p>
						<p class="vp-trust-sub"><?php echo esc_html( $vp_badge['subtitle'] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
