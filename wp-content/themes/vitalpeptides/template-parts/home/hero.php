<?php
/**
 * Home hero — replicates HeroSection.tsx (typing headline, floating bottles, molecular glow).
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

$vp_shop_url = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' );
$vp_img      = VP_THEME_URI . '/assets/images';
?>
<section class="vp-hero" aria-label="<?php esc_attr_e( 'Welcome - Premium Research Peptides', 'vitalpeptides' ); ?>">
	<div class="vp-hero-bg" aria-hidden="true"></div>

	<div class="vp-container vp-hero-container">
		<div class="vp-hero-grid hero-landscape-wrapper">
			<!-- Text content -->
			<div class="vp-hero-text hero-text-content">
				<h1 class="vp-hero-title">
					<span class="vp-typing-wrap">
						<span class="vp-typing-ghost" aria-hidden="true">Premium Peptides</span>
						<span class="vp-typing-live">
							<span class="vp-typed-text" data-words='["Premium Peptides","COA Verified","99% Purity"]'></span><span class="vp-typing-cursor"></span>
						</span>
					</span>
					<br>
					<span><?php esc_html_e( 'You Can Trust', 'vitalpeptides' ); ?></span>
				</h1>
				<p class="vp-hero-sub"><?php esc_html_e( 'Elevate your research with our lab-verified peptides. Made for purity, tested for precision, and fulfilled in 24hrs.', 'vitalpeptides' ); ?></p>
				<div class="vp-hero-ctas">
					<a href="<?php echo esc_url( add_query_arg( 'orderby', 'popularity', $vp_shop_url ) ); ?>" class="vp-btn vp-btn--ghost"><?php esc_html_e( 'Shop Best Sellers', 'vitalpeptides' ); ?></a>
					<a href="<?php echo esc_url( $vp_shop_url ); ?>" class="vp-btn vp-btn--primary vp-btn--arrow">
						<?php esc_html_e( 'Shop Now', 'vitalpeptides' ); ?>
						<svg width="24" height="10" viewBox="0 0 45 15" fill="none" aria-hidden="true"><path d="M44.7071 8.20711C45.0976 7.81658 45.0976 7.18342 44.7071 6.79289L38.3431 0.428932C37.9526 0.0384078 37.3195 0.0384078 36.9289 0.428932C36.5384 0.819457 36.5384 1.45262 36.9289 1.84315L42.5858 7.5L36.9289 13.1569C36.5384 13.5474 36.5384 14.1805 36.9289 14.5711C37.3195 14.9616 37.9526 14.9616 38.3431 14.5711L44.7071 8.20711ZM0 8.5H44V6.5H0V8.5Z" fill="currentColor"/></svg>
					</a>
				</div>
			</div>

			<!-- Floating bottles -->
			<div class="vp-hero-bottles hero-bottles-container" role="img" aria-label="<?php esc_attr_e( 'Featured products showcase', 'vitalpeptides' ); ?>">
				<div class="vp-hero-bottles-inner">
					<div class="vp-hero-glow" aria-hidden="true">
						<div class="vp-hero-glow-mask">
							<img src="<?php echo esc_url( $vp_img . '/molecular-glow.png' ); ?>" alt="" width="800" height="800" loading="eager">
							<div class="vp-hero-glow-overlay"></div>
						</div>
					</div>

					<div class="vp-hero-tag vp-hero-tag--coa"><span><?php esc_html_e( 'COA Verified', 'vitalpeptides' ); ?></span></div>
					<div class="vp-hero-tag vp-hero-tag--purity"><span><?php esc_html_e( '99% Purity', 'vitalpeptides' ); ?></span></div>

					<div class="vp-hero-bottle vp-hero-bottle--tb500">
						<div class="vp-float-slow"><img src="<?php echo esc_url( $vp_img . '/TB500New.png' ); ?>" alt="TB-500 Peptide vial" width="500" height="750" loading="eager" draggable="false"></div>
					</div>
					<div class="vp-hero-bottle vp-hero-bottle--bacwater">
						<div class="vp-float-mid"><img src="<?php echo esc_url( $vp_img . '/g1-s-hero.png' ); ?>" alt="G1-S" width="500" height="650" loading="eager" draggable="false"></div>
					</div>
					<div class="vp-hero-bottle vp-hero-bottle--bpc157">
						<div class="vp-float-fast"><img src="<?php echo esc_url( $vp_img . '/BPC157New.png' ); ?>" alt="BPC-157 Peptide vial" width="500" height="750" loading="eager" draggable="false"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
