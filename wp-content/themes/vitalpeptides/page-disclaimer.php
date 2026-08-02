<?php
/**
 * Disclaimer page — replicates pages/Disclaimer.tsx.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

get_header();

vp_page_hero(
	__( 'Disclaimer', 'vitalpeptides' ),
	__( 'Important information about our products and services', 'vitalpeptides' )
);

$vp_sections = array(
	array( 'title' => __( 'Research Use Only', 'vitalpeptides' ), 'content' => __( 'All products offered by Vital Peptides are intended exclusively for in-vitro laboratory research and educational purposes. These products are not drugs, supplements, foods, or cosmetics, and are not intended for human or animal consumption.', 'vitalpeptides' ) ),
	array( 'title' => __( 'No Medical Claims', 'vitalpeptides' ), 'content' => __( 'Vital Peptides makes no therapeutic or medical claims about any of its products. The information provided on this website is for educational and research reference purposes only and should not be construed as medical advice.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Buyer Responsibility', 'vitalpeptides' ), 'content' => __( 'By purchasing from Vital Peptides, you agree that you are a qualified researcher and that all products will be used in compliance with applicable local, state, federal, and international regulations. You accept full responsibility for the use of any products purchased.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Product Information', 'vitalpeptides' ), 'content' => __( 'While we strive to ensure accuracy of all product descriptions, specifications, and certificates of analysis, Vital Peptides does not guarantee that the information is complete or error-free. Product specifications may be updated without prior notice.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Third-Party Content', 'vitalpeptides' ), 'content' => __( 'This website may contain links to third-party resources and research references. Vital Peptides is not responsible for the accuracy or content of external sites and does not endorse any third-party products or services.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Limitation of Liability', 'vitalpeptides' ), 'content' => __( 'To the fullest extent permitted by law, Vital Peptides disclaims all warranties, express or implied. In no event shall Vital Peptides be liable for any direct, indirect, incidental, or consequential damages arising from the use or inability to use our products or services.', 'vitalpeptides' ) ),
);
?>

<section class="vp-page-body">
	<div class="vp-container-narrow">
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
