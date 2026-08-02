<?php
/**
 * Partner Program page — replicates pages/PartnerProgram.tsx.
 *
 * @package VitalPeptides
 */

defined( 'ABSPATH' ) || exit;

get_header();

$vp_account_url = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'myaccount' ) ) : wp_login_url();

$vp_stats = array(
	array( 'value' => '15%', 'label' => __( 'First Order', 'vitalpeptides' ), 'sub' => __( 'Commission', 'vitalpeptides' ) ),
	array( 'value' => '8%', 'label' => __( 'Recurring', 'vitalpeptides' ), 'sub' => __( 'Lifetime earnings', 'vitalpeptides' ) ),
	array( 'value' => '45 days', 'label' => __( 'Cookie', 'vitalpeptides' ), 'sub' => __( 'Attribution window', 'vitalpeptides' ) ),
	array( 'value' => '$0', 'label' => __( 'Minimum', 'vitalpeptides' ), 'sub' => __( 'Payout threshold', 'vitalpeptides' ) ),
);

$vp_steps = array(
	array( 'num' => '01', 'icon' => 'users', 'title' => __( 'Apply & Get Approved', 'vitalpeptides' ), 'desc' => __( 'Complete a quick application. Most partners are approved within 24 hours and receive their unique referral link immediately.', 'vitalpeptides' ) ),
	array( 'num' => '02', 'icon' => 'zap', 'title' => __( 'Share With Your Network', 'vitalpeptides' ), 'desc' => __( 'Promote Vital Peptides through your blog, social media, newsletter, or research community using your personalized link.', 'vitalpeptides' ) ),
	array( 'num' => '03', 'icon' => 'trending', 'title' => __( 'Track Performance', 'vitalpeptides' ), 'desc' => __( 'Monitor your clicks, conversions, and earnings in real-time through your dedicated partner dashboard.', 'vitalpeptides' ) ),
	array( 'num' => '04', 'icon' => 'dollar', 'title' => __( 'Get Paid Monthly', 'vitalpeptides' ), 'desc' => __( 'Receive your commissions monthly via direct deposit or PayPal. No minimum payout — every dollar counts.', 'vitalpeptides' ) ),
);

$vp_benefits = array(
	array( 'icon' => 'bar-chart', 'title' => __( 'Real-Time Analytics', 'vitalpeptides' ), 'desc' => __( 'Track every click, conversion, and commission through a clean, intuitive dashboard built for performance.', 'vitalpeptides' ) ),
	array( 'icon' => 'shield', 'title' => __( 'Premium Brand Reputation', 'vitalpeptides' ), 'desc' => __( 'Partner with a brand known for 99%+ purity and third-party tested research peptides that researchers trust.', 'vitalpeptides' ) ),
	array( 'icon' => 'gift', 'title' => __( 'Exclusive Partner Discounts', 'vitalpeptides' ), 'desc' => __( 'Offer your audience exclusive savings that drive conversions and build loyalty with every referral.', 'vitalpeptides' ) ),
	array( 'icon' => 'clock', 'title' => __( '45-Day Cookie Window', 'vitalpeptides' ), 'desc' => __( 'Extended attribution ensures you get credited for referrals even weeks after the initial click.', 'vitalpeptides' ) ),
	array( 'icon' => 'star', 'title' => __( 'Dedicated Support', 'vitalpeptides' ), 'desc' => __( 'Access a dedicated partner manager who provides marketing assets, strategy tips, and priority support.', 'vitalpeptides' ) ),
	array( 'icon' => 'trending', 'title' => __( 'Lifetime Recurring Revenue', 'vitalpeptides' ), 'desc' => __( 'Earn on every reorder from your referrals — not just the first purchase. Build true passive income.', 'vitalpeptides' ) ),
);

