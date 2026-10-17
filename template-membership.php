<?php
/**
 * Template Name: Membership and Pricing
 *
 * @package ForgetFit
 */

get_header();
$contact_url = forgetfit_page_url( 'contact' );
$plans       = forgetfit_membership_plans();
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
				<?php if ( ! $plans ) : ?>
					<p class="content-note"><?php esc_html_e( 'Membership options are being updated. Contact the team for the latest availability.', 'forgetfit' ); ?></p>
				<?php else : foreach ( $plans as $plan ) : ?>
					<article class="membership-card<?php echo $plan['featured'] ? ' membership-card--featured' : ''; ?>">
						<div><span class="membership-card__label"><?php echo esc_html( $plan['label'] ); ?></span><h2><?php echo esc_html( $plan['name'] ); ?></h2><p><?php echo esc_html( $plan['description'] ); ?></p></div>
						<div class="membership-card__price"><strong><?php echo esc_html( $plan['price'] ); ?></strong><span><?php echo esc_html( $plan['period'] ); ?></span></div>
						<ul><?php foreach ( $plan['benefits'] as $benefit ) : ?><li><?php echo esc_html( $benefit ); ?></li><?php endforeach; ?></ul>
						<a class="btn<?php echo $plan['featured'] ? '' : ' btn--outline'; ?>" href="<?php echo esc_url( $contact_url ); ?>"><?php esc_html_e( 'Ask about this plan', 'forgetfit' ); ?></a>
					</article>
				<?php endforeach; endif; ?>
			</div>
			<p class="content-note"><?php esc_html_e( 'Ask the team about current availability, start dates, and membership terms.', 'forgetfit' ); ?></p>
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
			<aside class="page-aside"><span class="eyebrow"><?php esc_html_e( 'Questions answered', 'forgetfit' ); ?></span><h2><?php esc_html_e( 'Good to know.', 'forgetfit' ); ?></h2><p><?php esc_html_e( 'Get clear answers before your first visit or membership decision.', 'forgetfit' ); ?></p><a class="quiet-link" href="<?php echo esc_url( forgetfit_page_url( 'faq' ) ); ?>"><?php esc_html_e( 'View all FAQs', 'forgetfit' ); ?> →</a></aside>
			<div class="faq-list faq-accordion">
				<?php foreach ( forgetfit_faq_items() as $index => $faq ) : ?>
					<details class="faq-item"<?php echo 0 === $index ? ' open' : ''; ?>><summary><span><?php echo esc_html( $faq['question'] ); ?></span><span class="faq-item__icon" aria-hidden="true">+</span></summary><p><?php echo esc_html( $faq['answer'] ); ?></p></details>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/page', 'cta' ); ?>
</main>
<?php get_footer(); ?>
