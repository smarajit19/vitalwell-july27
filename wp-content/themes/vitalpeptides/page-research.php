<?php
/**
 * Research page — replicates pages/Research.tsx.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

get_header();

vp_page_hero(
	__( 'Peptide Research & Science', 'vitalpeptides' ),
	__( 'Advancing scientific understanding through high-purity peptide compounds', 'vitalpeptides' ),
	'flask',
	__( 'Research & Science', 'vitalpeptides' ),
	true
);

$vp_areas = array(
	array( 'icon' => 'flask', 'title' => __( 'Cellular Signaling', 'vitalpeptides' ), 'desc' => __( 'Peptides act as signaling molecules that regulate cell-to-cell communication, influencing gene expression, protein synthesis, and metabolic processes essential for cellular function.', 'vitalpeptides' ) ),
	array( 'icon' => 'dna', 'title' => __( 'Hormone Regulation', 'vitalpeptides' ), 'desc' => __( 'Many peptides function as hormones or hormone analogs, playing vital roles in endocrine system research, growth factors, and metabolic regulation studies.', 'vitalpeptides' ) ),
	array( 'icon' => 'shield', 'title' => __( 'Tissue Repair Research', 'vitalpeptides' ), 'desc' => __( 'Research peptides are studied for their potential roles in tissue regeneration, wound healing, and cellular repair mechanisms at the molecular level.', 'vitalpeptides' ) ),
	array( 'icon' => 'microscope', 'title' => __( 'Antimicrobial Studies', 'vitalpeptides' ), 'desc' => __( 'Antimicrobial peptides (AMPs) are being researched for their mechanisms of action against bacteria, viruses, and fungi, contributing to understanding of innate immunity.', 'vitalpeptides' ) ),
	array( 'icon' => 'brain', 'title' => __( 'Neuropeptide Research', 'vitalpeptides' ), 'desc' => __( 'Neuropeptides are essential for studying neural communication, cognitive processes, mood regulation, and the complex mechanisms of the central nervous system.', 'vitalpeptides' ) ),
	array( 'icon' => 'zap', 'title' => __( 'Metabolic Pathway Analysis', 'vitalpeptides' ), 'desc' => __( 'Peptides help researchers understand metabolic processes, energy regulation, glucose homeostasis, and lipid metabolism in various physiological conditions.', 'vitalpeptides' ) ),
);

$vp_applications = array(
	array( 'title' => __( 'Growth Hormone Research', 'vitalpeptides' ), 'desc' => __( 'Peptides such as Sermorelin, Ipamorelin, and CJC-1295 are used extensively in growth hormone secretagogue research, helping scientists understand pituitary function, growth hormone release patterns, and age-related hormonal changes.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Tissue Protection & Repair Studies', 'vitalpeptides' ), 'desc' => __( 'BPC-157 and TB-500 are among the most studied peptides in tissue repair research. BPC-157 is investigated for its potential effects on angiogenesis, collagen formation, and tissue remodeling. TB-500 is studied for its role in cellular migration and inflammation modulation.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Metabolic Research', 'vitalpeptides' ), 'desc' => __( 'Peptides like G3-R represent next-generation research tools for studying metabolic pathways. As a triple agonist affecting multiple receptor pathways, it provides insights into complex metabolic regulation and energy homeostasis.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Growth Factor Research', 'vitalpeptides' ), 'desc' => __( "IGF-1 LR3 is a modified version of IGF-1 with enhanced stability. It's used extensively in cell culture research, studying muscle satellite cell activation, protein synthesis pathways, and cellular proliferation mechanisms.", 'vitalpeptides' ) ),
);

$vp_developments = array(
	array( 'title' => __( 'Advanced Synthesis Techniques', 'vitalpeptides' ), 'desc' => __( 'Recent developments in solid-phase peptide synthesis (SPPS) and microwave-assisted synthesis have dramatically improved peptide purity and yield, achieving greater than 99% purity.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Stability Enhancement', 'vitalpeptides' ), 'desc' => __( 'Research into peptide stability has led to innovative modifications including cyclization, D-amino acid substitution, PEGylation, and novel N-methylation patterns.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Multi-Receptor Targeting', 'vitalpeptides' ), 'desc' => __( 'Recent peptide research has focused on multi-agonist compounds that can simultaneously activate multiple receptor pathways, revealing complex receptor cross-talk mechanisms.', 'vitalpeptides' ) ),
	array( 'title' => __( 'Computational Peptide Design', 'vitalpeptides' ), 'desc' => __( 'AI and machine learning are transforming peptide research. Computational tools now predict peptide structure, binding affinity, and biological activity with unprecedented accuracy.', 'vitalpeptides' ) ),
);
?>

<section class="vp-page-body">
	<div class="vp-container">
		<div class="vp-research-intro">
			<h2><?php esc_html_e( 'What Are Peptides?', 'vitalpeptides' ); ?></h2>
			<p><?php esc_html_e( 'Peptides are short chains of amino acids, typically containing 2-50 amino acid residues linked by peptide bonds. They are essentially small proteins, serving as the fundamental building blocks of biological processes in all living organisms.', 'vitalpeptides' ); ?></p>
		</div>
		<div class="vp-research-facts">
			<div class="vp-fact-card"><p class="vp-fact-label"><?php esc_html_e( 'Molecular Weight', 'vitalpeptides' ); ?></p><p class="vp-fact-value">500-10,000 Da</p></div>
			<div class="vp-fact-card"><p class="vp-fact-label"><?php esc_html_e( 'Structure', 'vitalpeptides' ); ?></p><p class="vp-fact-value"><?php esc_html_e( 'Linear or Cyclic', 'vitalpeptides' ); ?></p></div>
			<div class="vp-fact-card"><p class="vp-fact-label"><?php esc_html_e( 'Stability', 'vitalpeptides' ); ?></p><p class="vp-fact-value"><?php esc_html_e( 'Proper Storage Req.', 'vitalpeptides' ); ?></p></div>
			<div class="vp-fact-card"><p class="vp-fact-label"><?php esc_html_e( 'Synthesis', 'vitalpeptides' ); ?></p><p class="vp-fact-value"><?php esc_html_e( 'Chemical / Recombinant', 'vitalpeptides' ); ?></p></div>
		</div>
	</div>
</section>

<section class="vp-page-body vp-page-body--alt">
	<div class="vp-container">
		<h2 class="vp-body-title"><?php esc_html_e( 'What Are Peptides Good For?', 'vitalpeptides' ); ?></h2>
		<p class="vp-body-sub"><?php esc_html_e( 'Peptides play crucial roles in numerous biological functions and have become invaluable tools in scientific research.', 'vitalpeptides' ); ?></p>
		<div class="vp-info-grid vp-info-grid--3">
			<?php foreach ( $vp_areas as $vp_area ) : ?>
				<div class="vp-info-card">
					<div class="vp-info-icon"><?php echo vp_icon( $vp_area['icon'] ); // phpcs:ignore ?></div>
					<h3><?php echo esc_html( $vp_area['title'] ); ?></h3>
					<p><?php echo esc_html( $vp_area['desc'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="vp-page-body">
	<div class="vp-container">
		<h2 class="vp-body-title"><?php esc_html_e( 'Peptide Research Applications', 'vitalpeptides' ); ?></h2>
		<div class="vp-info-grid vp-info-grid--2">
			<?php $vp_i = 1; foreach ( $vp_applications as $vp_app ) : ?>
				<div class="vp-info-card vp-info-card--lg">
					<div class="vp-app-head"><span class="vp-app-num"><?php echo esc_html( $vp_i ); ?></span><h3><?php echo esc_html( $vp_app['title'] ); ?></h3></div>
					<p><?php echo esc_html( $vp_app['desc'] ); ?></p>
				</div>
			<?php $vp_i++; endforeach; ?>
		</div>
	</div>
</section>

<section class="vp-page-body vp-page-body--alt">
	<div class="vp-container">
		<h2 class="vp-body-title"><?php esc_html_e( 'Latest Research Developments', 'vitalpeptides' ); ?></h2>
		<div class="vp-info-grid vp-info-grid--2">
			<?php foreach ( $vp_developments as $vp_dev ) : ?>
				<div class="vp-info-card vp-info-card--lg">
					<h3><?php echo esc_html( $vp_dev['title'] ); ?></h3>
					<p><?php echo esc_html( $vp_dev['desc'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="vp-dark-section">
	<div class="vp-container">
		<h2 class="vp-body-title vp-body-title--light"><?php esc_html_e( 'Research-Grade Quality Standards', 'vitalpeptides' ); ?></h2>
		<div class="vp-dark-grid">
			<div class="vp-dark-item">
				<div class="vp-dark-icon"><?php echo vp_icon( 'check-circle' ); // phpcs:ignore ?></div>
				<h3><?php esc_html_e( 'Guaranteed Purity', 'vitalpeptides' ); ?></h3>
				<p><?php esc_html_e( 'Every batch exceeds 99% purity, verified by HPLC and mass spectrometry analysis', 'vitalpeptides' ); ?></p>
			</div>
			<div class="vp-dark-item">
				<div class="vp-dark-icon"><?php echo vp_icon( 'building' ); // phpcs:ignore ?></div>
				<h3><?php esc_html_e( 'USA Manufactured', 'vitalpeptides' ); ?></h3>
				<p><?php esc_html_e( 'All synthesis and purification conducted in state-of-the-art US facilities', 'vitalpeptides' ); ?></p>
			</div>
			<div class="vp-dark-item">
				<div class="vp-dark-icon"><?php echo vp_icon( 'headphones' ); // phpcs:ignore ?></div>
				<h3><?php esc_html_e( 'Research Support', 'vitalpeptides' ); ?></h3>
				<p><?php esc_html_e( 'Dedicated technical support team with peptide expertise for all your research needs', 'vitalpeptides' ); ?></p>
			</div>
		</div>
	</div>
</section>

<section class="vp-disclaimer-strip">
	<div class="vp-container">
		<p><?php esc_html_e( 'All peptides sold by Vital Peptides are intended for laboratory research purposes only. They are not approved for human or animal consumption, therapeutic use, or any clinical applications. Researchers must comply with all applicable regulations when conducting peptide research.', 'vitalpeptides' ); ?></p>
	</div>
</section>

<?php get_footer(); ?>
