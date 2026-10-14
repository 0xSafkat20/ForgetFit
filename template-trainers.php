<?php
/**
 * Template Name: Trainers
 *
 * @package ForgetFit
 */

get_header();
$trainers = new WP_Query(
	array(
		'post_type'      => 'ff_trainer',
		'posts_per_page' => 12,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
	)
);
?>
<main id="main" class="site-main essential-page">
	<header class="inner-hero essential-hero">
		<div class="container essential-hero__grid">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'Meet the team', 'forgetfit' ); ?></span>
				<h1><?php esc_html_e( 'Coaches who make training clearer.', 'forgetfit' ); ?></h1>
				<p class="lead"><?php esc_html_e( 'Friendly guidance, useful feedback, and sessions adapted to the person in front of us.', 'forgetfit' ); ?></p>
			</div>
			<a class="btn" href="<?php echo esc_url( forgetfit_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'Meet a coach', 'forgetfit' ); ?></a>
		</div>
	</header>

	<section class="section section--cream">
		<div class="container">
			<div class="section-heading">
				<div><span class="eyebrow"><?php esc_html_e( 'Your coaching team', 'forgetfit' ); ?></span><h2><?php esc_html_e( 'Support for every stage.', 'forgetfit' ); ?></h2></div>
				<p><?php esc_html_e( 'Add real trainer profiles in WordPress to show each coach’s experience, qualifications, and specialties.', 'forgetfit' ); ?></p>
			</div>
			<div class="trainer-grid">
				<?php if ( $trainers->have_posts() ) : ?>
					<?php while ( $trainers->have_posts() ) : $trainers->the_post(); ?>
						<article class="trainer-card">
							<a class="trainer-card__image" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large' ); } else { echo '<span class="trainer-card__placeholder">FF</span>'; } ?></a>
							<div><span><?php esc_html_e( 'Forget Fit coach', 'forgetfit' ); ?></span><h2><?php the_title(); ?></h2><p><?php echo esc_html( get_the_excerpt() ?: wp_trim_words( get_the_content(), 24 ) ); ?></p><a class="quiet-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View profile', 'forgetfit' ); ?> →</a></div>
						</article>
					<?php endwhile; wp_reset_postdata(); ?>
				<?php else : ?>
					<article class="coach-role"><span>01</span><h3><?php esc_html_e( 'Strength coaching', 'forgetfit' ); ?></h3><p><?php esc_html_e( 'Technique, progression, and practical programming for becoming stronger.', 'forgetfit' ); ?></p></article>
					<article class="coach-role"><span>02</span><h3><?php esc_html_e( 'Group coaching', 'forgetfit' ); ?></h3><p><?php esc_html_e( 'Structured sessions with clear options for different experience levels.', 'forgetfit' ); ?></p></article>
					<article class="coach-role"><span>03</span><h3><?php esc_html_e( 'Member support', 'forgetfit' ); ?></h3><p><?php esc_html_e( 'Orientations, equipment help, and useful check-ins as goals change.', 'forgetfit' ); ?></p></article>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="section essential-dark">
		<div class="container story-grid">
			<div class="story-grid__image"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/group-training-v3.png' ); ?>" alt="<?php esc_attr_e( 'Coach supporting a gym member during training', 'forgetfit' ); ?>" width="1672" height="941" loading="lazy"></div>
			<div class="story-grid__copy"><span class="eyebrow"><?php esc_html_e( 'Our approach', 'forgetfit' ); ?></span><h2><?php esc_html_e( 'Explain. Adapt. Progress.', 'forgetfit' ); ?></h2><p class="lead"><?php esc_html_e( 'Good coaching helps you understand what you are doing and why it matters.', 'forgetfit' ); ?></p><p><?php esc_html_e( 'Sessions are adjusted for confidence, experience, and current ability. The aim is sustainable progress—not making every workout unnecessarily complicated.', 'forgetfit' ); ?></p></div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/page', 'cta' ); ?>
</main>
<?php get_footer(); ?>
