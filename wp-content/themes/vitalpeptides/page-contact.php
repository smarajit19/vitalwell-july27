<?php
/**
 * Contact page — replicates pages/Contact.tsx.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

get_header();

vp_page_hero(
	__( 'Contact Us', 'vitalpeptides' ),
	__( 'Have a question? Our team is here to help with your research needs.', 'vitalpeptides' )
);
?>

<section class="vp-page-body">
	<div class="vp-container-narrow">
		<h2 class="vp-body-title"><?php esc_html_e( 'Get in Touch', 'vitalpeptides' ); ?></h2>
		<div class="vp-contact-grid">
			<div class="vp-contact-card">
				<div class="vp-contact-icon"><?php echo vp_icon( 'mail' ); // phpcs:ignore ?></div>
				<div>
					<p class="vp-contact-label"><?php esc_html_e( 'Email', 'vitalpeptides' ); ?></p>
					<p class="vp-contact-value"><a href="mailto:support@vitalpeptidesciences.com">support@vitalpeptidesciences.com</a></p>
				</div>
			</div>
			<div class="vp-contact-card">
				<div class="vp-contact-icon"><?php echo vp_icon( 'phone' ); // phpcs:ignore ?></div>
				<div>
					<p class="vp-contact-label"><?php esc_html_e( 'Phone', 'vitalpeptides' ); ?></p>
					<p class="vp-contact-value"><a href="tel:+18776257857">+1 (877) 625-7857</a></p>
				</div>
			</div>
			<div class="vp-contact-card">
				<div class="vp-contact-icon"><?php echo vp_icon( 'map-pin' ); // phpcs:ignore ?></div>
				<div>
					<p class="vp-contact-label"><?php esc_html_e( 'Business Address', 'vitalpeptides' ); ?></p>
					<p class="vp-contact-value">
						<?php esc_html_e( 'MPA Wellness LLC (DBA Vital Peptide Science)', 'vitalpeptides' ); ?><br>
						<?php esc_html_e( '[Business street address]', 'vitalpeptides' ); ?><br>
						<?php esc_html_e( '[City], [State] [ZIP], USA', 'vitalpeptides' ); ?>
					</p>
				</div>
			</div>
			<div class="vp-contact-card">
				<div class="vp-contact-icon"><?php echo vp_icon( 'clock' ); // phpcs:ignore ?></div>
				<div>
					<p class="vp-contact-label"><?php esc_html_e( 'Hours', 'vitalpeptides' ); ?></p>
					<p class="vp-contact-value"><?php esc_html_e( 'Mon-Fri, 9AM - 5PM EST', 'vitalpeptides' ); ?></p>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