$vp_testimonials = array(
	array( 'name' => 'Dr. Rachel T.', 'role' => __( 'Research Consultant', 'vitalpeptides' ), 'earnings' => '$1,800/mo', 'quote' => __( 'The recurring commissions are a game-changer. Referrals I made six months ago still generate income every month.', 'vitalpeptides' ) ),
	array( 'name' => 'Marcus L.', 'role' => __( 'Science Educator', 'vitalpeptides' ), 'earnings' => '$3,100/mo', 'quote' => __( "Vital Peptides' reputation makes selling easy. When your audience trusts the product, conversions follow naturally.", 'vitalpeptides' ) ),
	array( 'name' => 'Emily W.', 'role' => __( 'Content Creator', 'vitalpeptides' ), 'earnings' => '$950/mo', 'quote' => __( 'I started part-time and the earnings grew steadily. The analytics dashboard makes it easy to optimize my content.', 'vitalpeptides' ) ),
);
?>

<main>
	<!-- Hero -->
	<section class="vp-partner-hero">
		<div class="vp-partner-hero-bg" aria-hidden="true"></div>
		<div class="vp-container vp-partner-hero-inner">
			<div class="vp-partner-hero-copy">
				<div class="vp-hero-badge"><?php echo vp_icon( 'trending' ); // phpcs:ignore ?><?php esc_html_e( 'Partner Program', 'vitalpeptides' ); ?></div>
				<h1><?php esc_html_e( 'Grow with', 'vitalpeptides' ); ?><br><span class="vp-accent-text"><?php esc_html_e( 'Vital Peptides', 'vitalpeptides' ); ?></span></h1>
				<p><?php esc_html_e( 'Earn generous commissions by sharing premium research-grade peptides with your network. No caps, no limits, no hassle.', 'vitalpeptides' ); ?></p>
				<a href="<?php echo esc_url( $vp_account_url ); ?>" class="vp-btn vp-btn--accent"><?php esc_html_e( 'Apply Now', 'vitalpeptides' ); ?><?php echo vp_icon( 'chevron-right' ); // phpcs:ignore ?></a>
			</div>
		</div>
	</section>

	<!-- Stats bar -->
	<section class="vp-partner-stats-wrap">
		<div class="vp-container">
			<div class="vp-partner-stats">
				<?php foreach ( $vp_stats as $vp_stat ) : ?>
					<div class="vp-partner-stat">
						<p class="vp-partner-stat-value"><?php echo esc_html( $vp_stat['value'] ); ?></p>
						<p class="vp-partner-stat-label"><?php echo esc_html( $vp_stat['label'] ); ?></p>
						<p class="vp-partner-stat-sub"><?php echo esc_html( $vp_stat['sub'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- How it works -->
	<section class="vp-page-body">
		<div class="vp-container">
			<h2 class="vp-body-title"><?php esc_html_e( 'How It Works', 'vitalpeptides' ); ?></h2>
			<p class="vp-body-sub"><?php esc_html_e( 'Start earning in minutes with a streamlined onboarding process designed for simplicity.', 'vitalpeptides' ); ?></p>
			<div class="vp-info-grid vp-info-grid--4">
				<?php foreach ( $vp_steps as $vp_step ) : ?>
					<div class="vp-info-card vp-info-card--lg vp-step-card">
						<span class="vp-step-num"><?php echo esc_html( $vp_step['num'] ); ?></span>
						<div class="vp-info-icon"><?php echo vp_icon( $vp_step['icon'] ); // phpcs:ignore ?></div>
						<h3><?php echo esc_html( $vp_step['title'] ); ?></h3>
						<p><?php echo esc_html( $vp_step['desc'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Earnings -->
	<section class="vp-page-body vp-page-body--alt">
		<div class="vp-container">
			<div class="vp-earnings-grid">
				<div>
					<h2 class="vp-body-title vp-body-title--left"><?php esc_html_e( 'Earnings That Compound', 'vitalpeptides' ); ?></h2>
					<p class="vp-earnings-lead"><?php esc_html_e( "Our dual-commission model rewards you upfront and keeps paying as your referrals reorder. Here's what a typical month could look like.", 'vitalpeptides' ); ?></p>
					<div class="vp-earnings-rows">
						<div class="vp-earnings-row">
							<div><p class="vp-earnings-row-label"><?php esc_html_e( '15 new customers × avg. $180 order', 'vitalpeptides' ); ?></p><p class="vp-earnings-row-note"><?php esc_html_e( '15% first-order commission', 'vitalpeptides' ); ?></p></div>
							<span class="vp-earnings-row-value">+$405</span>
						</div>
						<div class="vp-earnings-row">
							<div><p class="vp-earnings-row-label"><?php esc_html_e( '60 recurring orders × avg. $140', 'vitalpeptides' ); ?></p><p class="vp-earnings-row-note"><?php esc_html_e( '8% lifetime recurring', 'vitalpeptides' ); ?></p></div>
							<span class="vp-earnings-row-value">+$672</span>
						</div>
					</div>
				</div>
				<div class="vp-earnings-panel">
					<p class="vp-earnings-panel-label"><?php esc_html_e( 'Estimated Monthly Earnings', 'vitalpeptides' ); ?></p>
					<p class="vp-earnings-panel-value">$1,077</p>
					<p class="vp-earnings-panel-sub"><?php esc_html_e( 'Based on average partner performance', 'vitalpeptides' ); ?></p>
					<div class="vp-earnings-metrics">
						<div><p class="vp-metric-value">75</p><p class="vp-metric-label"><?php esc_html_e( 'Orders', 'vitalpeptides' ); ?></p></div>
						<div><p class="vp-metric-value">87%</p><p class="vp-metric-label"><?php esc_html_e( 'Retention', 'vitalpeptides' ); ?></p></div>
						<div><p class="vp-metric-value">12.5%</p><p class="vp-metric-label"><?php esc_html_e( 'Conv. Rate', 'vitalpeptides' ); ?></p></div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Benefits -->
	<section class="vp-page-body">
		<div class="vp-container">
			<h2 class="vp-body-title"><?php esc_html_e( 'Why Partners Choose Vital Peptides', 'vitalpeptides' ); ?></h2>
			<p class="vp-body-sub"><?php esc_html_e( 'Everything you need to build a sustainable revenue stream with a brand researchers trust.', 'vitalpeptides' ); ?></p>
			<div class="vp-info-grid vp-info-grid--3">
				<?php foreach ( $vp_benefits as $vp_benefit ) : ?>
					<div class="vp-info-card">
						<div class="vp-info-icon"><?php echo vp_icon( $vp_benefit['icon'] ); // phpcs:ignore ?></div>
						<h3><?php echo esc_html( $vp_benefit['title'] ); ?></h3>
						<p><?php echo esc_html( $vp_benefit['desc'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Testimonials -->
	<section class="vp-page-body vp-page-body--alt">
		<div class="vp-container">
			<h2 class="vp-body-title"><?php esc_html_e( 'Partner Success Stories', 'vitalpeptides' ); ?></h2>
			<div class="vp-info-grid vp-info-grid--3">
				<?php foreach ( $vp_testimonials as $vp_t ) : ?>
					<div class="vp-info-card vp-info-card--lg vp-partner-testimonial">
						<div class="vp-partner-stars">
							<?php for ( $vp_i = 0; $vp_i < 5; $vp_i++ ) : ?><span><?php echo vp_icon( 'star' ); // phpcs:ignore ?></span><?php endfor; ?>
						</div>
						<p class="vp-partner-quote">"<?php echo esc_html( $vp_t['quote'] ); ?>"</p>
						<div class="vp-partner-meta">
							<div><p class="vp-partner-name"><?php echo esc_html( $vp_t['name'] ); ?></p><p class="vp-partner-role"><?php echo esc_html( $vp_t['role'] ); ?></p></div>
							<span class="vp-partner-earnings"><?php echo esc_html( $vp_t['earnings'] ); ?></span>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Final CTA -->
	<section class="vp-dark-section">
		<div class="vp-container vp-partner-cta">
			<h2><?php esc_html_e( 'Ready to Start Earning?', 'vitalpeptides' ); ?></h2>
			<p><?php esc_html_e( 'Join the Vital Peptides Partner Program today and turn your network into a revenue stream.', 'vitalpeptides' ); ?></p>
			<a href="<?php echo esc_url( $vp_account_url ); ?>" class="vp-btn vp-btn--accent"><?php esc_html_e( 'Apply Now', 'vitalpeptides' ); ?><?php echo vp_icon( 'chevron-right' ); // phpcs:ignore ?></a>
		</div>
	</section>
</main>

<?php get_footer(); ?>
