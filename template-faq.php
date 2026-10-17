<?php
/**
 * Template Name: Frequently Asked Questions
 *
 * @package ForgetFit
 */

get_header();
$faq_groups = array(
	'visit'      => array(
		'title'       => __( 'Your first visit', 'forgetfit' ),
		'description' => __( 'Practical answers to help you arrive prepared and feel comfortable from the start.', 'forgetfit' ),
	),
	'membership' => array(
		'title'       => __( 'Membership details', 'forgetfit' ),
		'description' => __( 'The important things to check before changing, pausing, or ending a membership.', 'forgetfit' ),
	),
);
?>
<main id="main" class="site-main essential-page">
	<header class="inner-hero essential-hero">
		<div class="container essential-hero__grid">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'Useful FAQs', 'forgetfit' ); ?></span>
				<h1><?php esc_html_e( 'Know before you go.', 'forgetfit' ); ?></h1>
				<p class="lead"><?php esc_html_e( 'Straight answers about your first visit, beginner support, facilities, memberships, and the free pass.', 'forgetfit' ); ?></p>
			</div>
			<a class="btn" href="<?php echo esc_url( forgetfit_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'Ask the team', 'forgetfit' ); ?></a>
		</div>
	</header>

	<section class="section">
		<div class="container">
			<?php foreach ( $faq_groups as $group_key => $group ) : ?>
				<section class="faq-group" aria-labelledby="faq-<?php echo esc_attr( $group_key ); ?>">
					<header class="faq-group__head">
						<h2 id="faq-<?php echo esc_attr( $group_key ); ?>"><?php echo esc_html( $group['title'] ); ?></h2>
						<p><?php echo esc_html( $group['description'] ); ?></p>
					</header>
					<div class="faq-list faq-accordion">
						<?php foreach ( forgetfit_faq_items( $group_key ) as $index => $faq ) : ?>
							<details class="faq-item"<?php echo 0 === $index ? ' open' : ''; ?>><summary><span><?php echo esc_html( $faq['question'] ); ?></span><span class="faq-item__icon" aria-hidden="true">+</span></summary><p><?php echo esc_html( $faq['answer'] ); ?></p></details>
						<?php endforeach; ?>
					</div>
				</section>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="section section--cream">
		<div class="container">
			<div class="section-heading"><div><span class="eyebrow"><?php esc_html_e( 'Beginner guides', 'forgetfit' ); ?></span><h2><?php esc_html_e( 'Build confidence one step at a time.', 'forgetfit' ); ?></h2></div><p><?php esc_html_e( 'Five practical guides for starting well, learning the basics, and building a routine that lasts.', 'forgetfit' ); ?></p></div>
			<div class="blog-plan">
				<?php foreach ( forgetfit_starter_blog_posts() as $index => $article ) : ?>
					<?php $article_post = get_page_by_path( $article['slug'], OBJECT, 'post' ); ?>
					<article><span><?php printf( esc_html__( 'Guide %02d', 'forgetfit' ), (int) $index + 1 ); ?></span><h3><?php echo esc_html( $article['title'] ); ?></h3><p><?php echo esc_html( $article['excerpt'] ); ?></p><a class="quiet-link" href="<?php echo esc_url( $article_post ? get_permalink( $article_post ) : forgetfit_page_url( 'blog' ) ); ?>"><?php esc_html_e( 'Read guide', 'forgetfit' ); ?> →</a></article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/page', 'cta' ); ?>
</main>
<?php get_footer(); ?>
