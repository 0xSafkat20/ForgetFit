<?php
/** Shared call to action for essential pages. @package ForgetFit */
?>
<section class="essential-cta">
	<div class="container essential-cta__inner">
		<div><span class="eyebrow"><?php esc_html_e( 'Try Forget Fit', 'forgetfit' ); ?></span><h2><?php esc_html_e( 'See the gym before you decide.', 'forgetfit' ); ?></h2></div>
		<div><p><?php esc_html_e( 'Tour the space, meet the team, and ask the questions that matter to you.', 'forgetfit' ); ?></p><a class="btn" href="<?php echo esc_url( forgetfit_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'Get a free pass', 'forgetfit' ); ?> <span aria-hidden="true">→</span></a></div>
	</div>
</section>
