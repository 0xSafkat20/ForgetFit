<?php
/** Free-pass enquiry form. @package ForgetFit */
$form_suffix = wp_unique_id( 'trial-' );
$today       = new DateTimeImmutable( 'today', wp_timezone() );
$status      = isset( $_GET['trial'] ) ? sanitize_key( wp_unslash( $_GET['trial'] ) ) : '';
$messages    = array(
	'success' => __( 'Thanks! Your request is on its way. Check your email for the next steps.', 'forgetfit' ),
	'limited' => __( 'Too many requests were sent. Please wait a few minutes and try again.', 'forgetfit' ),
	'error'   => __( 'Please check your details and try again.', 'forgetfit' ),
);
$message     = isset( $messages[ $status ] ) ? $messages[ $status ] : '';
?>
<form class="trial-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" data-trial-form>
	<?php wp_nonce_field( 'forgetfit_trial', 'forgetfit_trial_nonce' ); ?>
	<input type="hidden" name="action" value="forgetfit_trial">
	<input type="hidden" name="trial_started" value="<?php echo esc_attr( time() ); ?>" data-trial-started>

	<div class="form-status<?php echo 'success' === $status ? ' is-success' : ( $message ? ' is-error' : '' ); ?>" role="status" aria-live="polite" tabindex="-1"<?php echo $message ? '' : ' hidden'; ?>><?php echo esc_html( $message ); ?></div>

	<div class="form-field">
		<label for="<?php echo esc_attr( $form_suffix ); ?>name"><?php esc_html_e( 'Name', 'forgetfit' ); ?></label>
		<input id="<?php echo esc_attr( $form_suffix ); ?>name" name="trial_name" type="text" autocomplete="name" maxlength="100" required>
	</div>
	<div class="form-field">
		<label for="<?php echo esc_attr( $form_suffix ); ?>email"><?php esc_html_e( 'Email', 'forgetfit' ); ?></label>
		<input id="<?php echo esc_attr( $form_suffix ); ?>email" name="trial_email" type="email" autocomplete="email" maxlength="190" required>
	</div>
	<div class="form-field">
		<label for="<?php echo esc_attr( $form_suffix ); ?>phone"><?php esc_html_e( 'Phone', 'forgetfit' ); ?></label>
		<input id="<?php echo esc_attr( $form_suffix ); ?>phone" name="trial_phone" type="tel" autocomplete="tel" inputmode="tel" maxlength="30">
	</div>
	<div class="form-field">
		<label for="<?php echo esc_attr( $form_suffix ); ?>date"><?php esc_html_e( 'Preferred visit date', 'forgetfit' ); ?></label>
		<input id="<?php echo esc_attr( $form_suffix ); ?>date" name="trial_date" type="date" min="<?php echo esc_attr( $today->format( 'Y-m-d' ) ); ?>" max="<?php echo esc_attr( $today->modify( '+90 days' )->format( 'Y-m-d' ) ); ?>" required>
	</div>
	<div class="form-field">
		<label for="<?php echo esc_attr( $form_suffix ); ?>time"><?php esc_html_e( 'Preferred time', 'forgetfit' ); ?></label>
		<select id="<?php echo esc_attr( $form_suffix ); ?>time" name="trial_time" required>
			<option value=""><?php esc_html_e( 'Choose a time', 'forgetfit' ); ?></option>
			<?php foreach ( forgetfit_trial_time_options() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="form-field">
		<label for="<?php echo esc_attr( $form_suffix ); ?>experience"><?php esc_html_e( 'Experience level', 'forgetfit' ); ?></label>
		<select id="<?php echo esc_attr( $form_suffix ); ?>experience" name="trial_experience" required>
			<option value=""><?php esc_html_e( 'Choose your level', 'forgetfit' ); ?></option>
			<?php foreach ( forgetfit_trial_experience_options() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="form-field form-field--full">
		<label for="<?php echo esc_attr( $form_suffix ); ?>goal"><?php esc_html_e( 'Fitness goal', 'forgetfit' ); ?></label>
		<select id="<?php echo esc_attr( $form_suffix ); ?>goal" name="trial_goal" required>
			<option value=""><?php esc_html_e( 'What would you like to work on?', 'forgetfit' ); ?></option>
			<?php foreach ( forgetfit_trial_goal_options() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="trial-form__trap" aria-hidden="true">
		<label for="<?php echo esc_attr( $form_suffix ); ?>company"><?php esc_html_e( 'Company', 'forgetfit' ); ?></label>
		<input id="<?php echo esc_attr( $form_suffix ); ?>company" name="trial_company" type="text" tabindex="-1" autocomplete="off">
	</div>

	<label class="form-consent form-field--full">
		<input name="trial_consent" type="checkbox" value="1" required>
		<span>
			<?php
			printf(
				/* translators: %s: privacy policy link. */
				wp_kses_post( __( 'I agree that Forget Fit may contact me about this request and have read the %s.', 'forgetfit' ) ),
				'<a href="' . esc_url( forgetfit_page_url( 'privacy-policy' ) ) . '">' . esc_html__( 'privacy policy', 'forgetfit' ) . '</a>'
			);
			?>
		</span>
	</label>

	<div class="form-field form-field--full">
		<button class="btn trial-form__submit" type="submit">
			<span class="trial-form__button-label"><?php echo esc_html( get_theme_mod( 'forgetfit_cta_label', __( 'Get a free pass', 'forgetfit' ) ) ); ?></span>
			<span class="trial-form__button-loading" aria-hidden="true"><?php esc_html_e( 'Sending…', 'forgetfit' ); ?></span>
		</button>
	</div>
	<p class="form-note"><?php esc_html_e( 'No pressure. Your preferred time is a request, not a confirmed booking. Our team will follow up.', 'forgetfit' ); ?></p>
</form>
