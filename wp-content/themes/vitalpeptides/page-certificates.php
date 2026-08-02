<?php
/**
 * Certificates page — replicates pages/Certificates.tsx.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

get_header();

vp_page_hero(
	__( 'Certificates of Analysis', 'vitalpeptides' ),
	__( 'Every batch of our peptides undergoes rigorous third-party testing to ensure the highest quality standards for your research.', 'vitalpeptides' ),
	'shield',
	__( 'Quality Assurance', 'vitalpeptides' ),
	true
);

$vp_tests = array(
	array( 'icon' => 'flask', 'title' => __( 'HPLC Analysis', 'vitalpeptides' ), 'desc' => __( 'High-Performance Liquid Chromatography confirms ≥99% purity for every batch.', 'vitalpeptides' ) ),
	array( 'icon' => 'file', 'title' => __( 'Mass Spectrometry', 'vitalpeptides' ), 'desc' => __( 'Molecular weight verification ensures correct peptide identity and structure.', 'vitalpeptides' ) ),
	array( 'icon' => 'shield', 'title' => __( 'Amino Acid Analysis', 'vitalpeptides' ), 'desc' => __( 'Confirms the correct amino acid composition and sequence integrity.', 'vitalpeptides' ) ),
	array( 'icon' => 'check-circle', 'title' => __( 'Endotoxin Testing', 'vitalpeptides' ), 'desc' => __( 'Ensures peptides are free from bacterial endotoxin contamination.', 'vitalpeptides' ) ),
);
?>

<section class="vp-page-body">
	<div class="vp-container">
		<h2 class="vp-body-title"><?php esc_html_e( 'Our Testing Process', 'vitalpeptides' ); ?></h2>
		<p class="vp-body-sub"><?php esc_html_e( 'We employ multiple analytical techniques to verify the identity, purity, and quality of every peptide we offer.', 'vitalpeptides' ); ?></p>
		<div class="vp-info-grid vp-info-grid--4">
			<?php foreach ( $vp_tests as $vp_test ) : ?>
				<div class="vp-info-card vp-info-card--center">
					<div class="vp-info-icon"><?php echo vp_icon( $vp_test['icon'] ); // phpcs:ignore ?></div>
					<h3><?php echo esc_html( $vp_test['title'] ); ?></h3>
					<p><?php echo esc_html( $vp_test['desc'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="vp-page-body vp-page-body--alt">
	<div class="vp-container">
		<h2 class="vp-body-title"><?php esc_html_e( 'Product Analysis Reports', 'vitalpeptides' ); ?></h2>
		<div class="vp-coa-grid">
			<?php
			if ( class_exists( 'WooCommerce' ) ) :
				$vp_products = wc_get_products( array( 'limit' => -1, 'status' => 'publish', 'orderby' => 'menu_order', 'order' => 'ASC' ) );
				foreach ( $vp_products as $vp_product ) :
					$vp_dosage = vp_product_meta( $vp_product, 'dosage' );
					$vp_purity = vp_product_meta( $vp_product, 'purity' );
					?>
					<div class="vp-coa-card">
						<div class="vp-coa-img"><?php echo $vp_product->get_image( 'woocommerce_gallery_thumbnail' ); // phpcs:ignore ?></div>
						<div class="vp-coa-info">
							<h3><?php echo esc_html( $vp_product->get_name() ); ?> <?php echo esc_html( $vp_dosage ); ?></h3>
							<?php if ( $vp_purity ) : ?><p><?php echo esc_html( $vp_purity ); ?></p><?php endif; ?>
							<div class="vp-coa-verified"><?php echo vp_icon( 'check-circle' ); // phpcs:ignore ?><span><?php esc_html_e( 'COA Verified', 'vitalpeptides' ); ?></span></div>
						</div>
						<a href="<?php echo esc_url( $vp_product->get_permalink() ); ?>" class="vp-coa-link"><?php esc_html_e( 'View Product →', 'vitalpeptides' ); ?></a>
					</div>
				<?php endforeach; endif; ?>
		</div>
		<p class="vp-coa-note">
			<?php esc_html_e( 'Additional COA reports available for members-only products after login.', 'vitalpeptides' ); ?>
			<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'myaccount' ) ) : wp_login_url() ); ?>"><?php esc_html_e( 'Create an account', 'vitalpeptides' ); ?></a>
			<?php esc_html_e( 'to view all certificates.', 'vitalpeptides' ); ?>
		</p>
	</div>
</section>

<?php get_footer(); ?>
