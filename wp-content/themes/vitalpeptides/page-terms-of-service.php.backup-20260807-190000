<?php
/**
 * Terms of Service page — replicates pages/TermsOfService.tsx.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

get_header();

vp_page_hero(
	__( 'Terms of Service', 'vitalpeptides' ),
	__( 'Last updated: February 2026', 'vitalpeptides' )
);

$vp_sections = array(
	array( 'title' => __( 'Acceptance of Terms', 'vitalpeptides' ), 'content' => __( 'By accessing and using the Vital Peptides website and services, you agree to be bound by these Terms of Service. If you do not agree, please do not use our services.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Research Use Only', 'vitalpeptides' ), 'content' => __( 'All products sold by Vital Peptides are intended strictly for laboratory research purposes. They are not approved for human consumption, therapeutic use, veterinary use, or any clinical applications. By purchasing, you confirm that products will be used solely for legitimate research.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Account Responsibilities', 'vitalpeptides' ), 'content' => __( 'You are responsible for maintaining the confidentiality of your account credentials and for all activities under your account. You must provide accurate and complete information when creating an account.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Orders & Payments', 'vitalpeptides' ), 'content' => __( 'All orders are subject to acceptance and product availability. Prices are listed in USD and may change without notice. Payment must be received in full before orders are processed and shipped.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Shipping & Delivery', 'vitalpeptides' ), 'content' => __( 'We aim to ship all orders placed before 2PM EST on the same business day. Delivery times vary based on location and shipping method selected. Vital Peptides is not responsible for delays caused by carriers.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Returns & Refunds', 'vitalpeptides' ), 'content' => __( 'We accept returns of unopened, undamaged products within 30 days of delivery. Refunds are processed to the original payment method within 5-10 business days. Custom or bulk orders may not be eligible for return.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Intellectual Property', 'vitalpeptides' ), 'content' => __( 'All content on our website, including text, images, logos, and design, is the property of Vital Peptides and is protected by intellectual property laws. Unauthorized use is prohibited.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Limitation of Liability', 'vitalpeptides' ), 'content' => __( 'Vital Peptides shall not be liable for any indirect, incidental, or consequential damages arising from the use of our products or services. Our liability is limited to the purchase price of the products.', 'vitalpeptides' ) ),
	array(
    'title'   => __( 'Contact', 'vitalpeptides' ),
    'content' => sprintf(
        __( 'For questions regarding these terms, contact us at %s.', 'vitalpeptides' ),
        '<a href="mailto:support@vitalpeptidesciences.com">support@vitalpeptidesciences.com</a>'
    ),
),
);
?>

<section class="vp-page-body">
	<div class="vp-container-narrow">
		<div class="vp-prose-sections">
			<?php foreach ( $vp_sections as $vp_section ) : ?>
				<div class="vp-prose-section">
					<h2><?php echo esc_html( $vp_section['title'] ); ?></h2>
					<p><?php echo wp_kses_post( $vp_section['content'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
