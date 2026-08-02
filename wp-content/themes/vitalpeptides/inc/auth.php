<?php
/**
 * Vital Peptides — Authentication (custom signup + login).
 *
 * Provides a themed /auth page (see page-auth.php) with signup and login
 * views, and processes both server-side. Signup creates a WooCommerce
 * customer and logs them straight in. Works alongside the gated checkout:
 * the checkout page links non-registered visitors here.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

/* --------------------------------------------------------------------------
 * Make sure account registration is enabled (safety net for the settings).
 * ------------------------------------------------------------------------ */
add_filter( 'pre_option_users_can_register', function ( $value ) {
	return $value ? $value : 1;
} );
add_filter( 'pre_option_woocommerce_enable_myaccount_registration', function () {
	return 'yes';
} );

/**
 * URL of the auth page, with an optional view (login|signup) and redirect.
 */
function vp_auth_url( $view = 'login', $redirect = '' ) {
	$args = array( 'view' => $view );
	if ( $redirect ) {
		$args['redirect_to'] = rawurlencode( $redirect );
	}
	return add_query_arg( $args, home_url( '/auth/' ) );
}

/**
 * Store a transient auth error/notice to show back on the auth page.
 */
function vp_auth_set_error( $message ) {
	if ( ! headers_sent() ) {
		setcookie( 'vp_auth_msg', $message, time() + 60, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN );
	}
	$GLOBALS['vp_auth_msg'] = $message;
}

/**
 * Read (and clear) the auth message cookie.
 */
function vp_auth_get_error() {
	if ( isset( $GLOBALS['vp_auth_msg'] ) ) {
		return $GLOBALS['vp_auth_msg'];
	}
	if ( ! empty( $_COOKIE['vp_auth_msg'] ) ) {
		$msg = sanitize_text_field( wp_unslash( $_COOKIE['vp_auth_msg'] ) );
		if ( ! headers_sent() ) {
			setcookie( 'vp_auth_msg', '', time() - 3600, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN );
		}
		return $msg;
	}
	return '';
}

/* --------------------------------------------------------------------------
 * Process signup + login submissions early (before any output).
 * ------------------------------------------------------------------------ */
add_action( 'template_redirect', 'vp_process_auth', 5 );
function vp_process_auth() {
	if ( empty( $_POST['vp_auth_action'] ) ) {
		return;
	}
	$action   = sanitize_text_field( wp_unslash( $_POST['vp_auth_action'] ) );
	$redirect = isset( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : '';
	if ( ! $redirect ) {
		$redirect = wc_get_page_permalink( 'myaccount' );
	}

	// -------- Sign up --------
	if ( 'signup' === $action ) {
		if ( ! isset( $_POST['vp_auth_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vp_auth_nonce'] ) ), 'vp_signup' ) ) {
			vp_auth_set_error( __( 'Security check failed. Please try again.', 'vitalpeptides' ) );
			wp_safe_redirect( vp_auth_url( 'signup', $redirect ) );
			exit;
		}
		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$pass  = isset( $_POST['password'] ) ? (string) wp_unslash( $_POST['password'] ) : '';
		$pass2 = isset( $_POST['password2'] ) ? (string) wp_unslash( $_POST['password2'] ) : '';

		if ( ! is_email( $email ) ) {
			vp_auth_set_error( __( 'Please enter a valid email address.', 'vitalpeptides' ) );
			wp_safe_redirect( vp_auth_url( 'signup', $redirect ) );
			exit;
		}
		if ( strlen( $pass ) < 8 ) {
			vp_auth_set_error( __( 'Password must be at least 8 characters.', 'vitalpeptides' ) );
			wp_safe_redirect( add_query_arg( 'email', rawurlencode( $email ), vp_auth_url( 'signup', $redirect ) ) );
			exit;
		}
		if ( $pass !== $pass2 ) {
			vp_auth_set_error( __( 'Passwords do not match.', 'vitalpeptides' ) );
			wp_safe_redirect( add_query_arg( 'email', rawurlencode( $email ), vp_auth_url( 'signup', $redirect ) ) );
			exit;
		}

		$customer_id = wc_create_new_customer( $email, '', $pass );
		if ( is_wp_error( $customer_id ) ) {
			vp_auth_set_error( $customer_id->get_error_message() );
			wp_safe_redirect( add_query_arg( 'email', rawurlencode( $email ), vp_auth_url( 'signup', $redirect ) ) );
			exit;
		}

		wc_set_customer_auth_cookie( $customer_id );
		wp_safe_redirect( $redirect );
		exit;
	}

	// -------- Log in --------
	if ( 'login' === $action ) {
		if ( ! isset( $_POST['vp_auth_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vp_auth_nonce'] ) ), 'vp_login' ) ) {
			vp_auth_set_error( __( 'Security check failed. Please try again.', 'vitalpeptides' ) );
			wp_safe_redirect( vp_auth_url( 'login', $redirect ) );
			exit;
		}
		$creds = array(
			'user_login'    => isset( $_POST['username'] ) ? sanitize_text_field( wp_unslash( $_POST['username'] ) ) : '',
			'user_password' => isset( $_POST['password'] ) ? (string) wp_unslash( $_POST['password'] ) : '',
			'remember'      => ! empty( $_POST['remember'] ),
		);
		$user = wp_signon( $creds, is_ssl() );
		if ( is_wp_error( $user ) ) {
			vp_auth_set_error( __( 'Invalid email/username or password.', 'vitalpeptides' ) );
			wp_safe_redirect( vp_auth_url( 'login', $redirect ) );
			exit;
		}
		wp_safe_redirect( $redirect );
		exit;
	}
}

/**
 * Logged-in users have no reason to see the auth page — send them on.
 */
add_action( 'template_redirect', 'vp_auth_redirect_logged_in' );
function vp_auth_redirect_logged_in() {
	if ( is_page( 'auth' ) && is_user_logged_in() ) {
		$redirect = isset( $_GET['redirect_to'] ) ? esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) : wc_get_page_permalink( 'myaccount' ); // phpcs:ignore WordPress.Security.NonceVerification
		wp_safe_redirect( $redirect ? $redirect : home_url( '/' ) );
		exit;
	}
}

/* --------------------------------------------------------------------------
 * On the checkout page, suppress WooCommerce's default login-reminder form so
 * guests see only our styled Sign-In / Create-Account gate (avoids two
 * competing login prompts). The coupon field is left intact.
 * ------------------------------------------------------------------------ */
add_action( 'wp', function () {
	if ( function_exists( 'is_checkout' ) && is_checkout() && ! is_user_logged_in() ) {
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
	}
} );

/* --------------------------------------------------------------------------
 * Point the WooCommerce "My Account" login form at our styled /auth page,
 * and add a signup link on it. (My Account still works if reached directly.)
 * ------------------------------------------------------------------------ */
add_action( 'woocommerce_login_form_start', 'vp_myaccount_auth_hint' );
function vp_myaccount_auth_hint() {
	if ( is_user_logged_in() ) {
		return;
	}
	echo '<p class="vp-myaccount-authhint">' . wp_kses_post(
		sprintf(
			/* translators: %s: signup URL */
			__( 'New researcher? <a href="%s">Create an account</a>.', 'vitalpeptides' ),
			esc_url( vp_auth_url( 'signup' ) )
		)
	) . '</p>';
}
