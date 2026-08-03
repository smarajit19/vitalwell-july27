<?php
/**
 * Email OTP verification for customer registration and login.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

define( 'VP_EMAIL_OTP_TTL', 10 * MINUTE_IN_SECONDS );
define( 'VP_EMAIL_OTP_RESEND_WAIT', MINUTE_IN_SECONDS );
define( 'VP_EMAIL_OTP_MAX_ATTEMPTS', 5 );

function vp_is_email_verified( $user_id = 0 ) {
	$user_id = $user_id ? absint( $user_id ) : get_current_user_id();
	return $user_id && '1' === get_user_meta( $user_id, '_vp_email_verified', true );
}

/**
 * Keep the OTP approval scoped to the current WordPress login session. This
 * makes a new email code necessary whenever a customer signs in again.
 */
function vp_email_otp_session_key( $user_id = 0 ) {
	$user_id = $user_id ? absint( $user_id ) : get_current_user_id();
	$token   = wp_get_session_token();

	if ( ! $user_id || ! $token ) {
		return '';
	}

	return 'vp_email_otp_session_' . $user_id . '_' . hash( 'sha256', $token );
}

function vp_email_otp_session_is_verified( $user_id = 0 ) {
	$key = vp_email_otp_session_key( $user_id );
	return $key && '1' === get_transient( $key );
}

function vp_email_otp_clear_session( $user_id = 0 ) {
	$key = vp_email_otp_session_key( $user_id );
	if ( $key ) {
		delete_transient( $key );
	}
}

function vp_email_otp_verify_session( $user_id = 0 ) {
	$key = vp_email_otp_session_key( $user_id );
	if ( $key ) {
		set_transient( $key, '1', MONTH_IN_SECONDS );
	}
}

function vp_email_otp_page_url( $redirect = '' ) {
	$url = home_url( '/verify-email/' );
	if ( $redirect ) {
		$url = add_query_arg( 'redirect_to', vp_auth_redirect_target( $redirect ), $url );
	}
	return $url;
}

/**
 * Serve the verification screen directly so the flow does not depend on a
 * manually created WordPress page or a database page record.
 */
add_action( 'template_redirect', 'vp_render_email_otp_page', 20 );
function vp_render_email_otp_page() {
	$request_path = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';
	$otp_path     = wp_parse_url( home_url( '/verify-email/' ), PHP_URL_PATH );
	if ( untrailingslashit( (string) $request_path ) !== untrailingslashit( (string) $otp_path ) ) {
		return;
	}

	status_header( 200 );
	nocache_headers();
	require get_template_directory() . '/page-verify-email.php';
	exit;
}

function vp_email_otp_set_notice( $message, $type = 'error' ) {
	set_transient( 'vp_email_otp_notice_' . get_current_user_id(), array( 'message' => $message, 'type' => $type ), 2 * MINUTE_IN_SECONDS );
}

function vp_email_otp_get_notice() {
	$key    = 'vp_email_otp_notice_' . get_current_user_id();
	$notice = get_transient( $key );
	delete_transient( $key );
	return is_array( $notice ) ? $notice : array();
}

/**
 * Issue and email a new code. The raw code is never saved to the database.
 */
function vp_email_otp_send( $user_id, $force = false ) {
	$user_id = absint( $user_id );
	$user    = get_userdata( $user_id );
	if ( ! $user ) {
		return new WP_Error( 'vp_otp_user', __( 'This account cannot be verified.', 'vitalpeptides' ) );
	}

	$sent_at = absint( get_user_meta( $user_id, '_vp_email_otp_sent_at', true ) );
	if ( ! $force && $sent_at && ( time() - $sent_at ) < VP_EMAIL_OTP_RESEND_WAIT ) {
		return new WP_Error( 'vp_otp_throttled', __( 'A code was sent recently. Please wait one minute before requesting another.', 'vitalpeptides' ) );
	}

	try {
		$code = (string) random_int( 100000, 999999 );
	} catch ( Exception $exception ) {
		return new WP_Error( 'vp_otp_generation', __( 'Unable to generate a verification code. Please try again.', 'vitalpeptides' ) );
	}

	$subject = sprintf( __( '%s email verification code', 'vitalpeptides' ), wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) );
	$message = sprintf(
		/* translators: 1: customer name, 2: OTP code, 3: expiry minutes */
		__( 'Hello %1$s,\n\nYour email verification code is: %2$s\n\nThis code expires in %3$d minutes. Do not share it with anyone.\n', 'vitalpeptides' ),
		$user->display_name ? $user->display_name : $user->user_email,
		$code,
		(int) ( VP_EMAIL_OTP_TTL / MINUTE_IN_SECONDS )
	);

	if ( ! wp_mail( $user->user_email, $subject, $message ) ) {
		return new WP_Error( 'vp_otp_mail', __( 'We could not send the verification email. Please configure SMTP and try again.', 'vitalpeptides' ) );
	}

	update_user_meta( $user_id, '_vp_email_otp_hash', wp_hash_password( $code ) );
	update_user_meta( $user_id, '_vp_email_otp_expires', time() + VP_EMAIL_OTP_TTL );
	update_user_meta( $user_id, '_vp_email_otp_attempts', 0 );
	update_user_meta( $user_id, '_vp_email_otp_sent_at', time() );
	return true;
}

