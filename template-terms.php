<?php
/**
 * Template Name: Terms and Cancellation Policy
 *
 * @package ForgetFit
 */

get_header();
$email = get_theme_mod( 'forgetfit_email', 'hello@example.com' );
?>
<main id="main" class="site-main policy-page">
	<header class="inner-hero policy-hero"><div class="container"><span class="eyebrow"><?php esc_html_e( 'Membership information', 'forgetfit' ); ?></span><h1><?php the_title(); ?></h1><p class="lead"><?php esc_html_e( 'Important conditions for website use, trial visits, memberships, payments, and cancellation requests.', 'forgetfit' ); ?></p></div></header>
	<section class="content-wrap">
		<div class="container policy-layout">
			<aside><strong><?php esc_html_e( 'Before launch', 'forgetfit' ); ?></strong><p><?php esc_html_e( 'This starter policy is not a substitute for the gym’s signed membership agreement or legal advice. Add the confirmed notice period, billing dates, refunds, freezes, age rules, and jurisdiction.', 'forgetfit' ); ?></p><span><?php printf( esc_html__( 'Page updated %s', 'forgetfit' ), esc_html( get_the_modified_date() ) ); ?></span></aside>
			<article class="policy-content">
				<h2><?php esc_html_e( 'Website information', 'forgetfit' ); ?></h2><p><?php esc_html_e( 'Website content is general information and may change. Membership availability, prices, promotions, timetables, and services must be confirmed with the gym before purchase.', 'forgetfit' ); ?></p>
				<h2><?php esc_html_e( 'Free passes and introductory offers', 'forgetfit' ); ?></h2><p><?php esc_html_e( 'Trial offers may be limited to eligible new visitors, identification may be required, and booking may be subject to capacity. The gym should publish the real eligibility, duration, and expiry conditions for each offer.', 'forgetfit' ); ?></p>
				<h2><?php esc_html_e( 'Membership agreement', 'forgetfit' ); ?></h2><p><?php esc_html_e( 'A membership begins only when the gym accepts the application and the member agrees to the applicable membership terms. The signed or accepted membership agreement takes priority over general website wording.', 'forgetfit' ); ?></p>
				<h2><?php esc_html_e( 'Payments and renewals', 'forgetfit' ); ?></h2><p><?php esc_html_e( 'The gym should state all joining fees, recurring charges, billing dates, taxes, renewal arrangements, and the process for failed payments before a member commits.', 'forgetfit' ); ?></p>
				<h2><?php esc_html_e( 'Cancellation requests', 'forgetfit' ); ?></h2><p><?php esc_html_e( 'Cancellation requests should be submitted using the contact method stated in the membership agreement. A request is not complete until the gym confirms receipt and any applicable notice period, final payment, or outstanding balance.', 'forgetfit' ); ?></p>
				<h2><?php esc_html_e( 'Freezes, refunds, and exceptional circumstances', 'forgetfit' ); ?></h2><p><?php esc_html_e( 'Eligibility for membership freezes, refunds, medical exceptions, relocation, or other early termination depends on the confirmed membership terms and applicable law.', 'forgetfit' ); ?></p>
				<h2><?php esc_html_e( 'Safe and respectful use', 'forgetfit' ); ?></h2><p><?php esc_html_e( 'Members and visitors must follow staff instructions, equipment guidance, health and safety rules, and standards of respectful conduct. Access may be restricted where safety or serious misconduct requires it, subject to the membership agreement and law.', 'forgetfit' ); ?></p>
				<h2><?php esc_html_e( 'Questions and requests', 'forgetfit' ); ?></h2><p><?php printf( wp_kses_post( __( 'Contact <a href="mailto:%1$s">%2$s</a> for membership terms or to start a cancellation request.', 'forgetfit' ) ), esc_attr( antispambot( $email ) ), esc_html( antispambot( $email ) ) ); ?></p>
			</article>
		</div>
	</section>
</main>
<?php get_footer(); ?>
