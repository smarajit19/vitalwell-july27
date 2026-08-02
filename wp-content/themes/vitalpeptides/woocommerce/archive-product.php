<?php
/**
 * Shop archive — replicates pages/Shop.tsx (header, search + sort row, category chips, 4-col grid).
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

get_header();

$vp_shop_url   = get_permalink( wc_get_page_id( 'shop' ) );
$vp_current    = isset( $_GET['product_cat'] ) ? sanitize_title( wp_unslash( $_GET['product_cat'] ) ) : '';
$vp_orderby    = isset( $_GET['orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : 'popularity';
$vp_search     = get_search_query();

$vp_sort_labels = array(
	'popularity' => __( 'Most Popular', 'vitalpeptides' ),
	'title'      => __( 'A - Z', 'vitalpeptides' ),
	'title-desc' => __( 'Z - A', 'vitalpeptides' ),
	'price'      => __( 'Price: Low to High', 'vitalpeptides' ),
	'price-desc' => __( 'Price: High to Low', 'vitalpeptides' ),
);
$vp_sort_label  = isset( $vp_sort_labels[ $vp_orderby ] ) ? $vp_sort_labels[ $vp_orderby ] : $vp_sort_labels['popularity'];

$vp_cats = get_terms( array(
	'taxonomy'   => 'product_cat',
	'hide_empty' => true,
) );
?>

<main class="vp-shop">
	<div class="vp-container vp-shop-container">
		<div class="vp-shop-head fade-in-section">
			<h1><?php echo is_product_category() ? esc_html( single_term_title( '', false ) ) : esc_html__( 'All Products', 'vitalpeptides' ); ?></h1>
			<p><?php esc_html_e( 'Premium research peptides with 99%+ purity', 'vitalpeptides' ); ?></p>
		</div>

		<!-- Search & sort row -->
		<div class="vp-shop-toolbar">
			<form class="vp-shop-search" role="search" method="get" action="<?php echo esc_url( $vp_shop_url ); ?>">
				<?php echo vp_icon( 'search' ); // phpcs:ignore ?>
				<input type="search" name="s" value="<?php echo esc_attr( $vp_search ); ?>" placeholder="<?php esc_attr_e( 'Search products...', 'vitalpeptides' ); ?>">
				<input type="hidden" name="post_type" value="product">
			</form>

			<div class="vp-shop-sort">
				<button type="button" class="vp-sort-toggle" aria-expanded="false">
					<span class="vp-sort-label"><?php esc_html_e( 'Sort by:', 'vitalpeptides' ); ?></span>
					<span class="vp-sort-current"><?php echo esc_html( $vp_sort_label ); ?></span>
					<?php echo vp_icon( 'chevron-down' ); // phpcs:ignore ?>
				</button>
				<div class="vp-sort-menu" hidden>
					<?php foreach ( $vp_sort_labels as $vp_key => $vp_label ) : ?>
						<a href="<?php echo esc_url( add_query_arg( 'orderby', $vp_key ) ); ?>" class="<?php echo $vp_orderby === $vp_key ? 'is-active' : ''; ?>"><?php echo esc_html( $vp_label ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<!-- Product grid -->
		<?php if ( woocommerce_product_loop() ) : ?>
			<div class="vp-shop-grid">
				<?php
				while ( have_posts() ) {
					the_post();
					vp_product_card( wc_get_product( get_the_ID() ) );
				}
				?>
			</div>
			<div class="vp-shop-pagination">
				<?php woocommerce_pagination(); ?>
			</div>
		<?php else : ?>
			<div class="vp-shop-empty">
				<p><?php esc_html_e( 'No products found matching your criteria.', 'vitalpeptides' ); ?></p>
			</div>
		<?php endif; ?>

		<?php if ( ! is_user_logged_in() ) : ?>
			<div class="vp-shop-unlock fade-in-section">
				<?php echo vp_icon( 'lock' ); // phpcs:ignore ?>
				<h2><?php esc_html_e( 'Unlock Full Catalog', 'vitalpeptides' ); ?></h2>
				<p><?php esc_html_e( 'Sign in to access our complete collection of research peptides, exclusive member pricing, and members-only compounds.', 'vitalpeptides' ); ?></p>
				<a href="<?php echo esc_url( function_exists( 'vp_auth_url' ) ? vp_auth_url( 'signup' ) : get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>" class="vp-btn vp-btn--light"><?php esc_html_e( 'Create Account to Unlock', 'vitalpeptides' ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php get_footer(); ?>
