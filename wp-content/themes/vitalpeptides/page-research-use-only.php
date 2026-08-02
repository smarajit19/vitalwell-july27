<?php
/**
 * Research Use Only page — replicates pages/ResearchUseOnly.tsx.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

get_header();

vp_page_hero(
	__( 'Research Use Only', 'vitalpeptides' ),
	__( 'All peptides sold by Vital Peptides are strictly for laboratory research and educational purposes only.', 'vitalpeptides' ),
	'alert',
	__( 'Important Notice', 'vitalpeptides' )
);

$vp_cards = array(
	array( 'icon' => 'shield', 'title' => __( 'Not For Human Use', 'vitalpeptides' ), 'desc' => __( 'Our products are not intended for human or animal consumption, and are not to be used for therapeutic, diagnostic, or clinical purposes.', 'vitalpeptides' ) ),
	array( 'icon' => 'scale', 'title' => __( 'Regulatory Compliance', 'vitalpeptides' ), 'desc' => __( 'Purchasers must comply with all applicable local, state, federal, and international regulations regarding peptide research materials.', 'vitalpeptides' ) ),
	array( 'icon' => 'file', 'title' => __( 'Qualified Researchers', 'vitalpeptides' ), 'desc' => __( 'Products should only be handled by qualified research professionals in appropriate laboratory settings with proper safety protocols.', 'vitalpeptides' ) ),
	array( 'icon' => 'alert', 'title' => __( 'Buyer Agreement', 'vitalpeptides' ), 'desc' => __( 'By purchasing from Vital Peptides, you acknowledge and agree that all products are for research use only and accept full responsibility for their use.', 'vitalpeptides' ) ),
);
?>

<section class="vp-page-body">
	<div class="vp-container">
		<div class="vp-info-grid vp-info-grid--2 vp-info-grid--wide">
			<?php foreach ( $vp_cards as $vp_card ) : ?>
				<div class="vp-info-card">
					<div class="vp-info-icon"><?php echo vp_icon( $vp_card['icon'] ); // phpcs:ignore ?></div>
					<h3><?php echo esc_html( $vp_card['title'] ); ?></h3>
					<p><?php echo esc_html( $vp_card['desc'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="vp-statement-box">
			<h2><?php esc_html_e( 'Intended Use Statement', 'vitalpeptides' ); ?></h2>
			<p><?php esc_html_e( 'The products available on this website are research chemicals and are not approved by the FDA or any regulatory agency for human or veterinary use. They are intended solely for in-vitro research, educational purposes, and scientific investigation conducted by qualified professionals.', 'vitalpeptides' ); ?></p>
			<p><?php esc_html_e( 'Vital Peptides assumes no liability for any misuse of its products. Any person or entity purchasing products from Vital Peptides represents and warrants that they are legally authorized to purchase research chemicals and will use them only for lawful research purposes.', 'vitalpeptides' ); ?></p>
		</div>
	</div>
</section>

<?php get_footer(); ?>
