<?php
/** Site footer. @package ForgetFit */
$phone   = get_theme_mod( 'forgetfit_phone', '+1 (555) 240-9090' );
$email   = get_theme_mod( 'forgetfit_email', 'hello@example.com' );
$address = get_theme_mod( 'forgetfit_address', __( '204 Iron Street, Downtown', 'forgetfit' ) );
?>
<footer class="site-footer">
	<div class="container">
		<div class="footer-intro">
			<div><span class="eyebrow"><?php esc_html_e( 'Start today', 'forgetfit' ); ?></span><h2><?php esc_html_e( 'Ready to get stronger?', 'forgetfit' ); ?></h2></div>
			<a class="btn btn--light" href="<?php echo esc_url( forgetfit_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'Claim your free pass', 'forgetfit' ); ?></a>
		</div>
		<div class="footer-grid">
			<div class="footer-brand">
				<a class="site-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="site-brand__mark">FF</span><span><?php bloginfo( 'name' ); ?></span></a>
				<p><?php esc_html_e( 'A modern gym with serious equipment, expert coaching, and a community that keeps you moving forward.', 'forgetfit' ); ?></p>
			</div>
			<div>
				<h3><?php esc_html_e( 'Explore', 'forgetfit' ); ?></h3>
				<ul><li><a href="<?php echo esc_url( forgetfit_page_url( 'about' ) ); ?>"><?php esc_html_e( 'About the gym', 'forgetfit' ); ?></a></li><li><a href="<?php echo esc_url( forgetfit_page_url( 'programs' ) ); ?>"><?php esc_html_e( 'Programs', 'forgetfit' ); ?></a></li><li><a href="<?php echo esc_url( forgetfit_page_url( 'membership' ) ); ?>"><?php esc_html_e( 'Membership', 'forgetfit' ); ?></a></li><li><a href="<?php echo esc_url( forgetfit_page_url( 'trainers' ) ); ?>"><?php esc_html_e( 'Trainers', 'forgetfit' ); ?></a></li><li><a href="<?php echo esc_url( forgetfit_page_url( 'faq' ) ); ?>"><?php esc_html_e( 'FAQs', 'forgetfit' ); ?></a></li><li><a href="<?php echo esc_url( forgetfit_page_url( 'blog' ) ); ?>"><?php esc_html_e( 'Blog', 'forgetfit' ); ?></a></li></ul>
			</div>
			<div>
				<h3><?php esc_html_e( 'Visit', 'forgetfit' ); ?></h3>
				<address class="footer-contact"><?php echo esc_html( $address ); ?><br><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a><br><a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a><br><a href="<?php echo esc_url( forgetfit_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'Directions and contact', 'forgetfit' ); ?> →</a></address>
			</div>
			<div>
				<h3><?php esc_html_e( 'Hours', 'forgetfit' ); ?></h3>
				<ul><li><?php esc_html_e( 'Weekdays · 5am–11pm', 'forgetfit' ); ?></li><li><?php esc_html_e( 'Saturday · 7am–9pm', 'forgetfit' ); ?></li><li><?php esc_html_e( 'Sunday · 8am–8pm', 'forgetfit' ); ?></li></ul>
				<div class="socials"><?php foreach ( array( 'instagram' => 'Instagram', 'facebook' => 'Facebook', 'youtube' => 'YouTube' ) as $network => $label ) { $url = get_theme_mod( 'forgetfit_' . $network ); if ( $url ) { echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $label ) . ' ↗</a>'; } } ?></div>
			</div>
		</div>
		<?php if ( is_active_sidebar( 'footer-widgets' ) ) : ?><div class="footer-widgets"><?php dynamic_sidebar( 'footer-widgets' ); ?></div><?php endif; ?>
		<div class="footer-bottom">
			<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>.</span>
			<nav class="footer-legal" aria-label="<?php esc_attr_e( 'Legal', 'forgetfit' ); ?>"><a href="<?php echo esc_url( forgetfit_page_url( 'privacy-policy' ) ); ?>"><?php esc_html_e( 'Privacy', 'forgetfit' ); ?></a><a href="<?php echo esc_url( forgetfit_page_url( 'terms-cancellation' ) ); ?>"><?php esc_html_e( 'Terms and cancellation', 'forgetfit' ); ?></a></nav>
			<span><?php esc_html_e( 'Train strong. Live strong.', 'forgetfit' ); ?></span>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
