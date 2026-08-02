<?php
/**
 * FAQ page — replicates pages/FAQ.tsx.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

get_header();

vp_page_hero(
	__( 'Frequently Asked Questions', 'vitalpeptides' ),
	__( 'Find answers to the most common questions about our products and services.', 'vitalpeptides' )
);

$vp_faqs = array(
	array( 'q' => __( 'What are your peptides used for?', 'vitalpeptides' ), 'a' => __( 'All of our peptides are intended strictly for in-vitro laboratory research and educational purposes only. They are not approved for human or animal consumption.', 'vitalpeptides' ) ),
	array( 'q' => __( 'What purity are your peptides?', 'vitalpeptides' ), 'a' => __( 'All of our peptides exceed 99% purity as verified by HPLC (High-Performance Liquid Chromatography) and mass spectrometry. Each product ships with a Certificate of Analysis.', 'vitalpeptides' ) ),
	array( 'q' => __( 'How should I store my peptides?', 'vitalpeptides' ), 'a' => __( 'Lyophilized (freeze-dried) peptides should be stored at -20°C for long-term storage. Once reconstituted, peptides should be refrigerated at 2-8°C and used within a reasonable timeframe.', 'vitalpeptides' ) ),
	array( 'q' => __( 'Do you offer Certificates of Analysis?', 'vitalpeptides' ), 'a' => __( 'Yes, every batch of peptides comes with a Certificate of Analysis (COA) that details purity, identity verification, and other quality metrics. COAs are available on request and on our Certificates page.', 'vitalpeptides' ) ),
	array( 'q' => __( 'What payment methods do you accept?', 'vitalpeptides' ), 'a' => __( 'We accept major credit cards (Visa, MasterCard, American Express), as well as other standard payment methods through our secure checkout.', 'vitalpeptides' ) ),
	array( 'q' => __( 'How long does shipping take?', 'vitalpeptides' ), 'a' => __( 'Orders placed before 2PM EST ship the same business day. Standard shipping within the US typically takes 2-5 business days. Expedited options are available at checkout.', 'vitalpeptides' ) ),
	array( 'q' => __( 'Do you ship internationally?', 'vitalpeptides' ), 'a' => __( 'Currently, we ship within the United States. International shipping may be available for select destinations — please contact our support team for more information.', 'vitalpeptides' ) ),
	array( 'q' => __( 'What is your return policy?', 'vitalpeptides' ), 'a' => __( 'We accept returns of unopened, undamaged products within 30 days of delivery. Please visit our Returns & Refunds page for full details.', 'vitalpeptides' ) ),
	array( 'q' => __( 'Do I need an account to order?', 'vitalpeptides' ), 'a' => __( 'Yes, an account is required to browse our full catalog and place orders. Creating an account is free and gives you access to member pricing and our complete product selection.', 'vitalpeptides' ) ),
	array( 'q' => __( 'How do I contact support?', 'vitalpeptides' ), 'a' => __( 'You can reach us via email at support@vitalpeptidesciences.com or by phone at +1 (877) 625-7857, Monday through Friday, 9AM-5PM EST.', 'vitalpeptides' ) ),
);
?>

<section class="vp-page-body">
	<div class="vp-container-narrow">
		<div class="vp-accordion vp-accordion--page">
			<?php foreach ( $vp_faqs as $vp_faq ) : ?>
				<div class="vp-accordion-item">
					<button type="button" class="vp-accordion-trigger" aria-expanded="false">
						<?php echo esc_html( $vp_faq['q'] ); ?>
						<?php echo vp_icon( 'chevron-down' ); // phpcs:ignore ?>
					</button>
					<div class="vp-accordion-content" hidden><p><?php echo esc_html( $vp_faq['a'] ); ?></p></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
