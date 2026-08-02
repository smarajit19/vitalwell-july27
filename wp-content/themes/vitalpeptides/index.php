<?php
/**
 * Fallback template.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main class="vp-page">
	<section class="vp-page-hero">
		<div class="vp-container vp-page-hero-inner">
			<h1><?php echo is_search() ? esc_html( sprintf( __( 'Search results for "%s"', 'vitalpeptides' ), get_search_query() ) ) : esc_html( get_bloginfo( 'name' ) ); ?></h1>
		</div>
	</section>
	<section class="vp-page-body">
		<div class="vp-container-narrow">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<article <?php post_class( 'vp-prose-block' ); ?>>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<div class="vp-prose"><?php the_excerpt(); ?></div>
					</article>
				<?php endwhile; ?>
				<?php the_posts_pagination(); ?>
			<?php else : ?>
				<p class="vp-empty-note"><?php esc_html_e( 'Nothing found.', 'vitalpeptides' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
