<?php
/**
 * Quality section — replicates QualitySection.tsx (stats, tabs, product image panel).
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

$vp_shop_url = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' );

$vp_tabs = array(
	'potency' => array(
		'label' => __( 'Potency', 'vitalpeptides' ),
		'icon'  => 'zap',
		'title' => __( 'Verified Potency', 'vitalpeptides' ),
		'badge' => __( 'HPLC Analysis', 'vitalpeptides' ),
		'desc'  => __( 'Every vial is tested to confirm it contains exactly what the label says—down to the microgram.', 'vitalpeptides' ),
		'why'   => __( 'No guessing games. You get the exact concentration you paid for, every single time.', 'vitalpeptides' ),
	),
	'purity' => array(
		'label' => __( 'Purity', 'vitalpeptides' ),
		'icon'  => 'shield',
		'title' => __( 'Guaranteed Purity', 'vitalpeptides' ),
		'badge' => __( 'Mass Spectrometry', 'vitalpeptides' ),
		'desc'  => __( 'Comprehensive testing confirms our peptides are free from impurities, degradation products, and synthesis byproducts.', 'vitalpeptides' ),
		'why'   => __( 'Higher purity means better results. We guarantee 99%+ or your money back.', 'vitalpeptides' ),
	),
	'stability' => array(
		'label' => __( 'Stability', 'vitalpeptides' ),
		'icon'  => 'clock',
		'title' => __( 'Long-Term Stability', 'vitalpeptides' ),
		'badge' => __( 'pH & Stability Testing', 'vitalpeptides' ),
		'desc'  => __( 'Optimal pH and formulation testing ensures your peptides remain effective throughout their entire shelf life.', 'vitalpeptides' ),
		'why'   => __( 'Your peptides work when you need them—not just when they arrive.', 'vitalpeptides' ),
	),
	'safety' => array(
		'label' => __( 'Safety', 'vitalpeptides' ),
		'icon'  => 'check-circle',
		'title' => __( 'Contaminant-Free', 'vitalpeptides' ),
		'badge' => __( 'USP Sterility & LAL', 'vitalpeptides' ),
		'desc'  => __( 'Rigorous sterility and endotoxin testing confirms products are free from bacteria, fungi, and harmful toxins.', 'vitalpeptides' ),
		'why'   => __( "Peace of mind knowing your research won't be compromised by contamination.", 'vitalpeptides' ),
	),
	'consistency' => array(
		'label' => __( 'Consistency', 'vitalpeptides' ),
		'icon'  => 'refresh',
		'title' => __( 'Batch-to-Batch Consistency', 'vitalpeptides' ),
		'badge' => __( 'QC Verification', 'vitalpeptides' ),
		'desc'  => __( 'Precision weighing and quality controls ensure every batch meets the same exacting standards.', 'vitalpeptides' ),
		'why'   => __( "Same great quality whether it's your first order or your fiftieth.", 'vitalpeptides' ),
	),
);
?>
<section class="vp-quality" aria-label="<?php esc_attr_e( 'Quality assurance', 'vitalpeptides' ); ?>">
	<div class="vp-container">
		<div class="vp-quality-grid">
			<div class="vp-quality-content">
				<h2><?php esc_html_e( 'Quality you can verify, not just trust', 'vitalpeptides' ); ?></h2>
				<p class="vp-quality-lead"><?php esc_html_e( "Every batch is independently tested by accredited U.S. laboratories. We don't ask you to take our word for it—we give you the proof.", 'vitalpeptides' ); ?></p>

				<div class="vp-quality-stats">
					<div class="vp-quality-stat"><?php echo vp_icon( 'shield' ); // phpcs:ignore ?><span class="vp-quality-stat-title"><?php esc_html_e( '99% HPLC', 'vitalpeptides' ); ?></span><span class="vp-quality-stat-sub"><?php esc_html_e( 'Purity', 'vitalpeptides' ); ?></span></div>
					<div class="vp-quality-stat"><?php echo vp_icon( 'check-circle' ); // phpcs:ignore ?><span class="vp-quality-stat-title"><?php esc_html_e( '3rd Party', 'vitalpeptides' ); ?></span><span class="vp-quality-stat-sub"><?php esc_html_e( 'Lab Tested', 'vitalpeptides' ); ?></span></div>
					<div class="vp-quality-stat"><?php echo vp_icon( 'zap' ); // phpcs:ignore ?><span class="vp-quality-stat-title"><?php esc_html_e( 'Lyophilized', 'vitalpeptides' ); ?></span><span class="vp-quality-stat-sub"><?php esc_html_e( 'Powder', 'vitalpeptides' ); ?></span></div>
				</div>

				<div class="vp-quality-tabs" role="tablist">
					<?php $vp_i = 0; foreach ( $vp_tabs as $vp_id => $vp_tab ) : ?>
						<button type="button" role="tab" class="vp-quality-tab<?php echo 0 === $vp_i ? ' is-active' : ''; ?>" data-tab="<?php echo esc_attr( $vp_id ); ?>" aria-selected="<?php echo 0 === $vp_i ? 'true' : 'false'; ?>">
							<?php echo vp_icon( $vp_tab['icon'] ); // phpcs:ignore ?><?php echo esc_html( $vp_tab['label'] ); ?>
						</button>
					<?php $vp_i++; endforeach; ?>
				</div>

				<div class="vp-quality-panels">
					<?php $vp_i = 0; foreach ( $vp_tabs as $vp_id => $vp_tab ) : ?>
						<div class="vp-quality-panel<?php echo 0 === $vp_i ? ' is-active' : ''; ?>" data-panel="<?php echo esc_attr( $vp_id ); ?>" role="tabpanel">
							<div class="vp-quality-panel-head">
								<h3><?php echo esc_html( $vp_tab['title'] ); ?></h3>
								<span class="vp-quality-panel-badge"><?php echo vp_icon( 'check-circle' ); // phpcs:ignore ?><?php echo esc_html( $vp_tab['badge'] ); ?></span>
							</div>
							<p class="vp-quality-panel-desc"><?php echo esc_html( $vp_tab['desc'] ); ?></p>
							<div class="vp-quality-why">
								<p><span><?php esc_html_e( 'Why it matters: ', 'vitalpeptides' ); ?></span><?php echo esc_html( $vp_tab['why'] ); ?></p>
							</div>
						</div>
					<?php $vp_i++; endforeach; ?>
				</div>

				<div class="vp-quality-cta">
					<a href="<?php echo esc_url( $vp_shop_url ); ?>" class="vp-btn vp-btn--primary vp-btn--arrow">
						<span><?php esc_html_e( 'Shop Now', 'vitalpeptides' ); ?></span>
						<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
					</a>
					<div class="vp-quality-coa"><?php echo vp_icon( 'check-circle' ); // phpcs:ignore ?><span><?php esc_html_e( 'Free COA included with every order', 'vitalpeptides' ); ?></span></div>
				</div>
			</div>

			<div class="vp-quality-visual">
				<div class="vp-quality-visual-inner">
					<div class="vp-quality-ring" aria-hidden="true"></div>
					<div class="vp-quality-badge-float">
						<div class="vp-quality-badge-icon"><?php echo vp_icon( 'check-circle' ); // phpcs:ignore ?></div>
						<div>
							<p class="vp-quality-badge-title"><?php esc_html_e( '99%+ Purity', 'vitalpeptides' ); ?></p>
							<p class="vp-quality-badge-sub"><?php esc_html_e( 'Verified by HPLC', 'vitalpeptides' ); ?></p>
						</div>
					</div>
					<img src="<?php echo esc_url( VP_THEME_URI . '/assets/images/tb-500-quality.png' ); ?>" alt="<?php esc_attr_e( 'TB-500 peptide vial - 99%+ purity', 'vitalpeptides' ); ?>" loading="lazy">
				</div>
			</div>
		</div>
	</div>
</section>
