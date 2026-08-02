<?php
/**
 * Theme header — replicates Navbar.tsx (sticky glass navbar, centered links, icon actions).
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

$vp_shop_url = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' );
// Logged-in → My Account; logged-out → themed /auth page.
if ( is_user_logged_in() ) {
	$vp_account_url = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'myaccount' ) ) : admin_url( 'profile.php' );
} else {
	$vp_account_url = function_exists( 'vp_auth_url' ) ? vp_auth_url( 'login' ) : wp_login_url();
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="vp-header">
	<div class="vp-header-bar" role="banner">
		<nav class="vp-container vp-nav" aria-label="<?php esc_attr_e( 'Main navigation', 'vitalpeptides' ); ?>">
			<div class="vp-nav-left">
				<button type="button" class="vp-nav-icon-btn vp-mobile-toggle" aria-label="<?php esc_attr_e( 'Open menu', 'vitalpeptides' ); ?>" aria-expanded="false">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M3 12H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M3 6H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M3 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
				</button>
				<a class="vp-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Home', 'vitalpeptides' ); ?>">
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<img src="<?php echo esc_url( VP_THEME_URI . '/assets/images/LogoMain.svg' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php endif; ?>
				</a>
			</div>

			<div class="vp-nav-center">
				<a class="vp-nav-link" href="<?php echo esc_url( $vp_shop_url ); ?>"><?php esc_html_e( 'Products', 'vitalpeptides' ); ?><span class="vp-nav-underline"></span></a>
				<a class="vp-nav-link" href="<?php echo esc_url( home_url( '/research/' ) ); ?>"><?php esc_html_e( 'Research', 'vitalpeptides' ); ?><span class="vp-nav-underline"></span></a>
				<a class="vp-nav-link" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact us', 'vitalpeptides' ); ?><span class="vp-nav-underline"></span></a>
			</div>

			<div class="vp-nav-right">
				<button type="button" class="vp-nav-icon-btn vp-search-toggle" aria-label="<?php esc_attr_e( 'Search', 'vitalpeptides' ); ?>">
					<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="1.5"/><path d="m21 21-4.3-4.3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
				</button>
				<a class="vp-nav-icon-btn" href="<?php echo esc_url( $vp_account_url ); ?>" aria-label="<?php echo is_user_logged_in() ? esc_attr__( 'My Account', 'vitalpeptides' ) : esc_attr__( 'Sign in', 'vitalpeptides' ); ?>">
					<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
				</a>
				<button type="button" class="vp-nav-icon-btn vp-cart-toggle" aria-label="<?php esc_attr_e( 'Shopping cart', 'vitalpeptides' ); ?>">
					<svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M6 2L3 6V20C3 20.5304 3.21071 21.0391 3.58579 21.4142C3.96086 21.7893 4.46957 22 5 22H19C19.5304 22 20.0391 21.7893 20.4142 21.4142C20.7893 21.0391 21 20.5304 21 20V6L18 2H6Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 6H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 10C16 11.0609 15.5786 12.0783 14.8284 12.8284C14.0783 13.5786 13.0609 14 12 14C10.9391 14 9.92172 13.5786 9.17157 12.8284C8.42143 12.0783 8 11.0609 8 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
					<?php vp_cart_count_badge(); ?>
				</button>
			</div>
		</nav>

		<div class="vp-header-search" hidden>
			<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="vp-container vp-header-search-form">
				<?php echo vp_icon( 'search' ); // phpcs:ignore ?>
				<input type="search" name="s" placeholder="<?php esc_attr_e( 'Search products...', 'vitalpeptides' ); ?>" autocomplete="off">
				<input type="hidden" name="post_type" value="product">
				<button type="button" class="vp-search-close" aria-label="<?php esc_attr_e( 'Close search', 'vitalpeptides' ); ?>"><?php echo vp_icon( 'x' ); // phpcs:ignore ?></button>
			</form>
		</div>
	</div>

	<div class="vp-mobile-menu" hidden>
		<a href="<?php echo esc_url( $vp_shop_url ); ?>"><?php esc_html_e( 'Products', 'vitalpeptides' ); ?></a>
		<a href="<?php echo esc_url( home_url( '/research/' ) ); ?>"><?php esc_html_e( 'Research', 'vitalpeptides' ); ?></a>
		<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact us', 'vitalpeptides' ); ?></a>
		<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( $vp_account_url ); ?>"><?php esc_html_e( 'My Account', 'vitalpeptides' ); ?></a>
		<?php else : ?>
			<a href="<?php echo esc_url( function_exists( 'vp_auth_url' ) ? vp_auth_url( 'login' ) : wp_login_url() ); ?>"><?php esc_html_e( 'Sign In', 'vitalpeptides' ); ?></a>
			<a href="<?php echo esc_url( function_exists( 'vp_auth_url' ) ? vp_auth_url( 'signup' ) : wp_registration_url() ); ?>"><?php esc_html_e( 'Create Account', 'vitalpeptides' ); ?></a>
		<?php endif; ?>
	</div>
</header>
