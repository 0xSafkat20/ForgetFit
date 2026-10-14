<?php
/**
 * Template Name: Contact and Directions
 *
 * @package ForgetFit
 */

get_header();
$address  = get_theme_mod( 'forgetfit_address', __( '204 Iron Street, Downtown', 'forgetfit' ) );
$phone    = get_theme_mod( 'forgetfit_phone', '+1 (555) 240-9090' );
$email    = get_theme_mod( 'forgetfit_email', 'hello@example.com' );
$tel      = preg_replace( '/[^0-9+]/', '', $phone );
$maps_url = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $address );
?>
<main id="main" class="site-main essential-page">
	<header class="inner-hero essential-hero">
		<div class="container essential-hero__grid">
			<div><span class="eyebrow"><?php esc_html_e( 'Contact and directions', 'forgetfit' ); ?></span><h1><?php esc_html_e( 'Come and see the gym.', 'forgetfit' ); ?></h1><p class="lead"><?php esc_html_e( 'Arrange a free pass, ask a question, or plan your first visit.', 'forgetfit' ); ?></p></div>
			<a class="btn" href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Open directions', 'forgetfit' ); ?> ↗</a>
		</div>
	</header>

	<section class="section contact" id="contact">
		<div class="container contact__grid">
			<div class="contact__copy">
				<span class="eyebrow"><?php esc_html_e( 'Get in touch', 'forgetfit' ); ?></span>
				<h2><?php esc_html_e( 'We are ready when you are.', 'forgetfit' ); ?></h2>
				<p class="lead"><?php esc_html_e( 'Tell us what you want help with. The team will respond with a clear next step.', 'forgetfit' ); ?></p>
				<dl>
					<div><dt><?php esc_html_e( 'Visit', 'forgetfit' ); ?></dt><dd><?php echo esc_html( $address ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Call', 'forgetfit' ); ?></dt><dd><a href="tel:<?php echo esc_attr( $tel ); ?>"><?php echo esc_html( $phone ); ?></a></dd></div>
					<div><dt><?php esc_html_e( 'Email', 'forgetfit' ); ?></dt><dd><a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a></dd></div>
					<div><dt><?php esc_html_e( 'Hours', 'forgetfit' ); ?></dt><dd><?php esc_html_e( 'Weekdays 5am–11pm · Saturday 7am–9pm · Sunday 8am–8pm', 'forgetfit' ); ?></dd></div>
				</dl>
			</div>
			<?php get_template_part( 'template-parts/form', 'trial' ); ?>
		</div>
	</section>

	<section class="section section--cream">
		<div class="container directions-grid">
			<div class="directions-card directions-card--visual"><span class="directions-card__pin" aria-hidden="true">+</span><div><span class="eyebrow"><?php esc_html_e( 'Find us', 'forgetfit' ); ?></span><h2><?php esc_html_e( 'Plan your visit.', 'forgetfit' ); ?></h2></div></div>
			<div class="directions-card">
				<span class="eyebrow"><?php esc_html_e( 'Gym address', 'forgetfit' ); ?></span><h3><?php echo esc_html( $address ); ?></h3><p><?php esc_html_e( 'Use the directions link for a live route from your current location. Add confirmed parking and public-transport details here before launch.', 'forgetfit' ); ?></p>
				<div class="direction-actions"><a class="btn" href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Google Maps', 'forgetfit' ); ?> ↗</a><a class="btn btn--outline" href="tel:<?php echo esc_attr( $tel ); ?>"><?php esc_html_e( 'Call the gym', 'forgetfit' ); ?></a></div>
			</div>
		</div>
	</section>
</main>
<?php get_footer(); ?>
