<?php
/**
 * Front page.
 *
 * @package ForgetFit
 */

get_header();

$cta       = get_theme_mod( 'forgetfit_cta_label', __( 'Get a free pass', 'forgetfit' ) );
$cta_url   = get_theme_mod( 'forgetfit_cta_url', '#contact' );
$phone     = get_theme_mod( 'forgetfit_phone', '+1 (555) 240-9090' );
$address   = get_theme_mod( 'forgetfit_address', __( '204 Iron Street, Downtown', 'forgetfit' ) );
$hero_title = get_theme_mod( 'forgetfit_hero_title', __( 'Stronger starts today.', 'forgetfit' ) );
$hero_words = preg_split( '/\s+/', trim( $hero_title ) );
$hero_last  = array_pop( $hero_words );
$programs  = new WP_Query(
	array(
		'post_type'      => 'ff_program',
		'posts_per_page' => 3,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
	)
);
$reviews   = new WP_Query(
	array(
		'post_type'      => 'ff_review',
		'posts_per_page' => 3,
	)
);
$image_dir = get_template_directory_uri() . '/assets/images/';
?>

<main id="main" class="site-main">
	<section class="hero" aria-labelledby="hero-title">
		<div class="hero__visual" aria-hidden="true">
			<img src="<?php echo esc_url( $image_dir . 'gym-hero-v3.png' ); ?>" alt="" width="1942" height="809" fetchpriority="high">
		</div>
		<div class="container hero__grid">
			<div class="hero__copy">
				<span class="eyebrow"><?php echo esc_html( get_theme_mod( 'forgetfit_hero_kicker', __( 'Training for every body', 'forgetfit' ) ) ); ?></span>
				<h1 id="hero-title"><?php echo esc_html( implode( ' ', $hero_words ) ); ?> <span><?php echo esc_html( $hero_last ); ?></span></h1>
				<p class="lead"><?php echo esc_html( get_theme_mod( 'forgetfit_hero_text', __( 'Everything you need to build strength, boost fitness, and keep moving forward—all in one motivating gym.', 'forgetfit' ) ) ); ?></p>
				<div class="hero__actions">
					<a class="btn" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta ); ?> <span aria-hidden="true">→</span></a>
					<a class="quiet-link" href="#showcase"><?php esc_html_e( 'See inside the club', 'forgetfit' ); ?> <span aria-hidden="true">↓</span></a>
				</div>
				<div class="hero__promise" aria-label="<?php esc_attr_e( 'Membership promises', 'forgetfit' ); ?>">
					<span><?php esc_html_e( 'No joining fee', 'forgetfit' ); ?></span>
					<span><?php esc_html_e( 'Cancel anytime', 'forgetfit' ); ?></span>
					<span><?php esc_html_e( 'Friendly first session', 'forgetfit' ); ?></span>
				</div>
			</div>
			<div class="hero__note">
				<span><?php esc_html_e( 'Your first week', 'forgetfit' ); ?></span>
				<strong><?php esc_html_e( '7 days free', 'forgetfit' ); ?></strong>
				<small><?php esc_html_e( 'Tour, train, and meet the team before joining.', 'forgetfit' ); ?></small>
			</div>
		</div>
	</section>

	<section class="reassurance" aria-label="<?php esc_attr_e( 'Why members choose ForgetFit', 'forgetfit' ); ?>">
		<div class="container reassurance__grid">
			<div>
				<span class="reassurance__icon" aria-hidden="true">01</span>
				<strong><?php esc_html_e( 'Training for every body', 'forgetfit' ); ?></strong>
				<p><?php esc_html_e( 'Welcoming coaching and scalable sessions for every experience level.', 'forgetfit' ); ?></p>
			</div>
			<div>
				<span class="reassurance__icon" aria-hidden="true">02</span>
				<strong><?php esc_html_e( 'Expert coaching', 'forgetfit' ); ?></strong>
				<p><?php esc_html_e( 'Practical guidance, useful feedback, and a plan that fits your goals.', 'forgetfit' ); ?></p>
			</div>
			<div>
				<span class="reassurance__icon" aria-hidden="true">03</span>
				<strong><?php esc_html_e( 'Open every day', 'forgetfit' ); ?></strong>
				<p><?php esc_html_e( 'Flexible hours make it easier to train around work, family, and life.', 'forgetfit' ); ?></p>
			</div>
		</div>
	</section>

	<section class="section programs" id="programs">
		<div class="container programs-showcase">
			<header class="programs-intro">
				<span class="eyebrow"><?php esc_html_e( 'Ways to train', 'forgetfit' ); ?></span>
				<h2><?php esc_html_e( 'Programs', 'forgetfit' ); ?></h2>
				<p><?php esc_html_e( 'Focused training options designed to meet you where you are and move you toward a stronger routine.', 'forgetfit' ); ?></p>
				<a class="btn btn--outline" href="#contact"><?php esc_html_e( 'Find your program', 'forgetfit' ); ?> <span aria-hidden="true">→</span></a>
			</header>

			<div class="program-card-grid">
				<?php
				$program_images = array( 'group-training-v3.png', 'gym-floor-v3.png', 'gym-hero-v3.png' );
				if ( $programs->have_posts() ) :
					$program_index = 0;
					while ( $programs->have_posts() ) :
						$programs->the_post();
						$program_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
						if ( ! $program_image ) {
							$program_image = $image_dir . $program_images[ $program_index % count( $program_images ) ];
						}
						?>
						<a class="program-card" href="<?php the_permalink(); ?>">
							<img src="<?php echo esc_url( $program_image ); ?>" alt="" loading="lazy">
							<span class="program-card__shade"></span>
							<span class="program-card__number"><?php echo esc_html( str_pad( (string) ( $program_index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
							<span class="program-card__content">
								<strong><?php the_title(); ?></strong>
								<small><?php echo esc_html( get_the_excerpt() ?: wp_trim_words( get_the_content(), 16 ) ); ?></small>
								<em><?php esc_html_e( 'Explore program', 'forgetfit' ); ?> →</em>
							</span>
						</a>
						<?php
						$program_index++;
					endwhile;
					wp_reset_postdata();
				else :
					$fallback_programs = array(
						array( __( 'Strength', 'forgetfit' ), __( 'Build useful strength with a progressive plan and confident technique.', 'forgetfit' ), 'group-training-v3.png' ),
						array( __( 'Hybrid', 'forgetfit' ), __( 'Combine coached strength and conditioning in one balanced session.', 'forgetfit' ), 'gym-floor-v3.png' ),
						array( __( 'Conditioning', 'forgetfit' ), __( 'Improve fitness with scalable intervals, circuits, and purposeful recovery.', 'forgetfit' ), 'gym-hero-v3.png' ),
					);
					foreach ( $fallback_programs as $program_index => $program ) :
						?>
						<a class="program-card" href="#contact">
							<img src="<?php echo esc_url( $image_dir . $program[2] ); ?>" alt="" loading="lazy" width="1672" height="941">
							<span class="program-card__shade"></span>
							<span class="program-card__number"><?php echo esc_html( str_pad( (string) ( $program_index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
							<span class="program-card__content">
								<strong><?php echo esc_html( $program[0] ); ?></strong>
								<small><?php echo esc_html( $program[1] ); ?></small>
								<em><?php esc_html_e( 'Ask about this program', 'forgetfit' ); ?> →</em>
							</span>
						</a>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php
	$plans = array(
		array(
			'label'       => __( 'Train your way', 'forgetfit' ),
			'name'        => __( 'Club', 'forgetfit' ),
			'price'       => '$45',
			'description' => __( 'Everything you need for a flexible independent training routine.', 'forgetfit' ),
			'benefits'    => array( __( 'Full gym access', 'forgetfit' ), __( 'Fitness orientation', 'forgetfit' ), __( 'Program library', 'forgetfit' ) ),
			'featured'    => false,
		),
		array(
			'label'       => __( 'Most popular', 'forgetfit' ),
			'name'        => __( 'Together', 'forgetfit' ),
			'price'       => '$79',
			'description' => __( 'Full access plus the motivation and structure of unlimited group training.', 'forgetfit' ),
			'benefits'    => array( __( 'Everything in Club', 'forgetfit' ), __( 'Unlimited group sessions', 'forgetfit' ), __( 'Quarterly coach check-in', 'forgetfit' ) ),
			'featured'    => true,
		),
		array(
			'label'       => __( 'Focused progress', 'forgetfit' ),
			'name'        => __( 'Guided', 'forgetfit' ),
			'price'       => '$149',
			'description' => __( 'A closer coaching relationship and a plan that evolves with your goals.', 'forgetfit' ),
			'benefits'    => array( __( 'Everything in Together', 'forgetfit' ), __( 'Two personal sessions monthly', 'forgetfit' ), __( 'Ongoing plan updates', 'forgetfit' ) ),
			'featured'    => false,
		),
	);
	?>
	<section class="section membership" id="membership">
		<div class="container membership-shell">
			<header class="membership-intro">
				<span class="eyebrow"><?php esc_html_e( 'Simple, flexible access', 'forgetfit' ); ?></span>
				<h2><?php esc_html_e( 'Membership', 'forgetfit' ); ?></h2>
				<p><?php esc_html_e( 'Choose the support that fits your goals. No joining fee and no long-term contract.', 'forgetfit' ); ?></p>
			</header>
			<div class="membership-grid">
				<?php foreach ( $plans as $plan ) : ?>
					<article class="membership-card<?php echo $plan['featured'] ? ' membership-card--featured' : ''; ?>">
						<div>
							<span class="membership-card__label"><?php echo esc_html( $plan['label'] ); ?></span>
							<h3><?php echo esc_html( $plan['name'] ); ?></h3>
							<p><?php echo esc_html( $plan['description'] ); ?></p>
						</div>
						<div class="membership-card__price">
							<strong><?php echo esc_html( $plan['price'] ); ?></strong>
							<span><?php esc_html_e( 'per month', 'forgetfit' ); ?></span>
						</div>
						<ul>
							<?php foreach ( $plan['benefits'] as $benefit ) : ?>
								<li><?php echo esc_html( $benefit ); ?></li>
							<?php endforeach; ?>
						</ul>
						<a class="btn<?php echo $plan['featured'] ? '' : ' btn--outline'; ?>" href="#contact"><?php echo esc_html( $cta ); ?> <span aria-hidden="true">→</span></a>
					</article>
				<?php endforeach; ?>
			</div>
			<p class="content-note content-note--light"><?php esc_html_e( 'Example packages and prices—replace with your real offer before launch.', 'forgetfit' ); ?></p>
		</div>
	</section>

	<section class="section section--sage showcase" id="showcase">
		<div class="container">
			<div class="showcase__head">
				<div>
					<span class="eyebrow"><?php esc_html_e( 'Inside ForgetFit', 'forgetfit' ); ?></span>
					<h2><?php esc_html_e( 'A serious gym that still feels welcoming.', 'forgetfit' ); ?></h2>
				</div>
				<a class="quiet-link" href="#contact"><?php esc_html_e( 'Book a gym tour', 'forgetfit' ); ?> →</a>
			</div>
			<div class="showcase-grid">
				<figure class="showcase-grid__main">
					<img src="<?php echo esc_url( $image_dir . 'gym-floor-v3.png' ); ?>" alt="<?php esc_attr_e( 'Spacious modern ForgetFit gym floor with strength equipment and turf', 'forgetfit' ); ?>" loading="lazy" width="1672" height="941">
					<figcaption>
						<strong><?php esc_html_e( 'The training floor', 'forgetfit' ); ?></strong>
						<span><?php esc_html_e( 'Racks, free weights, cables, cardio, and functional turf—with room to train properly.', 'forgetfit' ); ?></span>
					</figcaption>
				</figure>
				<div class="showcase-grid__details">
					<div class="detail-card">
						<span class="detail-card__icon">01</span>
						<h3><?php esc_html_e( 'Coaching that helps', 'forgetfit' ); ?></h3>
						<p><?php esc_html_e( 'Friendly coaches who explain the why, adapt the session, and help you build confidence.', 'forgetfit' ); ?></p>
					</div>
					<div class="detail-card detail-card--dark">
						<span class="detail-card__icon">02</span>
						<h3><?php esc_html_e( 'The essentials, done well', 'forgetfit' ); ?></h3>
						<p><?php esc_html_e( 'Clean changing rooms, secure storage, filtered water, and a team that knows your name.', 'forgetfit' ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section reviews" id="reviews">
		<div class="container">
			<div class="reviews__header">
				<div>
					<span class="eyebrow"><?php esc_html_e( 'Member stories', 'forgetfit' ); ?></span>
					<h2><?php esc_html_e( 'Real members. Real progress.', 'forgetfit' ); ?></h2>
				</div>
				<div class="review-score" aria-label="<?php esc_attr_e( 'Demo rating, replace with verified member data', 'forgetfit' ); ?>">
					<strong>4.9</strong>
					<span>★★★★★<small><?php esc_html_e( 'Demo rating · replace with verified data', 'forgetfit' ); ?></small></span>
				</div>
			</div>
			<div class="review-grid">
				<?php if ( $reviews->have_posts() ) : ?>
					<?php while ( $reviews->have_posts() ) : $reviews->the_post(); ?>
						<blockquote class="review-card">
							<p>“<?php echo esc_html( get_the_excerpt() ?: wp_trim_words( get_the_content(), 34 ) ); ?>”</p>
							<footer><strong><?php the_title(); ?></strong><span><?php esc_html_e( 'Member story', 'forgetfit' ); ?></span></footer>
						</blockquote>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<?php
					$sample_reviews = array(
						array( __( 'I never felt like I had to prove I belonged here. The coaching is useful, and people actually say hello.', 'forgetfit' ), 'Samira K.' ),
						array( __( 'It is the first routine I have kept because it fits my real life. I feel stronger every week.', 'forgetfit' ), 'Daniel M.' ),
						array( __( 'The equipment is excellent, but the people are why I stayed. The team remembers my goals.', 'forgetfit' ), 'Lena R.' ),
					);
					foreach ( $sample_reviews as $review ) :
						?>
						<blockquote class="review-card">
							<p>“<?php echo esc_html( $review[0] ); ?>”</p>
							<footer><strong><?php echo esc_html( $review[1] ); ?></strong><span><?php esc_html_e( 'Sample member story', 'forgetfit' ); ?></span></footer>
						</blockquote>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php $latest = new WP_Query( array( 'posts_per_page' => 3, 'post_status' => 'publish' ) ); ?>
	<section class="section journal" id="journal">
		<div class="container">
			<div class="section-heading">
				<div><span class="eyebrow"><?php esc_html_e( 'Practical guidance', 'forgetfit' ); ?></span><h2><?php esc_html_e( 'Train smarter between visits.', 'forgetfit' ); ?></h2></div>
				<a class="quiet-link" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Read all stories', 'forgetfit' ); ?> →</a>
			</div>
			<div class="posts-grid">
				<?php if ( $latest->have_posts() ) : ?>
					<?php while ( $latest->have_posts() ) : $latest->the_post(); ?>
						<?php get_template_part( 'template-parts/card', 'post' ); ?>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<?php
					$fallback_articles = array(
						array( __( 'How to build a routine you can keep', 'forgetfit' ), __( 'Consistency starts with a plan that respects your real week.', 'forgetfit' ) ),
						array( __( 'Your first strength session', 'forgetfit' ), __( 'A practical guide to the movements, coaching, and pace.', 'forgetfit' ) ),
						array( __( 'Recovery is training, too', 'forgetfit' ), __( 'Simple ways to help your body feel ready for what comes next.', 'forgetfit' ) ),
					);
					foreach ( $fallback_articles as $article ) :
						?>
						<article class="post-card post-card--placeholder">
							<div class="post-card__image"></div>
							<div class="post-card__body"><div class="post-card__meta"><span><?php esc_html_e( 'Training', 'forgetfit' ); ?></span><span><?php esc_html_e( '5 min read', 'forgetfit' ); ?></span></div><h3><?php echo esc_html( $article[0] ); ?></h3><p><?php echo esc_html( $article[1] ); ?></p></div>
						</article>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="section contact" id="contact">
		<div class="container contact__grid">
			<div class="contact__copy">
				<span class="eyebrow"><?php esc_html_e( 'Visit for a free pass', 'forgetfit' ); ?></span>
				<h2><?php esc_html_e( 'See the space. Meet the team. Try a workout.', 'forgetfit' ); ?></h2>
				<p class="lead"><?php esc_html_e( 'Tell us what you want help with and when you would like to visit. We will make your first session straightforward.', 'forgetfit' ); ?></p>
				<dl>
					<div><dt><?php esc_html_e( 'Visit', 'forgetfit' ); ?></dt><dd><?php echo esc_html( $address ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Call', 'forgetfit' ); ?></dt><dd><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></dd></div>
					<div><dt><?php esc_html_e( 'Hours', 'forgetfit' ); ?></dt><dd><?php esc_html_e( 'Weekdays 5am–11pm · Weekends 7am–9pm', 'forgetfit' ); ?></dd></div>
				</dl>
			</div>
			<?php get_template_part( 'template-parts/form', 'trial' ); ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
