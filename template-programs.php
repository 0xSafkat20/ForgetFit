<?php
/**
 * Template Name: Programs
 *
 * @package ForgetFit
 */

get_header();
$image_dir = get_template_directory_uri() . '/assets/images/';
$programs  = new WP_Query(
	array(
		'post_type'      => 'ff_program',
		'posts_per_page' => 12,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
	)
);
$fallback = array(
	array( 'Strength', 'Build useful strength with a progressive plan, clear coaching, and confident technique.', 'group-training-v3.png' ),
	array( 'Hybrid', 'Combine coached strength and conditioning in one balanced session.', 'gym-floor-v3.png' ),
	array( 'Conditioning', 'Improve fitness through scalable intervals, circuits, and purposeful recovery.', 'gym-hero-male-v4.png' ),
);
?>
<main id="main" class="site-main essential-page">
	<header class="inner-hero essential-hero">
		<div class="container essential-hero__grid">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'Ways to train', 'forgetfit' ); ?></span>
				<h1><?php esc_html_e( 'Find the training that fits.', 'forgetfit' ); ?></h1>
				<p class="lead"><?php esc_html_e( 'Start at your current level, choose the support you need, and build from there.', 'forgetfit' ); ?></p>
			</div>
			<a class="btn" href="<?php echo esc_url( forgetfit_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'Ask about a program', 'forgetfit' ); ?></a>
		</div>
	</header>

	<section class="section section--cream">
		<div class="container">
			<div class="section-heading">
				<div><span class="eyebrow"><?php esc_html_e( 'Program options', 'forgetfit' ); ?></span><h2><?php esc_html_e( 'Train with purpose.', 'forgetfit' ); ?></h2></div>
				<p><?php esc_html_e( 'Every option can be scaled for experience, ability, and schedule. A coach can help you choose during your first visit.', 'forgetfit' ); ?></p>
			</div>
			<div class="program-feature-grid">
				<?php if ( $programs->have_posts() ) : ?>
					<?php $index = 0; while ( $programs->have_posts() ) : $programs->the_post(); ?>
						<?php $image = get_the_post_thumbnail_url( get_the_ID(), 'large' ) ?: $image_dir . $fallback[ $index % count( $fallback ) ][2]; ?>
						<article class="program-feature">
							<a class="program-feature__image" href="<?php the_permalink(); ?>"><img src="<?php echo esc_url( $image ); ?>" alt="" loading="lazy"></a>
							<div><span><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span><h2><?php the_title(); ?></h2><p><?php echo esc_html( get_the_excerpt() ?: wp_trim_words( get_the_content(), 28 ) ); ?></p><a class="quiet-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View program', 'forgetfit' ); ?> →</a></div>
						</article>
					<?php $index++; endwhile; wp_reset_postdata(); ?>
				<?php else : ?>
					<?php foreach ( $fallback as $index => $program ) : ?>
						<article class="program-feature">
							<div class="program-feature__image"><img src="<?php echo esc_url( $image_dir . $program[2] ); ?>" alt="" loading="lazy" width="1672" height="941"></div>
							<div><span><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span><h2><?php echo esc_html( $program[0] ); ?></h2><p><?php echo esc_html( $program[1] ); ?></p><a class="quiet-link" href="<?php echo esc_url( forgetfit_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'Ask about this program', 'forgetfit' ); ?> →</a></div>
						</article>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="section essential-dark">
		<div class="container">
			<div class="section-heading"><div><span class="eyebrow"><?php esc_html_e( 'Your first steps', 'forgetfit' ); ?></span><h2><?php esc_html_e( 'Simple from day one.', 'forgetfit' ); ?></h2></div></div>
			<div class="step-grid">
				<article><span>01</span><h3><?php esc_html_e( 'Tell us your goal', 'forgetfit' ); ?></h3><p><?php esc_html_e( 'Strength, fitness, confidence, or simply building a reliable routine.', 'forgetfit' ); ?></p></article>
				<article><span>02</span><h3><?php esc_html_e( 'Tour and orientation', 'forgetfit' ); ?></h3><p><?php esc_html_e( 'See the space, learn the essentials, and ask practical questions.', 'forgetfit' ); ?></p></article>
				<article><span>03</span><h3><?php esc_html_e( 'Start at your level', 'forgetfit' ); ?></h3><p><?php esc_html_e( 'Use scalable sessions and clear progressions to move forward safely.', 'forgetfit' ); ?></p></article>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/page', 'cta' ); ?>
</main>
<?php get_footer(); ?>
