<?php
/**
 * Front page — replicates pages/Index.tsx section order.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main>
	<?php
	get_template_part( 'template-parts/home/hero' );
	get_template_part( 'template-parts/home/trust-strip' );
	get_template_part( 'template-parts/home/featured-carousel' );
	get_template_part( 'template-parts/home/guarantee' );
	if ( ! is_user_logged_in() ) {
		get_template_part( 'template-parts/home/unlock-access' );
	}
	get_template_part( 'template-parts/home/features' );
	get_template_part( 'template-parts/home/quality' );
	get_template_part( 'template-parts/home/why-choose' );
	get_template_part( 'template-parts/home/faq' );
	get_template_part( 'template-parts/home/cta' );
	?>
</main>

<?php get_footer(); ?>
