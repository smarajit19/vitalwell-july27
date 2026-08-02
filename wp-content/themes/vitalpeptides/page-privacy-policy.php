<?php
/**
 * Privacy Policy page — replicates pages/PrivacyPolicy.tsx.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

get_header();

vp_page_hero(
	__( 'Privacy Policy', 'vitalpeptides' ),
	__( 'Last updated: February 2026', 'vitalpeptides' )
);

$vp_sections = array(
	array( 'title' => __( 'Research Use Only', 'vitalpeptides' ), 'content' => __( 'All products offered by Vital Peptide Science are supplied strictly for laboratory research use only. They are NOT intended for human or animal consumption and are not drugs, foods, supplements, or medical devices. Accounts and orders are restricted to qualified researchers who are at least 21 years of age.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Information We Collect', 'vitalpeptides' ), 'content' => __( 'We collect information you provide directly, such as your name, email address, shipping address, and payment information when you create an account or place an order. We also automatically collect certain information about your device and usage of our website, including IP address, browser type, and browsing activity.', 'vitalpeptides' ) ),
	array( 'title' => __( 'How We Use Your Information', 'vitalpeptides' ), 'content' => __( 'We use your information to process orders, manage your account, communicate with you about your purchases, improve our website and services, and comply with legal obligations. We may also use your information to send you marketing communications, which you can opt out of at any time.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Information Sharing', 'vitalpeptides' ), 'content' => __( 'We do not sell your personal information. We may share your information with service providers who assist us in operating our website, processing payments, and fulfilling orders. We may also disclose information when required by law or to protect our rights.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Data Security', 'vitalpeptides' ), 'content' => __( 'We implement industry-standard security measures to protect your personal information, including SSL encryption, secure payment processing, and restricted access to personal data. However, no method of electronic storage is 100% secure.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Cookies', 'vitalpeptides' ), 'content' => __( 'We use cookies and similar technologies to enhance your browsing experience, analyze website traffic, and personalize content. You can control cookie preferences through your browser settings.', 'vitalpeptides' ) ),
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
			<div class="vp-prose-section">
				<h2><?php esc_html_e( 'Your Rights', 'vitalpeptides' ); ?></h2>
				<p><?php esc_html_e( 'You have the right to access, correct, or delete your personal information. You may also request a copy of the data we hold about you. To exercise these rights, please contact us at', 'vitalpeptides' ); ?> <a class="vp-link" href="mailto:support@vitalpeptidesciences.com">support@vitalpeptidesciences.com</a>.</p>
			</div>
			<div class="vp-prose-section">
				<h2><?php esc_html_e( 'Contact Us', 'vitalpeptides' ); ?></h2>
				<p><?php esc_html_e( 'If you have questions about this Privacy Policy, please contact us at', 'vitalpeptides' ); ?> <a class="vp-link" href="mailto:support@vitalpeptidesciences.com">support@vitalpeptidesciences.com</a>.</p>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
