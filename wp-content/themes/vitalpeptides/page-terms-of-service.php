<?php
/**
 * Terms of Service page.
 *
 * The approved terms are maintained as a standalone source document. This
 * template renders its main content inside the active WordPress theme so the
 * site header, navigation, footer, and WordPress hooks remain available.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

$vp_terms_style_path = get_template_directory() . '/assets/css/retriever-pay-terms.css';

wp_enqueue_style(
	'vp-retriever-pay-terms',
	get_template_directory_uri() . '/assets/css/retriever-pay-terms.css',
	array( 'vp-main' ),
	file_exists( $vp_terms_style_path ) ? filemtime( $vp_terms_style_path ) : null
);

get_header();

$vp_terms_source_path = get_template_directory() . '/assets/legal/retriever-pay-terms.html';
$vp_terms_document    = is_readable( $vp_terms_source_path ) ? file_get_contents( $vp_terms_source_path ) : false;
$vp_terms_markup      = '';

if ( is_string( $vp_terms_document ) && preg_match( '/<main\\b[^>]*>.*?<\\/main>/is', $vp_terms_document, $vp_terms_match ) ) {
	$vp_terms_markup = $vp_terms_match[0];
}
?>

<section class="vp-retriever-terms" aria-label="<?php esc_attr_e( 'Terms and Conditions', 'vitalpeptides' ); ?>">
	<?php if ( $vp_terms_markup ) : ?>
		<?php echo $vp_terms_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted, theme-maintained legal source. ?>
	<?php else : ?>
		<div class="wrap">
			<p><?php esc_html_e( 'The Terms and Conditions document is temporarily unavailable. Please contact us for assistance.', 'vitalpeptides' ); ?></p>
		</div>
	<?php endif; ?>
</section>

<?php get_footer(); ?>
