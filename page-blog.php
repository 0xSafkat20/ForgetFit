<?php
/**
 * Blog page fallback for installs that keep "Your latest posts" as the
 * WordPress homepage setting. A configured Posts page will use home.php.
 *
 * @package ForgetFit
 */

get_header();
$paged      = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
$blog_query = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'paged'               => $paged,
		'posts_per_page'      => (int) get_option( 'posts_per_page', 10 ),
		'ignore_sticky_posts' => false,
	)
);
?>
<main id="main" class="site-main">
	<header class="inner-hero">
		<div class="container">
			<span class="eyebrow"><?php esc_html_e( 'The ForgetFit blog', 'forgetfit' ); ?></span>
			<h1><?php esc_html_e( 'Ideas for moving well and living strong.', 'forgetfit' ); ?></h1>
			<p class="lead"><?php esc_html_e( 'Practical guidance on strength, fitness, recovery, and routines that last.', 'forgetfit' ); ?></p>
		</div>
	</header>
	<div class="content-wrap">
		<div class="container">
			<?php if ( $blog_query->have_posts() ) : ?>
				<div class="archive-grid">
					<?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); get_template_part( 'template-parts/card', 'post' ); endwhile; ?>
				</div>
				<?php if ( $blog_query->max_num_pages > 1 ) : ?>
					<nav class="pagination" aria-label="<?php esc_attr_e( 'Blog pages', 'forgetfit' ); ?>">
						<?php echo wp_kses_post( paginate_links( array( 'current' => $paged, 'total' => $blog_query->max_num_pages, 'type' => 'list' ) ) ); ?>
					</nav>
				<?php endif; ?>
			<?php else : ?>
				<div class="empty-state"><h2><?php esc_html_e( 'The first story is coming soon.', 'forgetfit' ); ?></h2></div>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>
