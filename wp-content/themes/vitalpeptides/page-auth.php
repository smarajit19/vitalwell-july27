<?php
/**
 * Auth page — themed signup / login card.
 * URL pattern: /auth/?view=signup|login&email=&redirect_to=
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

get_header();

$vp_view     = isset( $_GET['view'] ) ? sanitize_key( wp_unslash( $_GET['view'] ) ) : 'login'; // phpcs:ignore WordPress.Security.NonceVerification
$vp_view     = in_array( $vp_view, array( 'login', 'signup' ), true ) ? $vp_view : 'login';
$vp_email    = isset( $_GET['email'] ) ? sanitize_email( wp_unslash( $_GET['email'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
$vp_redirect = isset( $_GET['redirect_to'] ) ? esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
$vp_error    = function_exists( 'vp_auth_get_error' ) ? vp_auth_get_error() : '';
$vp_is_signup = ( 'signup' === $vp_view );
?>

<main class="vp-auth-page">
	<div class="vp-auth-card">
		<img class="vp-auth-logo" src="<?php echo esc_url( VP_THEME_URI . '/assets/images/LogoMain.svg' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">

		<?php if ( $vp_is_signup ) : ?>
			<h1 class="vp-auth-title"><?php esc_html_e( 'Join Vital Peptide Science', 'vitalpeptides' ); ?></h1>
			<p class="vp-auth-sub"><?php esc_html_e( 'Create your researcher account to access the full catalog and place orders.', 'vitalpeptides' ); ?></p>
		<?php else : ?>
			<h1 class="vp-auth-title"><?php esc_html_e( 'Welcome back', 'vitalpeptides' ); ?></h1>
			<p class="vp-auth-sub"><?php esc_html_e( 'Sign in to your researcher account.', 'vitalpeptides' ); ?></p>
		<?php endif; ?>

		<?php if ( $vp_error ) : ?>
			<div class="vp-auth-error" role="alert"><?php echo esc_html( $vp_error ); ?></div>
		<?php endif; ?>

		<?php if ( $vp_is_signup ) : ?>

			<form class="vp-auth-form" method="post" action="<?php echo esc_url( home_url( '/auth/' ) ); ?>">
				<input type="hidden" name="vp_auth_action" value="signup">
				<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $vp_redirect ); ?>">
				<?php wp_nonce_field( 'vp_signup', 'vp_auth_nonce' ); ?>

				<label class="vp-auth-field">
					<span><?php esc_html_e( 'Email address', 'vitalpeptides' ); ?></span>
					<input type="email" name="email" value="<?php echo esc_attr( $vp_email ); ?>" required autocomplete="email" placeholder="you@lab.com">
				</label>
				<label class="vp-auth-field">
					<span><?php esc_html_e( 'Password', 'vitalpeptides' ); ?></span>
					<input type="password" name="password" required autocomplete="new-password" minlength="8" placeholder="<?php esc_attr_e( 'At least 8 characters', 'vitalpeptides' ); ?>">
				</label>
				<label class="vp-auth-field">
					<span><?php esc_html_e( 'Confirm password', 'vitalpeptides' ); ?></span>
					<input type="password" name="password2" required autocomplete="new-password" minlength="8" placeholder="<?php esc_attr_e( 'Re-enter password', 'vitalpeptides' ); ?>">
				</label>

				<button type="submit" class="vp-btn vp-btn--gradient vp-auth-submit"><?php esc_html_e( 'Create Account', 'vitalpeptides' ); ?></button>
			</form>

			<p class="vp-auth-switch">
				<?php esc_html_e( 'Already have an account?', 'vitalpeptides' ); ?>
				<a href="<?php echo esc_url( vp_auth_url( 'login', $vp_redirect ) ); ?>"><?php esc_html_e( 'Sign In', 'vitalpeptides' ); ?></a>
			</p>

		<?php else : ?>

			<form class="vp-auth-form" method="post" action="<?php echo esc_url( home_url( '/auth/' ) ); ?>">
				<input type="hidden" name="vp_auth_action" value="login">
				<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $vp_redirect ); ?>">
				<?php wp_nonce_field( 'vp_login', 'vp_auth_nonce' ); ?>

				<label class="vp-auth-field">
					<span><?php esc_html_e( 'Email or username', 'vitalpeptides' ); ?></span>
					<input type="text" name="username" value="<?php echo esc_attr( $vp_email ); ?>" required autocomplete="username" placeholder="you@lab.com">
				</label>
				<label class="vp-auth-field">
					<span><?php esc_html_e( 'Password', 'vitalpeptides' ); ?></span>
					<input type="password" name="password" required autocomplete="current-password">
				</label>
				<label class="vp-auth-remember">
					<input type="checkbox" name="remember" value="1"> <?php esc_html_e( 'Keep me signed in', 'vitalpeptides' ); ?>
				</label>

				<button type="submit" class="vp-btn vp-btn--gradient vp-auth-submit"><?php esc_html_e( 'Sign In', 'vitalpeptides' ); ?></button>
			</form>

			<p class="vp-auth-switch">
				<?php esc_html_e( "Don't have an account?", 'vitalpeptides' ); ?>
				<a href="<?php echo esc_url( vp_auth_url( 'signup', $vp_redirect ) ); ?>"><?php esc_html_e( 'Create one', 'vitalpeptides' ); ?></a>
			</p>
			<p class="vp-auth-lost"><a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot your password?', 'vitalpeptides' ); ?></a></p>

		<?php endif; ?>

		<p class="vp-auth-fine"><?php esc_html_e( 'For qualified researchers, 21+. All products are for laboratory research use only — not for human or animal consumption.', 'vitalpeptides' ); ?></p>
	</div>
</main>

<?php
get_footer();
