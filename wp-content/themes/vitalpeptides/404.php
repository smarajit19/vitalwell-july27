<?php
/**
 * 404 template — replicates pages/NotFound.tsx.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main class="vp-404">
	<div class="vp-404-inner">
		<h1>404</h1>
		<p><?php esc_html_e( 'Oops! Page not found', 'vitalpeptides' ); ?></p>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Return to Home', 'vitalpeptides' ); ?></a>
	</div>
</main>

<?php get_footer(); ?>
