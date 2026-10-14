<?php
/**
 * Template Name: Privacy Policy
 *
 * @package ForgetFit
 */

get_header();
$email = get_theme_mod( 'forgetfit_email', 'hello@example.com' );
?>
<main id="main" class="site-main policy-page">
	<header class="inner-hero policy-hero"><div class="container"><span class="eyebrow"><?php esc_html_e( 'Your information', 'forgetfit' ); ?></span><h1><?php the_title(); ?></h1><p class="lead"><?php esc_html_e( 'This page explains the information the website may collect and how it may be used.', 'forgetfit' ); ?></p></div></header>
	<section class="content-wrap">
		<div class="container policy-layout">
			<aside><strong><?php esc_html_e( 'Before launch', 'forgetfit' ); ?></strong><p><?php esc_html_e( 'This is a starter privacy notice. Update it for the gym’s real services, providers, retention periods, and local legal requirements.', 'forgetfit' ); ?></p><span><?php printf( esc_html__( 'Page updated %s', 'forgetfit' ), esc_html( get_the_modified_date() ) ); ?></span></aside>
			<article class="policy-content">
				<h2><?php esc_html_e( 'Information you provide', 'forgetfit' ); ?></h2><p><?php esc_html_e( 'When you request a free pass or contact the gym, the website may collect your name, email address, phone number, training interest, and the content of your message.', 'forgetfit' ); ?></p>
				<h2><?php esc_html_e( 'How information is used', 'forgetfit' ); ?></h2><p><?php esc_html_e( 'Information may be used to respond to enquiries, arrange visits, provide requested services, keep necessary business records, prevent misuse, and improve the website.', 'forgetfit' ); ?></p>
				<h2><?php esc_html_e( 'Website and device data', 'forgetfit' ); ?></h2><p><?php esc_html_e( 'The hosting platform, security tools, and any enabled analytics services may process technical details such as IP address, browser type, device information, referring page, and pages visited.', 'forgetfit' ); ?></p>
				<h2><?php esc_html_e( 'Sharing and service providers', 'forgetfit' ); ?></h2><p><?php esc_html_e( 'Information should only be shared with service providers needed to operate the website and gym services, or when required by law. The gym should list its actual hosting, email, analytics, booking, and payment providers here.', 'forgetfit' ); ?></p>
				<h2><?php esc_html_e( 'Retention and security', 'forgetfit' ); ?></h2><p><?php esc_html_e( 'Personal information should be kept only as long as necessary for the stated purpose, legal obligations, and legitimate business records. Reasonable safeguards should be used, although no online system can be guaranteed completely secure.', 'forgetfit' ); ?></p>
				<h2><?php esc_html_e( 'Your choices and rights', 'forgetfit' ); ?></h2><p><?php esc_html_e( 'Depending on local law, you may have rights to request access, correction, deletion, restriction, or a copy of personal information. You may also object to some uses or withdraw consent where consent is the legal basis.', 'forgetfit' ); ?></p>
				<h2><?php esc_html_e( 'Contact', 'forgetfit' ); ?></h2><p><?php printf( wp_kses_post( __( 'For privacy questions or requests, contact <a href="mailto:%1$s">%2$s</a>.', 'forgetfit' ) ), esc_attr( antispambot( $email ) ), esc_html( antispambot( $email ) ) ); ?></p>
			</article>
		</div>
	</section>
</main>
<?php get_footer(); ?>
