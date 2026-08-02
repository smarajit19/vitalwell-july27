<?php
/**
 * Unlock access section — replicates UnlockAccessSection.tsx (guests only).
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

$vp_signup_url = function_exists( 'vp_auth_url' ) ? vp_auth_url( 'signup' ) : wp_registration_url();
$vp_login_url  = function_exists( 'vp_auth_url' ) ? vp_auth_url( 'login' ) : wp_login_url();
?>
<section class="vp-unlock" aria-label="<?php esc_attr_e( 'Get full access', 'vitalpeptides' ); ?>">
	<div class="vp-container">
		<div class="vp-unlock-inner">
			<div class="vp-unlock-badge"><?php echo vp_icon( 'lock' ); // phpcs:ignore ?><span><?php esc_html_e( 'Members Only', 'vitalpeptides' ); ?></span></div>
			<h2><?php esc_html_e( 'Unlock Full Access to Our Store', 'vitalpeptides' ); ?></h2>
			<p><?php esc_html_e( 'Sign in or create an account to browse our complete catalog of research-grade peptides, access exclusive member pricing, and join our community of researchers.', 'vitalpeptides' ); ?></p>
			<div class="vp-unlock-ctas">
				<a href="<?php echo esc_url( $vp_signup_url ); ?>" class="vp-btn vp-btn--primary vp-btn--arrow">
					<?php esc_html_e( 'Create Account', 'vitalpeptides' ); ?>
					<svg width="24" height="10" viewBox="0 0 45 15" fill="none" aria-hidden="true"><path d="M44.7071 8.20711C45.0976 7.81658 45.0976 7.18342 44.7071 6.79289L38.3431 0.428932C37.9526 0.0384078 37.3195 0.0384078 36.9289 0.428932C36.5384 0.819457 36.5384 1.45262 36.9289 1.84315L42.5858 7.5L36.9289 13.1569C36.5384 13.5474 36.5384 14.1805 36.9289 14.5711C37.3195 14.9616 37.9526 14.9616 38.3431 14.5711L44.7071 8.20711ZM0 8.5H44V6.5H0V8.5Z" fill="currentColor"/></svg>
				</a>
				<a href="<?php echo esc_url( $vp_login_url ); ?>" class="vp-btn vp-btn--ghost"><?php esc_html_e( 'Sign In', 'vitalpeptides' ); ?></a>
			</div>
		</div>
	</div>
</section>
