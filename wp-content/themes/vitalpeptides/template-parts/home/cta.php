<?php
/**
 * CTA + newsletter overlap — replicates CTASection.tsx.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

$vp_shop_url = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' );
$vp_img      = VP_THEME_URI . '/assets/images';
?>
<div class="vp-cta-wrap">
	<section class="vp-cta" aria-label="<?php esc_attr_e( 'Call to action', 'vitalpeptides' ); ?>">
		<div class="vp-cta-bottle vp-cta-bottle--left">
			<div><img src="<?php echo esc_url( $vp_img . '/bpc-157-cta.png' ); ?>" alt="BPC-157 peptide vial" loading="lazy"></div>
		</div>
		<div class="vp-cta-bottle vp-cta-bottle--right">
			<div><img src="<?php echo esc_url( $vp_img . '/tb-500-cta.png' ); ?>" alt="TB-500 peptide vial" loading="lazy"></div>
		</div>

		<div class="vp-container vp-cta-inner">
			<div class="vp-cta-copy">
				<h2>
					<?php esc_html_e( 'All the research peptides you need, with the', 'vitalpeptides' ); ?>
					<span class="vp-cta-highlight"><span><?php esc_html_e( 'peace of mind', 'vitalpeptides' ); ?></span><span class="vp-cta-underline" aria-hidden="true"></span></span>
					<?php esc_html_e( 'and research community at your fingertips.', 'vitalpeptides' ); ?>
				</h2>
				<a href="<?php echo esc_url( $vp_shop_url ); ?>" class="vp-btn vp-btn--primary vp-btn--arrow vp-cta-btn">
					<?php esc_html_e( 'Shop Now', 'vitalpeptides' ); ?>
					<svg width="32" height="10" viewBox="0 0 45 12" fill="none" aria-hidden="true"><path d="M44.5303 6.53033C44.8232 6.23744 44.8232 5.76256 44.5303 5.46967L39.7574 0.696699C39.4645 0.403806 38.9896 0.403806 38.6967 0.696699C38.4038 0.989593 38.4038 1.46447 38.6967 1.75736L42.9393 6L38.6967 10.2426C38.4038 10.5355 38.4038 11.0104 38.6967 11.3033C38.9896 11.5962 39.4645 11.5962 39.7574 11.3033L44.5303 6.53033ZM0 6.75H44V5.25H0V6.75Z" fill="white"/></svg>
				</a>
			</div>
		</div>
	</section>

	<!-- Newsletter overlap -->
	<div class="vp-newsletter-wrap">
		<div class="vp-container">
			<div class="vp-newsletter">
				<div class="vp-newsletter-row">
					<div class="vp-newsletter-copy">
						<h3><?php esc_html_e( 'Stay Updated with Vital Peptides', 'vitalpeptides' ); ?></h3>
						<p><?php esc_html_e( 'Subscribe to our newsletter for new product availability and research updates', 'vitalpeptides' ); ?></p>
						<p class="vp-newsletter-note"><?php esc_html_e( 'Join', 'vitalpeptides' ); ?> <strong>10,000+</strong> <?php esc_html_e( 'subscribers. No spam, unsubscribe anytime.', 'vitalpeptides' ); ?></p>
					</div>
					<div class="vp-newsletter-form-wrap">
						<form class="vp-newsletter-form">
							<div class="vp-newsletter-pill">
								<input type="email" name="email" placeholder="<?php esc_attr_e( 'Enter your email', 'vitalpeptides' ); ?>" required>
								<button type="submit"><?php esc_html_e( 'Subscribe', 'vitalpeptides' ); ?></button>
							</div>
							<p class="vp-newsletter-msg" role="status" hidden></p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
