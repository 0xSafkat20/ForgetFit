<?php
/**
 * Template Name: About the Gym
 *
 * @package ForgetFit
 */

get_header();
$image_dir = get_template_directory_uri() . '/assets/images/';
?>
<main id="main" class="site-main essential-page">
	<header class="inner-hero essential-hero">
		<div class="container essential-hero__grid">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'About Forget Fit', 'forgetfit' ); ?></span>
				<h1><?php esc_html_e( 'A gym built around real life.', 'forgetfit' ); ?></h1>
				<p class="lead"><?php esc_html_e( 'Serious equipment, useful coaching, and a welcoming place to build a routine that lasts.', 'forgetfit' ); ?></p>
			</div>
			<a class="btn" href="<?php echo esc_url( forgetfit_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'Visit the gym', 'forgetfit' ); ?> <span aria-hidden="true">→</span></a>
		</div>
	</header>

	<section class="section section--cream">
		<div class="container story-grid">
			<div class="story-grid__image">
				<img src="<?php echo esc_url( $image_dir . 'group-training-v3.png' ); ?>" alt="<?php esc_attr_e( 'Members training together at Forget Fit', 'forgetfit' ); ?>" width="1672" height="941">
			</div>
			<div class="story-grid__copy">
				<span class="eyebrow"><?php esc_html_e( 'Why we exist', 'forgetfit' ); ?></span>
				<h2><?php esc_html_e( 'Progress without the pressure.', 'forgetfit' ); ?></h2>
				<p class="lead"><?php esc_html_e( 'Training should feel focused, supportive, and possible to maintain.', 'forgetfit' ); ?></p>
				<p><?php esc_html_e( 'Forget Fit brings strength equipment, flexible training options, and practical coaching together in one straightforward space. New members receive a clear introduction, while experienced members have the freedom and equipment to train their way.', 'forgetfit' ); ?></p>
			</div>
		</div>
	</section>

	<section class="section essential-dark">
		<div class="container">
			<div class="section-heading">
				<div><span class="eyebrow"><?php esc_html_e( 'What matters here', 'forgetfit' ); ?></span><h2><?php esc_html_e( 'A better training environment.', 'forgetfit' ); ?></h2></div>
				<p><?php esc_html_e( 'Every part of the club is designed to make training easier to begin and easier to continue.', 'forgetfit' ); ?></p>
			</div>
			<div class="value-grid">
				<article class="value-card"><span>01</span><h3><?php esc_html_e( 'Welcoming', 'forgetfit' ); ?></h3><p><?php esc_html_e( 'A respectful gym for beginners, returning members, and experienced athletes.', 'forgetfit' ); ?></p></article>
				<article class="value-card"><span>02</span><h3><?php esc_html_e( 'Practical', 'forgetfit' ); ?></h3><p><?php esc_html_e( 'Clear guidance and useful feedback without unnecessary complexity.', 'forgetfit' ); ?></p></article>
				<article class="value-card"><span>03</span><h3><?php esc_html_e( 'Consistent', 'forgetfit' ); ?></h3><p><?php esc_html_e( 'Flexible access and scalable sessions that fit around everyday life.', 'forgetfit' ); ?></p></article>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container story-grid story-grid--reverse">
			<div class="story-grid__copy">
				<span class="eyebrow"><?php esc_html_e( 'Inside the club', 'forgetfit' ); ?></span>
				<h2><?php esc_html_e( 'Space to train properly.', 'forgetfit' ); ?></h2>
				<p class="lead"><?php esc_html_e( 'Racks, free weights, cables, cardio, functional turf, and the everyday essentials members expect.', 'forgetfit' ); ?></p>
				<a class="btn btn--outline" href="<?php echo esc_url( forgetfit_page_url( 'programs' ) ); ?>"><?php esc_html_e( 'Explore programs', 'forgetfit' ); ?></a>
			</div>
			<div class="story-grid__image">
				<img src="<?php echo esc_url( $image_dir . 'gym-floor-v3.png' ); ?>" alt="<?php esc_attr_e( 'Forget Fit training floor and equipment', 'forgetfit' ); ?>" width="1672" height="941" loading="lazy">
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/page', 'cta' ); ?>
</main>
<?php get_footer(); ?>
