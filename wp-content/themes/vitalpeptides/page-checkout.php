<?php
/**
 * Checkout page template — full-width, themed layout for the classic
 * [woocommerce_checkout] shortcut (replaces the narrow default page.php).
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main class="vp-checkout-page">
	<div class="vp-container">
		<div class="vp-checkout-body">
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
			?>
		</div>
	</div>
</main>

<?php get_footer(); ?>
