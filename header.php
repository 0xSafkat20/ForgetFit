<?php
/**
 * Site header.
 *
 * @package ForgetFit
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'forgetfit' ); ?></a>

<?php
$announcement = get_theme_mod( 'forgetfit_announcement', __( 'Join now and get your first 7 days free', 'forgetfit' ) );
if ( $announcement ) :
	?>
	<div class="announcement">
		<div class="container announcement__inner">
			<p><?php echo esc_html( $announcement ); ?></p>
			<a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">
				<?php esc_html_e( 'Experience the gym for free', 'forgetfit' ); ?>
				<span aria-hidden="true">→</span>
			</a>
		</div>
	</div>
<?php endif; ?>

<header class="site-header" id="site-header">
	<div class="container site-header__inner">
		<div class="site-branding">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a class="site-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<img class="site-brand__logo" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/forgetfit-site-logo-512.png' ); ?>" alt="" width="52" height="52">
					<span class="site-brand__name"><?php bloginfo( 'name' ); ?></span>
				</a>
			<?php endif; ?>
		</div>

		<nav class="site-nav" id="site-navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'forgetfit' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'site-nav__list',
					'fallback_cb'    => 'forgetfit_menu_fallback',
				)
			);
			?>
		</nav>

		<div class="header-actions">
			<a class="text-link" href="<?php echo esc_url( home_url( '/#showcase' ) ); ?>"><?php esc_html_e( 'Tour the club', 'forgetfit' ); ?></a>
			<a class="btn btn--small" href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">
				<?php echo esc_html( get_theme_mod( 'forgetfit_cta_label', __( 'Get a free pass', 'forgetfit' ) ) ); ?>
				<span aria-hidden="true">→</span>
			</a>
			<button class="menu-toggle" type="button" aria-controls="site-navigation" aria-expanded="false">
				<span></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'forgetfit' ); ?></span>
			</button>
		</div>
	</div>
</header>

