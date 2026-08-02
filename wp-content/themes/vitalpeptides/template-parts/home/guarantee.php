<?php
/**
 * Guarantee section — replicates GuaranteeSection.tsx (NAD bottle + 3 color-tab cards).
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

$vp_items = array(
	array( 'icon' => 'shield', 'title' => __( '99% Purity Guaranteed', 'vitalpeptides' ), 'subtitle' => __( 'Or your money back', 'vitalpeptides' ), 'color' => 'hsl(174 63% 92%)' ),
	array( 'icon' => 'truck', 'title' => __( 'Same Day Fulfillment', 'vitalpeptides' ), 'subtitle' => __( 'Weekdays before 2PM EST', 'vitalpeptides' ), 'color' => 'hsl(200 60% 92%)' ),
	array( 'icon' => 'file', 'title' => __( 'CoA with Every Batch', 'vitalpeptides' ), 'subtitle' => __( 'Third Party tested in America', 'vitalpeptides' ), 'color' => 'hsl(48 70% 92%)' ),
);
?>
<section class="vp-guarantee" aria-label="<?php esc_attr_e( 'Quality guarantee', 'vitalpeptides' ); ?>">
	<div class="vp-guarantee-bg" aria-hidden="true"></div>
	<div class="vp-guarantee-grid">
		<div class="vp-guarantee-image">
			<div class="vp-guarantee-image-inner" role="img" aria-label="<?php esc_attr_e( 'Featured peptide product', 'vitalpeptides' ); ?>">
				<img src="<?php echo esc_url( VP_THEME_URI . '/assets/images/NADNew.png' ); ?>" alt="NAD+ Peptide vial" width="500" height="650" loading="lazy" draggable="false">
			</div>
		</div>

		<div class="vp-guarantee-content">
			<header class="vp-guarantee-head">
				<h2><?php esc_html_e( 'The Vital Peptides Guarantee', 'vitalpeptides' ); ?></h2>
				<p><?php esc_html_e( "We don't compromise on quality. Every product meets the highest standards.", 'vitalpeptides' ); ?></p>
			</header>
			<div class="vp-guarantee-cards">
				<?php foreach ( $vp_items as $vp_item ) : ?>
					<article class="vp-guarantee-card">
						<div class="vp-guarantee-tab" style="background-color: <?php echo esc_attr( $vp_item['color'] ); ?>;" aria-hidden="true"></div>
						<div class="vp-guarantee-card-row">
							<div class="vp-guarantee-icon" style="background-color: <?php echo esc_attr( $vp_item['color'] ); ?>;"><?php echo vp_icon( $vp_item['icon'] ); // phpcs:ignore ?></div>
							<div>
								<h3><?php echo esc_html( $vp_item['title'] ); ?></h3>
								<p><?php echo esc_html( $vp_item['subtitle'] ); ?></p>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