/**
 * Start the OTP step after registration or login.
 */
function vp_email_otp_start( $user_id, $redirect = '' ) {
	// A new login must receive a new code, even if a previous code was sent
	// recently or the account completed verification on an earlier login.
	vp_email_otp_clear_session( $user_id );
	$result = vp_email_otp_send( $user_id, true );
	if ( is_wp_error( $result ) && 'vp_otp_throttled' !== $result->get_error_code() ) {
		vp_email_otp_set_notice( $result->get_error_message() );
	} elseif ( ! is_wp_error( $result ) ) {
		vp_email_otp_set_notice( __( 'We sent a six-digit verification code to your email address.', 'vitalpeptides' ), 'success' );
	}
	wp_safe_redirect( vp_email_otp_page_url( $redirect ) );
	exit;
}

/**
 * Prevent an authenticated but unverified customer from bypassing the OTP
 * screen by opening the checkout URL directly.
 */
add_action( 'template_redirect', 'vp_email_otp_gate_checkout', 6 );
function vp_email_otp_gate_checkout() {
	if ( ! function_exists( 'is_checkout' ) || ! is_checkout() || ! is_user_logged_in() || vp_email_otp_session_is_verified() ) {
		return;
	}
	if ( function_exists( 'is_order_received_page' ) && is_order_received_page() ) {
		return;
	}

	vp_email_otp_start( get_current_user_id(), wc_get_checkout_url() );
}

/**
 * Verify or resend an OTP before rendering the page.
 */
add_action( 'template_redirect', 'vp_process_email_otp', 4 );
function vp_process_email_otp() {
	if ( empty( $_POST['vp_email_otp_action'] ) ) {
		return;
	}

	$redirect = isset( $_POST['redirect_to'] ) ? vp_auth_redirect_target( esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) ) : vp_auth_redirect_target();
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( vp_auth_url( 'login', $redirect ) );
		exit;
	}
	if ( ! isset( $_POST['vp_email_otp_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vp_email_otp_nonce'] ) ), 'vp_email_otp' ) ) {
		vp_email_otp_set_notice( __( 'Security check failed. Please try again.', 'vitalpeptides' ) );
		wp_safe_redirect( vp_email_otp_page_url( $redirect ) );
		exit;
	}

	$user_id = get_current_user_id();
	$action  = sanitize_key( wp_unslash( $_POST['vp_email_otp_action'] ) );
	if ( vp_email_otp_session_is_verified( $user_id ) ) {
		wp_safe_redirect( $redirect );
		exit;
	}

	if ( 'resend' === $action ) {
		$result = vp_email_otp_send( $user_id );
		vp_email_otp_set_notice( is_wp_error( $result ) ? $result->get_error_message() : __( 'A new verification code was sent.', 'vitalpeptides' ), is_wp_error( $result ) ? 'error' : 'success' );
		wp_safe_redirect( vp_email_otp_page_url( $redirect ) );
		exit;
	}

	$code     = isset( $_POST['otp_code'] ) ? preg_replace( '/\D+/', '', (string) wp_unslash( $_POST['otp_code'] ) ) : '';
	$expires  = absint( get_user_meta( $user_id, '_vp_email_otp_expires', true ) );
	$attempts = absint( get_user_meta( $user_id, '_vp_email_otp_attempts', true ) );
	$hash     = get_user_meta( $user_id, '_vp_email_otp_hash', true );

	if ( $attempts >= VP_EMAIL_OTP_MAX_ATTEMPTS ) {
		vp_email_otp_set_notice( __( 'Too many incorrect attempts. Request a new code and try again.', 'vitalpeptides' ) );
	} elseif ( ! $expires || time() > $expires ) {
		vp_email_otp_set_notice( __( 'This code has expired. Request a new code and try again.', 'vitalpeptides' ) );
	} elseif ( 6 !== strlen( $code ) || ! $hash || ! wp_check_password( $code, $hash ) ) {
		update_user_meta( $user_id, '_vp_email_otp_attempts', $attempts + 1 );
		vp_email_otp_set_notice( __( 'That verification code is incorrect. Please try again.', 'vitalpeptides' ) );
	} else {
		update_user_meta( $user_id, '_vp_email_verified', '1' );
		vp_email_otp_verify_session( $user_id );
		delete_user_meta( $user_id, '_vp_email_otp_hash' );
		delete_user_meta( $user_id, '_vp_email_otp_expires' );
		delete_user_meta( $user_id, '_vp_email_otp_attempts' );
		delete_user_meta( $user_id, '_vp_email_otp_sent_at' );
		wp_safe_redirect( $redirect );
		exit;
	}

	wp_safe_redirect( vp_email_otp_page_url( $redirect ) );
	exit;
}
