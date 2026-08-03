<?php
/**
 * Email OTP verification page.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() ) {
	wp_safe_redirect( vp_auth_url( 'login', wc_get_checkout_url() ) );
	exit;
}

$vp_redirect = isset( $_GET['redirect_to'] ) ? vp_auth_redirect_target( esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) ) : vp_auth_redirect_target(); // phpcs:ignore WordPress.Security.NonceVerification
if ( vp_email_otp_session_is_verified() ) {
	wp_safe_redirect( $vp_redirect );
	exit;
}

$vp_user   = wp_get_current_user();
$vp_notice = vp_email_otp_get_notice();

get_header();
?>
<main class="vp-auth-page vp-otp-page">
	<div class="vp-auth-card">
		<img class="vp-auth-logo" src="<?php echo esc_url( VP_THEME_URI . '/assets/images/LogoMain.svg' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
		<h1 class="vp-auth-title"><?php esc_html_e( 'Verify your email', 'vitalpeptides' ); ?></h1>
		<p class="vp-auth-sub"><?php printf( esc_html__( 'Enter the six-digit code sent to %s.', 'vitalpeptides' ), esc_html( $vp_user->user_email ) ); ?></p>

		<?php if ( ! empty( $vp_notice['message'] ) ) : ?>
			<div class="vp-auth-error <?php echo 'success' === $vp_notice['type'] ? 'is-success' : ''; ?>" role="status"><?php echo esc_html( $vp_notice['message'] ); ?></div>
		<?php endif; ?>

		<form class="vp-auth-form" method="post" action="<?php echo esc_url( home_url( '/verify-email/' ) ); ?>">
			<input type="hidden" name="vp_email_otp_action" value="verify">
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $vp_redirect ); ?>">
			<?php wp_nonce_field( 'vp_email_otp', 'vp_email_otp_nonce' ); ?>
			<label class="vp-auth-field">
				<span><?php esc_html_e( 'Verification code', 'vitalpeptides' ); ?></span>
				<input class="vp-otp-input" type="text" name="otp_code" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" required placeholder="000000" aria-describedby="vp-otp-help">
			</label>
			<p id="vp-otp-help" class="vp-otp-help"><?php esc_html_e( 'The code expires after 10 minutes.', 'vitalpeptides' ); ?></p>
			<button type="submit" class="vp-btn vp-btn--gradient vp-auth-submit"><?php esc_html_e( 'Verify email', 'vitalpeptides' ); ?></button>
		</form>

		<form class="vp-otp-resend" method="post" action="<?php echo esc_url( home_url( '/verify-email/' ) ); ?>">
			<input type="hidden" name="vp_email_otp_action" value="resend">
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $vp_redirect ); ?>">
			<?php wp_nonce_field( 'vp_email_otp', 'vp_email_otp_nonce' ); ?>
			<button type="submit" class="vp-otp-resend-button"><?php esc_html_e( 'Resend code', 'vitalpeptides' ); ?></button>
		</form>
	</div>
</main>
<?php get_footer(); ?>
