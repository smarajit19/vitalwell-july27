<?php
/**
 * Why choose section — replicates WhyChooseSection.tsx (6 icon cards).
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

$vp_cards = array(
	array( 'icon' => 'package', 'title' => __( 'Always in Stock', 'vitalpeptides' ), 'desc' => __( 'Widely studied research peptides ready to ship. No backorders, no waiting.', 'vitalpeptides' ) ),
	array( 'icon' => 'flask', 'title' => __( 'Certificate of Analysis', 'vitalpeptides' ), 'desc' => __( 'Every batch ships with a full third-party Certificate of Analysis for complete transparency.', 'vitalpeptides' ) ),
	array( 'icon' => 'truck', 'title' => __( 'Safe & Protected Shipping', 'vitalpeptides' ), 'desc' => __( 'Cold-pack shipping keeps materials stable. Discreet packaging with full tracking on every USA order.', 'vitalpeptides' ) ),
	array( 'icon' => 'microscope', 'title' => __( 'Research-Grade Materials', 'vitalpeptides' ), 'desc' => __( 'Supplied strictly for laboratory research use by qualified researchers.', 'vitalpeptides' ) ),
	array( 'icon' => 'shield', 'title' => __( '99%+ Purity Verified', 'vitalpeptides' ), 'desc' => __( 'Every batch tested by US labs via HPLC and Mass Spectrometry. Full Certificate of Analysis included.', 'vitalpeptides' ) ),
	array( 'icon' => 'zap', 'title' => __( 'Same-Day Shipping', 'vitalpeptides' ), 'desc' => __( 'Order weekdays by 2PM EST, ships today. Most USA orders arrive in 2-3 business days.', 'vitalpeptides' ) ),
);
?>
<section class="vp-why" aria-labelledby="vp-why-heading">
	<div class="vp-why-bg" aria-hidden="true"></div>
	<div class="vp-container vp-why-inner">
		<h2 id="vp-why-heading"><?php esc_html_e( 'Why choose Vital Peptides?', 'vitalpeptides' ); ?></h2>
		<div class="vp-why-grid">
			<?php foreach ( $vp_cards as $vp_card ) : ?>
				<article class="vp-why-card">
					<div class="vp-why-icon"><?php echo vp_icon( $vp_card['icon'] ); // phpcs:ignore ?></div>
					<h3><?php echo esc_html( $vp_card['title'] ); ?></h3>
					<p><?php echo esc_html( $vp_card['desc'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
