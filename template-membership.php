<?php
/**
 * Template Name: Membership and Pricing
 *
 * @package ForgetFit
 */

get_header();
$contact_url = forgetfit_page_url( 'contact' );
$plans       = array(
	array( 'Train your way', 'Club', '$45', 'Flexible independent training with the essentials covered.', array( 'Full gym access', 'Fitness orientation', 'Program library' ), false ),
	array( 'Most popular', 'Together', '$79', 'Full access plus the motivation of coached group training.', array( 'Everything in Club', 'Unlimited group sessions', 'Quarterly coach check-in' ), true ),
	array( 'Focused progress', 'Guided', '$149', 'Closer coaching support and a plan that evolves with your goals.', array( 'Everything in Together', 'Two personal sessions monthly', 'Ongoing plan updates' ), false ),
);
?>
<main id="main" class="site-main essential-page">
	<header class="inner-hero essential-hero">
		<div class="container essential-hero__grid">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'Membership and pricing', 'forgetfit' ); ?></span>
				<h1><?php esc_html_e( 'Choose how you train.', 'forgetfit' ); ?></h1>
				<p class="lead"><?php esc_html_e( 'Straightforward access, flexible options, and room to change as your goals evolve.', 'forgetfit' ); ?></p>
			</div>
			<a class="btn" href="<?php echo esc_url( $contact_url ); ?>"><?php esc_html_e( 'Compare memberships', 'forgetfit' ); ?></a>
		</div>
	</header>

	<section class="section membership membership-page">
		<div class="container">
			<div class="membership-grid">
				<?php foreach ( $plans as $plan ) : ?>
					<article class="membership-card<?php echo $plan[5] ? ' membership-card--featured' : ''; ?>">
						<div><span class="membership-card__label"><?php echo esc_html( $plan[0] ); ?></span><h2><?php echo esc_html( $plan[1] ); ?></h2><p><?php echo esc_html( $plan[3] ); ?></p></div>
						<div class="membership-card__price"><strong><?php echo esc_html( $plan[2] ); ?></strong><span><?php esc_html_e( 'per month', 'forgetfit' ); ?></span></div>
						<ul><?php foreach ( $plan[4] as $benefit ) : ?><li><?php echo esc_html( $benefit ); ?></li><?php endforeach; ?></ul>
						<a class="btn<?php echo $plan[5] ? '' : ' btn--outline'; ?>" href="<?php echo esc_url( $contact_url ); ?>"><?php esc_html_e( 'Ask about this plan', 'forgetfit' ); ?></a>
					</article>
				<?php endforeach; ?>
			</div>
			<p class="content-note"><?php esc_html_e( 'Example packages and prices. Replace these with your confirmed offer, fees, and tax details before launch.', 'forgetfit' ); ?></p>
		</div>
	</section>

	<section class="section section--cream">
		<div class="container page-split">
			<aside class="page-aside"><span class="eyebrow"><?php esc_html_e( 'Included from day one', 'forgetfit' ); ?></span><h2><?php esc_html_e( 'A confident start.', 'forgetfit' ); ?></h2><p><?php esc_html_e( 'Every new member should understand the space, the equipment, and their next step.', 'forgetfit' ); ?></p></aside>
			<div class="benefit-list">
				<div><span>01</span><h3><?php esc_html_e( 'Gym orientation', 'forgetfit' ); ?></h3><p><?php esc_html_e( 'A practical introduction to the training floor and essential equipment.', 'forgetfit' ); ?></p></div>
				<div><span>02</span><h3><?php esc_html_e( 'A clear starting point', 'forgetfit' ); ?></h3><p><?php esc_html_e( 'Help choosing a program that matches your experience and goals.', 'forgetfit' ); ?></p></div>
				<div><span>03</span><h3><?php esc_html_e( 'Room to change', 'forgetfit' ); ?></h3><p><?php esc_html_e( 'Discuss a different level of support when your routine or goals change.', 'forgetfit' ); ?></p></div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container page-split">
			<aside class="page-aside"><span class="eyebrow"><?php esc_html_e( 'Questions answered', 'forgetfit' ); ?></span><h2><?php esc_html_e( 'Good to know.', 'forgetfit' ); ?></h2><p><?php esc_html_e( 'Claim a free pass and compare the options in person before joining.', 'forgetfit' ); ?></p></aside>
			<div class="faq-list">
				<?php foreach ( array( array( 'Can I try the gym first?', 'Yes. Arrange a free introductory visit and gym tour.' ), array( 'Do I need gym experience?', 'No. An orientation can help you become comfortable with the space and equipment.' ), array( 'Can I change my membership?', 'Ask the team about available plan changes and any applicable notice period.' ), array( 'Are classes beginner-friendly?', 'Coached sessions include options for different experience and fitness levels.' ) ) as $faq ) : ?>
					<div class="faq-item"><h3><?php echo esc_html( $faq[0] ); ?></h3><p><?php echo esc_html( $faq[1] ); ?></p></div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/page', 'cta' ); ?>
</main>
<?php get_footer(); ?>
