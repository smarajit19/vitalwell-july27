<?php
/**
 * Default page template (dark hero + prose body).
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main class="vp-page">
	<?php while ( have_posts() ) : the_post(); ?>
		<section class="vp-page-hero">
			<div class="vp-container vp-page-hero-inner">
				<h1><?php the_title(); ?></h1>
			</div>
		</section>
		<section class="vp-page-body">
			<div class="vp-container-narrow">
				<div class="vp-prose"><?php the_content(); ?></div>
			</div>
		</section>
	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
