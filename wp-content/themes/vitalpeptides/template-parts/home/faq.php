<?php
/**
 * Homepage FAQ accordion — replicates FAQSection.tsx.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

$vp_faqs = array(
	array( 'q' => __( 'Who can purchase from Vital Peptide Science?', 'vitalpeptides' ), 'a' => __( 'Purchases are restricted to registered, approved researchers who are at least 21 years of age. All products are supplied strictly for laboratory research use only.', 'vitalpeptides' ) ),
	array( 'q' => __( 'What are these products intended for?', 'vitalpeptides' ), 'a' => __( 'All products are research chemicals intended solely for in-vitro laboratory research and educational purposes. They are not for human or animal consumption and are not drugs, foods, or supplements.', 'vitalpeptides' ) ),
	array( 'q' => __( 'How do you verify quality?', 'vitalpeptides' ), 'a' => __( 'Every batch undergoes third-party testing by US laboratories via HPLC and Mass Spectrometry, and ships with a full Certificate of Analysis documenting purity and identity.', 'vitalpeptides' ) ),
	array( 'q' => __( 'What is your shipping policy?', 'vitalpeptides' ), 'a' => __( 'We offer same-day fulfillment on orders placed before 2PM EST, Monday through Friday. Delivery times vary by location and selected shipping method.', 'vitalpeptides' ) ),
	array( 'q' => __( 'How should materials be stored?', 'vitalpeptides' ), 'a' => __( 'Lyophilized powder should be stored at -20°C and protected from light. After reconstitution, store at 2–8°C. See each product page for specific storage conditions.', 'vitalpeptides' ) ),
	array( 'q' => __( 'What is your return policy?', 'vitalpeptides' ), 'a' => __( 'Unopened, undamaged products may be returned within 30 days of delivery. If a product does not meet the purity specifications stated in its Certificate of Analysis, contact us for a replacement or refund.', 'vitalpeptides' ) ),
);
?>
<section id="faq" class="vp-faq">
	<div class="vp-faq-container">
		<div class="vp-section-head fade-in-section">
			<h2><?php esc_html_e( 'Frequently Asked Questions', 'vitalpeptides' ); ?></h2>
			<p><?php esc_html_e( 'Key information for qualified researchers.', 'vitalpeptides' ); ?></p>
		</div>

		<div class="vp-accordion">
			<?php foreach ( $vp_faqs as $vp_faq ) : ?>
				<div class="vp-accordion-item">
					<button type="button" class="vp-accordion-trigger" aria-expanded="false">
						<?php echo esc_html( $vp_faq['q'] ); ?>
						<?php echo vp_icon( 'chevron-down' ); // phpcs:ignore ?>
					</button>
					<div class="vp-accordion-content" hidden>
						<p><?php echo esc_html( $vp_faq['a'] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
