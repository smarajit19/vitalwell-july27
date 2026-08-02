<?php
/**
 * Returns page — replicates pages/Returns.tsx.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

get_header();

vp_page_hero(
	__( 'Returns & Refunds', 'vitalpeptides' ),
	__( 'Your satisfaction is our priority. Learn about our return and refund policies.', 'vitalpeptides' )
);

$vp_highlights = array(
	array( 'icon' => 'rotate', 'title' => __( '30-Day Returns', 'vitalpeptides' ), 'desc' => __( 'Return unopened products within 30 days of delivery.', 'vitalpeptides' ) ),
	array( 'icon' => 'check-circle', 'title' => __( 'Quality Guarantee', 'vitalpeptides' ), 'desc' => __( "Full replacement for any product that doesn't meet purity standards.", 'vitalpeptides' ) ),
	array( 'icon' => 'x-circle', 'title' => __( 'No Restocking Fee', 'vitalpeptides' ), 'desc' => __( 'No restocking fees on eligible returns.', 'vitalpeptides' ) ),
	array( 'icon' => 'mail', 'title' => __( 'Easy Process', 'vitalpeptides' ), 'desc' => __( "Contact support to initiate a return — we'll guide you through it.", 'vitalpeptides' ) ),
);

$vp_sections = array(
	array( 'title' => __( 'Research Use Only', 'vitalpeptides' ), 'content' => __( 'All products sold by Vital Peptide Science are supplied strictly for laboratory research use only. They are NOT for human or animal consumption and are not drugs, foods, supplements, or medical devices. This policy applies solely to products purchased and handled for lawful research purposes.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Eligibility', 'vitalpeptides' ), 'content' => __( 'Products must be unopened, in original packaging, and returned within 30 days of delivery. Custom orders, bulk orders, and products that have been opened or reconstituted are not eligible for return. For safety reasons, opened research materials cannot be accepted for return.', 'vitalpeptides' ) ),
	array( 'title' => __( 'How to Return', 'vitalpeptides' ), 'content' => __( 'Contact our support team at support@vitalpeptidesciences.com with your order number and reason for return. We will provide a return authorization and shipping instructions within 1 business day.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Refund Processing', 'vitalpeptides' ), 'content' => __( 'Once we receive and inspect the returned product, refunds are processed to the original payment method within 5-10 business days. You will receive an email confirmation when your refund has been issued.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Damaged or Incorrect Orders', 'vitalpeptides' ), 'content' => __( 'If you receive a damaged or incorrect product, please contact us immediately with photos. We will send a replacement at no additional cost and arrange return shipping for the damaged item.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Quality Concerns', 'vitalpeptides' ), 'content' => __( 'If a product does not meet the purity or quality specifications stated in its Certificate of Analysis, we will provide a full replacement or refund. Please contact us with details about your quality concern.', 'vitalpeptides' ) ),
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
