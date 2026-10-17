<?php
/** ForgetFit theme functions. @package ForgetFit */
if ( ! defined( 'ABSPATH' ) ) { exit; }
define( 'FORGETFIT_VERSION', '3.6.0' );

require_once get_template_directory() . '/inc/starter-content.php';

function forgetfit_setup() {
	load_theme_textdomain( 'forgetfit', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-logo', array( 'height' => 72, 'width' => 240, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'woocommerce' );
	add_editor_style( 'style.css' );
	register_nav_menus( array( 'primary' => __( 'Primary navigation', 'forgetfit' ), 'footer' => __( 'Footer navigation', 'forgetfit' ) ) );
}
add_action( 'after_setup_theme', 'forgetfit_setup' );
add_action( 'after_setup_theme', function() { $GLOBALS['content_width'] = apply_filters( 'forgetfit_content_width', 820 ); }, 0 );

function forgetfit_assets() {
	wp_enqueue_style( 'forgetfit-style', get_stylesheet_uri(), array(), FORGETFIT_VERSION );
	wp_enqueue_script( 'forgetfit-main', get_template_directory_uri() . '/assets/js/main.js', array(), FORGETFIT_VERSION, true );
	wp_localize_script(
		'forgetfit-main',
		'forgetfitTheme',
		array(
			'menuOpen'     => __( 'Open menu', 'forgetfit' ),
			'menuClose'    => __( 'Close menu', 'forgetfit' ),
			'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
			'networkError' => __( 'We could not send your request. Check your connection and try again.', 'forgetfit' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'forgetfit_assets' );

function forgetfit_widgets_init() {
	register_sidebar( array( 'name' => __( 'Footer widgets', 'forgetfit' ), 'id' => 'footer-widgets', 'before_widget' => '<section id="%1$s" class="footer-widget %2$s">', 'after_widget' => '</section>', 'before_title' => '<h3>', 'after_title' => '</h3>' ) );
}
add_action( 'widgets_init', 'forgetfit_widgets_init' );

function forgetfit_register_content_types() {
	register_post_type( 'ff_program', array( 'labels' => array( 'name' => __( 'Programs', 'forgetfit' ), 'singular_name' => __( 'Program', 'forgetfit' ) ), 'public' => true, 'menu_icon' => 'dashicons-universal-access-alt', 'has_archive' => 'training-programs', 'rewrite' => array( 'slug' => 'training-program' ), 'show_in_rest' => true, 'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ) ) );
	register_post_type( 'ff_review', array( 'labels' => array( 'name' => __( 'Member Reviews', 'forgetfit' ), 'singular_name' => __( 'Member Review', 'forgetfit' ) ), 'public' => true, 'menu_icon' => 'dashicons-format-quote', 'has_archive' => false, 'show_in_rest' => true, 'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ) ) );
	register_post_type(
		'ff_trainer',
		array(
			'labels'       => array(
				'name'          => __( 'Trainers', 'forgetfit' ),
				'singular_name' => __( 'Trainer', 'forgetfit' ),
				'add_new_item'  => __( 'Add trainer profile', 'forgetfit' ),
				'edit_item'     => __( 'Edit trainer profile', 'forgetfit' ),
			),
			'public'       => true,
			'menu_icon'    => 'dashicons-groups',
			'has_archive'  => false,
			'rewrite'      => array( 'slug' => 'trainer' ),
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		)
	);
}
add_action( 'init', 'forgetfit_register_content_types' );

/**
 * Return the public URL for a seeded page without hard-coding the install path.
 */
function forgetfit_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . trim( $slug, '/' ) . '/' );
}

/**
 * Return live membership plans with a safe theme-only fallback.
 *
 * @return array<int,array<string,mixed>>
 */
function forgetfit_membership_plans() {
	if ( function_exists( '\\ForgetFit\\Core\\public_plans' ) ) {
		return \ForgetFit\Core\public_plans();
	}

	return array(
		array( 'label' => __( 'Train your way', 'forgetfit' ), 'name' => __( 'Club', 'forgetfit' ), 'price' => '$45', 'period' => __( 'per month', 'forgetfit' ), 'description' => __( 'Everything you need for a flexible independent training routine.', 'forgetfit' ), 'benefits' => array( __( 'Full gym access', 'forgetfit' ), __( 'Fitness orientation', 'forgetfit' ), __( 'Program library', 'forgetfit' ) ), 'featured' => false ),
		array( 'label' => __( 'Most popular', 'forgetfit' ), 'name' => __( 'Together', 'forgetfit' ), 'price' => '$79', 'period' => __( 'per month', 'forgetfit' ), 'description' => __( 'Full access plus the motivation and structure of unlimited group training.', 'forgetfit' ), 'benefits' => array( __( 'Everything in Club', 'forgetfit' ), __( 'Unlimited group sessions', 'forgetfit' ), __( 'Quarterly coach check-in', 'forgetfit' ) ), 'featured' => true ),
		array( 'label' => __( 'Focused progress', 'forgetfit' ), 'name' => __( 'Guided', 'forgetfit' ), 'price' => '$149', 'period' => __( 'per month', 'forgetfit' ), 'description' => __( 'A closer coaching relationship and a plan that evolves with your goals.', 'forgetfit' ), 'benefits' => array( __( 'Everything in Together', 'forgetfit' ), __( 'Two personal sessions monthly', 'forgetfit' ), __( 'Ongoing plan updates', 'forgetfit' ) ), 'featured' => false ),
	);
}

/**
 * Create the core marketing and policy pages once for new or existing installs.
 * Existing pages and their content are never overwritten.
 */
function forgetfit_maybe_create_essential_pages() {
	if ( '1' === get_option( 'forgetfit_essential_pages_v4' ) ) {
		return;
	}

	$pages = array(
		'about'              => array( __( 'About the Gym', 'forgetfit' ), 'template-about.php' ),
		'programs'           => array( __( 'Programs', 'forgetfit' ), 'template-programs.php' ),
		'membership'         => array( __( 'Membership and Pricing', 'forgetfit' ), 'template-membership.php' ),
		'trainers'           => array( __( 'Trainers', 'forgetfit' ), 'template-trainers.php' ),
		'contact'            => array( __( 'Contact and Directions', 'forgetfit' ), 'template-contact.php' ),
		'faq'                => array( __( 'Frequently Asked Questions', 'forgetfit' ), 'template-faq.php' ),
		'blog'               => array( __( 'Blog', 'forgetfit' ), '' ),
		'privacy-policy'     => array( __( 'Privacy Policy', 'forgetfit' ), 'template-privacy-policy.php' ),
		'terms-cancellation' => array( __( 'Terms and Cancellation Policy', 'forgetfit' ), 'template-terms.php' ),
	);
	$created = false;

	foreach ( $pages as $slug => $details ) {
		$page = get_page_by_path( $slug );
		if ( ! $page ) {
			$page_id = wp_insert_post(
				array(
					'post_title'   => $details[0],
					'post_name'    => $slug,
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_content' => '',
				)
			);
			if ( ! is_wp_error( $page_id ) ) {
				update_post_meta( $page_id, '_wp_page_template', $details[1] );
				$page    = get_post( $page_id );
				$created = true;
			}
		} else {
			if ( 'publish' !== $page->post_status ) {
				wp_update_post( array( 'ID' => $page->ID, 'post_status' => 'publish' ) );
				$page = get_post( $page->ID );
			}
			if ( $details[1] && in_array( get_page_template_slug( $page->ID ), array( '', 'default' ), true ) ) {
				update_post_meta( $page->ID, '_wp_page_template', $details[1] );
			}
		}

		if ( 'privacy-policy' === $slug && $page && ! get_option( 'wp_page_for_privacy_policy' ) ) {
			update_option( 'wp_page_for_privacy_policy', $page->ID );
		}
		if ( 'blog' === $slug && $page && ! get_option( 'page_for_posts' ) ) {
			update_option( 'page_for_posts', $page->ID );
		}
	}

	update_option( 'forgetfit_essential_pages_v4', '1' );
	if ( $created ) {
		flush_rewrite_rules( false );
	}
}
add_action( 'init', 'forgetfit_maybe_create_essential_pages', 30 );

function forgetfit_customize_register( $customizer ) {
	$customizer->add_section( 'forgetfit_home', array( 'title' => __( 'ForgetFit homepage', 'forgetfit' ), 'priority' => 30 ) );
	$fields = array(
		'announcement' => array( __( 'Announcement bar', 'forgetfit' ), __( 'Join now and get your first 7 days free', 'forgetfit' ), 'sanitize_text_field' ),
		'hero_kicker' => array( __( 'Hero eyebrow', 'forgetfit' ), __( 'Training for every body', 'forgetfit' ), 'sanitize_text_field' ),
		'hero_title' => array( __( 'Hero title', 'forgetfit' ), __( 'Stronger starts today.', 'forgetfit' ), 'sanitize_text_field' ),
		'hero_text' => array( __( 'Hero description', 'forgetfit' ), __( 'Everything you need to build strength, boost fitness, and keep moving forward—all in one motivating gym.', 'forgetfit' ), 'sanitize_textarea_field' ),
		'cta_label' => array( __( 'Primary button label', 'forgetfit' ), __( 'Get a free pass', 'forgetfit' ), 'sanitize_text_field' ),
		'cta_url' => array( __( 'Primary button URL', 'forgetfit' ), '#contact', 'esc_url_raw' ),
		'phone' => array( __( 'Phone number', 'forgetfit' ), '+1 (555) 240-9090', 'sanitize_text_field' ),
		'email' => array( __( 'Contact email', 'forgetfit' ), 'hello@example.com', 'sanitize_email' ),
		'address' => array( __( 'Gym address', 'forgetfit' ), __( '204 Iron Street, Downtown', 'forgetfit' ), 'sanitize_text_field' ),
		'instagram' => array( __( 'Instagram URL', 'forgetfit' ), '', 'esc_url_raw' ),
		'facebook' => array( __( 'Facebook URL', 'forgetfit' ), '', 'esc_url_raw' ),
		'youtube' => array( __( 'YouTube URL', 'forgetfit' ), '', 'esc_url_raw' ),
		'instagram_followers' => array( __( 'Instagram follower count', 'forgetfit' ), '18.4k', 'sanitize_text_field' ),
		'facebook_followers' => array( __( 'Facebook follower count', 'forgetfit' ), '12.1k', 'sanitize_text_field' ),
		'youtube_followers' => array( __( 'YouTube subscriber count', 'forgetfit' ), '6.8k', 'sanitize_text_field' ),
	);
	foreach ( $fields as $id => $field ) {
		$customizer->add_setting( 'forgetfit_' . $id, array( 'default' => $field[1], 'sanitize_callback' => $field[2], 'transport' => 'refresh' ) );
		$customizer->add_control( 'forgetfit_' . $id, array( 'label' => $field[0], 'section' => 'forgetfit_home', 'type' => 'hero_text' === $id ? 'textarea' : 'text' ) );
	}
	$customizer->add_setting( 'forgetfit_accent', array( 'default' => '#ffd100', 'sanitize_callback' => 'sanitize_hex_color' ) );
	$customizer->add_control( new WP_Customize_Color_Control( $customizer, 'forgetfit_accent', array( 'label' => __( 'Accent color', 'forgetfit' ), 'section' => 'colors' ) ) );
}
add_action( 'customize_register', 'forgetfit_customize_register' );
add_action( 'wp_head', function() { $accent = get_theme_mod( 'forgetfit_accent', '#ffd100' ) ?: '#ffd100'; echo '<style id="forgetfit-customizer">:root{--accent:' . esc_attr( $accent ) . ';}</style>'; }, 20 );

function forgetfit_menu_fallback() {
	echo '<ul class="site-nav__list">';
	$links = array(
		home_url( '/' )                 => __( 'Home', 'forgetfit' ),
		forgetfit_page_url( 'about' )   => __( 'About', 'forgetfit' ),
		forgetfit_page_url( 'programs' ) => __( 'Programs', 'forgetfit' ),
		forgetfit_page_url( 'membership' ) => __( 'Membership', 'forgetfit' ),
		forgetfit_page_url( 'trainers' ) => __( 'Trainers', 'forgetfit' ),
		forgetfit_page_url( 'contact' ) => __( 'Contact', 'forgetfit' ),
	);
	foreach ( $links as $url => $label ) {
		echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul>';
}

function forgetfit_reading_time() { return max( 1, (int) ceil( str_word_count( wp_strip_all_tags( get_post_field( 'post_content', get_the_ID() ) ) ) / 220 ) ); }

/** Form option allow-lists. */
function forgetfit_trial_time_options() {
	return array(
		'no-preference' => __( 'No preference', 'forgetfit' ),
		'morning'       => __( 'Morning · 5am–10am', 'forgetfit' ),
		'midday'        => __( 'Midday · 10am–2pm', 'forgetfit' ),
		'afternoon'     => __( 'Afternoon · 2pm–6pm', 'forgetfit' ),
		'evening'       => __( 'Evening · 6pm–close', 'forgetfit' ),
	);
}

function forgetfit_trial_goal_options() {
	return array(
		'strength'          => __( 'Build strength', 'forgetfit' ),
		'fitness'           => __( 'Improve fitness and energy', 'forgetfit' ),
		'classes'           => __( 'Join group classes', 'forgetfit' ),
		'personal-coaching' => __( 'Work with a personal coach', 'forgetfit' ),
		'returning'         => __( 'Return to a regular routine', 'forgetfit' ),
		'not-sure'          => __( 'I am not sure yet', 'forgetfit' ),
	);
}

function forgetfit_trial_experience_options() {
	return array(
		'new'         => __( 'New to the gym', 'forgetfit' ),
		'returning'   => __( 'Returning after a break', 'forgetfit' ),
		'regular'     => __( 'Train regularly', 'forgetfit' ),
		'experienced' => __( 'Experienced lifter or athlete', 'forgetfit' ),
	);
}

/** Return a privacy-preserving request fingerprint for rate limiting. */
function forgetfit_trial_request_fingerprint() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$ua = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : 'unknown';
	return hash_hmac( 'sha256', $ip . '|' . $ua, wp_salt( 'nonce' ) );
}

/** Increment a short-lived request counter and report when its limit is exceeded. */
function forgetfit_trial_rate_limited( $bucket, $limit, $ttl ) {
	$key   = 'ff_trial_' . sanitize_key( $bucket ) . '_' . forgetfit_trial_request_fingerprint();
	$count = (int) get_transient( $key );
	if ( $count >= $limit ) {
		return true;
	}
	set_transient( $key, $count + 1, $ttl );
	return false;
}

/** Validate and dispatch a free-pass request for both AJAX and non-JS submissions. */
function forgetfit_process_trial_request() {
	$nonce = isset( $_POST['forgetfit_trial_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['forgetfit_trial_nonce'] ) ) : '';
	if ( ! $nonce || ! wp_verify_nonce( $nonce, 'forgetfit_trial' ) ) {
		return new WP_Error( 'security', __( 'Your form session expired. Refresh the page and try again.', 'forgetfit' ), array( 'status' => 403 ) );
	}

	if ( forgetfit_trial_rate_limited( 'attempts', 6, 15 * MINUTE_IN_SECONDS ) ) {
		return new WP_Error( 'rate_limited', __( 'Too many requests were sent. Please wait 15 minutes and try again.', 'forgetfit' ), array( 'status' => 429 ) );
	}

	$honeypot = isset( $_POST['trial_company'] ) ? sanitize_text_field( wp_unslash( $_POST['trial_company'] ) ) : '';
	if ( $honeypot ) {
		return array( 'message' => __( 'Thanks! Your request is on its way. Check your email for the next steps.', 'forgetfit' ) );
	}

	$started = isset( $_POST['trial_started'] ) ? absint( $_POST['trial_started'] ) : 0;
	$elapsed = time() - $started;
	if ( ! $started || $elapsed < 3 || $elapsed > 2 * HOUR_IN_SECONDS ) {
		return new WP_Error( 'timing', __( 'Please wait a moment, then submit the form again.', 'forgetfit' ), array( 'status' => 422 ) );
	}

	$name       = isset( $_POST['trial_name'] ) ? sanitize_text_field( wp_unslash( $_POST['trial_name'] ) ) : '';
	$email      = isset( $_POST['trial_email'] ) ? sanitize_email( wp_unslash( $_POST['trial_email'] ) ) : '';
	$phone      = isset( $_POST['trial_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['trial_phone'] ) ) : '';
	$date_value = isset( $_POST['trial_date'] ) ? sanitize_text_field( wp_unslash( $_POST['trial_date'] ) ) : '';
	$time_key   = isset( $_POST['trial_time'] ) ? sanitize_key( wp_unslash( $_POST['trial_time'] ) ) : '';
	$goal_key   = isset( $_POST['trial_goal'] ) ? sanitize_key( wp_unslash( $_POST['trial_goal'] ) ) : '';
	$level_key  = isset( $_POST['trial_experience'] ) ? sanitize_key( wp_unslash( $_POST['trial_experience'] ) ) : '';
	$request_id = isset( $_POST['trial_request_token'] ) ? sanitize_text_field( wp_unslash( $_POST['trial_request_token'] ) ) : '';
	$source     = isset( $_POST['trial_source'] ) ? sanitize_key( wp_unslash( $_POST['trial_source'] ) ) : 'website';
	$consent    = isset( $_POST['trial_consent'] ) && '1' === sanitize_text_field( wp_unslash( $_POST['trial_consent'] ) );
	$times      = forgetfit_trial_time_options();
	$goals      = forgetfit_trial_goal_options();
	$levels     = forgetfit_trial_experience_options();

	if ( ! $name || strlen( $name ) > 100 ) {
		return new WP_Error( 'name', __( 'Enter your name.', 'forgetfit' ), array( 'status' => 422, 'field' => 'trial_name' ) );
	}
	if ( ! is_email( $email ) ) {
		return new WP_Error( 'email', __( 'Enter a valid email address.', 'forgetfit' ), array( 'status' => 422, 'field' => 'trial_email' ) );
	}
	if ( strlen( $phone ) > 30 || ( $phone && ! preg_match( '/^[0-9+().\-\s]+$/', $phone ) ) ) {
		return new WP_Error( 'phone', __( 'Enter a valid phone number.', 'forgetfit' ), array( 'status' => 422, 'field' => 'trial_phone' ) );
	}

	$date   = DateTimeImmutable::createFromFormat( '!Y-m-d', $date_value, wp_timezone() );
	$errors = DateTimeImmutable::getLastErrors();
	$today  = new DateTimeImmutable( 'today', wp_timezone() );
	if ( ! $date || ( is_array( $errors ) && ( $errors['warning_count'] || $errors['error_count'] ) ) || $date->format( 'Y-m-d' ) !== $date_value || $date < $today || $date > $today->modify( '+90 days' ) ) {
		return new WP_Error( 'date', __( 'Choose a visit date within the next 90 days.', 'forgetfit' ), array( 'status' => 422, 'field' => 'trial_date' ) );
	}
	if ( ! isset( $times[ $time_key ] ) ) {
		return new WP_Error( 'time', __( 'Choose a preferred visit time.', 'forgetfit' ), array( 'status' => 422, 'field' => 'trial_time' ) );
	}
	if ( ! isset( $goals[ $goal_key ] ) ) {
		return new WP_Error( 'goal', __( 'Choose a fitness goal.', 'forgetfit' ), array( 'status' => 422, 'field' => 'trial_goal' ) );
	}
	if ( ! isset( $levels[ $level_key ] ) ) {
		return new WP_Error( 'experience', __( 'Choose your experience level.', 'forgetfit' ), array( 'status' => 422, 'field' => 'trial_experience' ) );
	}
	if ( ! $consent ) {
		return new WP_Error( 'consent', __( 'Please agree to the privacy notice before submitting.', 'forgetfit' ), array( 'status' => 422, 'field' => 'trial_consent' ) );
	}
	if ( ! preg_match( '/^[a-f0-9-]{36}$/i', $request_id ) ) {
		return new WP_Error( 'request_id', __( 'Refresh the page and submit the form again.', 'forgetfit' ), array( 'status' => 422 ) );
	}
	if ( forgetfit_trial_rate_limited( 'accepted', 2, HOUR_IN_SECONDS ) ) {
		return new WP_Error( 'rate_limited', __( 'This request has already been sent. Please wait before submitting another.', 'forgetfit' ), array( 'status' => 429 ) );
	}

	$lead_result = null;
	if ( function_exists( '\\ForgetFit\\Core\\capture_lead' ) ) {
		$visit_utc  = $date->setTime( 0, 0 )->setTimezone( new DateTimeZone( 'UTC' ) )->format( 'Y-m-d H:i:s' );
		$lead_result = \ForgetFit\Core\capture_lead(
			array(
				'request_token'   => $request_id,
				'name'            => $name,
				'email'           => $email,
				'phone'           => $phone,
				'interest'        => 'free-pass',
				'preferred_visit' => $visit_utc,
				'preferred_time'  => $time_key,
				'fitness_goal'    => $goal_key,
				'experience_level'=> $level_key,
				'source'          => $source,
				'privacy_version' => 'theme-' . FORGETFIT_VERSION,
				'consent_at'      => current_time( 'mysql', true ),
			)
		);
		if ( is_wp_error( $lead_result ) ) {
			return new WP_Error( 'save_failed', __( 'We could not save your request right now. Please call the gym or try again shortly.', 'forgetfit' ), array( 'status' => 500 ) );
		}
		if ( empty( $lead_result['created'] ) ) {
			return array( 'message' => __( 'Thanks! We already received this request and the team will follow up.', 'forgetfit' ) );
		}
	}

	$target = sanitize_email( get_theme_mod( 'forgetfit_email', get_option( 'admin_email' ) ) );
	if ( ! is_email( $target ) ) {
		$target = sanitize_email( get_option( 'admin_email' ) );
	}
	$address       = sanitize_text_field( get_theme_mod( 'forgetfit_address', __( '204 Iron Street, Downtown', 'forgetfit' ) ) );
	$contact_phone = sanitize_text_field( get_theme_mod( 'forgetfit_phone', '+1 (555) 240-9090' ) );
	$date_label    = wp_date( get_option( 'date_format' ), $date->getTimestamp(), wp_timezone() );
	$staff_body    = sprintf(
		/* translators: 1: name, 2: email, 3: phone, 4: date, 5: time, 6: goal, 7: experience. */
		__( "Name: %1\$s\nEmail: %2\$s\nPhone: %3\$s\nPreferred date: %4\$s\nPreferred time: %5\$s\nFitness goal: %6\$s\nExperience: %7\$s", 'forgetfit' ),
		$name,
		$email,
		$phone ? $phone : __( 'Not provided', 'forgetfit' ),
		$date_label,
		$times[ $time_key ],
		$goals[ $goal_key ],
		$levels[ $level_key ]
	);
	if ( is_array( $lead_result ) && ! empty( $lead_result['id'] ) ) {
		$staff_body .= "\n" . sprintf(
			/* translators: %s: admin lead record URL. */
			__( 'Lead record: %s', 'forgetfit' ),
			admin_url( 'admin.php?page=forgetfit-leads&lead_id=' . absint( $lead_result['id'] ) )
		);
	}
	$headers    = array( 'Reply-To: ' . str_replace( array( "\r", "\n" ), '', $name ) . ' <' . $email . '>' );
	$staff_sent = wp_mail( $target, sprintf( __( 'New free-pass request — %1$s — %2$s', 'forgetfit' ), $name, $date_label ), $staff_body, $headers );
	if ( ! $staff_sent ) {
		if ( ! is_array( $lead_result ) || empty( $lead_result['id'] ) ) {
			return new WP_Error( 'mail_failed', __( 'We could not send your request right now. Please call the gym or try again shortly.', 'forgetfit' ), array( 'status' => 500 ) );
		}
		error_log( 'Forget Fit: staff notification email could not be sent; the lead remains saved.' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}

	$visitor_body = sprintf(
		/* translators: 1: name, 2: date, 3: time, 4: address, 5: phone, 6: contact URL, 7: privacy URL. */
		__( "Hi %1\$s,\n\nWe received your free-pass request for %2\$s (%3\$s). This is a preferred time, not a confirmed booking; our team will contact you to confirm.\n\nGym: %4\$s\nPhone: %5\$s\nContact and directions: %6\$s\nPrivacy policy: %7\$s\n\nTrain strong. Live strong.\nForget Fit", 'forgetfit' ),
		$name,
		$date_label,
		$times[ $time_key ],
		$address,
		$contact_phone,
		forgetfit_page_url( 'contact' ),
		forgetfit_page_url( 'privacy-policy' )
	);
	if ( ! wp_mail( $email, __( 'We received your Forget Fit free-pass request', 'forgetfit' ), $visitor_body ) ) {
		error_log( 'Forget Fit: visitor confirmation email could not be sent.' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}

	return array( 'message' => __( 'Thanks! Your request is on its way. Check your email for the next steps.', 'forgetfit' ) );
}

function forgetfit_handle_trial_form() {
	$result   = forgetfit_process_trial_request();
	$status   = is_wp_error( $result ) ? ( 'rate_limited' === $result->get_error_code() ? 'limited' : 'error' ) : 'success';
	$redirect = wp_get_referer() ?: forgetfit_page_url( 'contact' );
	wp_safe_redirect( add_query_arg( 'trial', $status, remove_query_arg( 'trial', $redirect ) ) . '#contact' );
	exit;
}
add_action( 'admin_post_nopriv_forgetfit_trial', 'forgetfit_handle_trial_form' );
add_action( 'admin_post_forgetfit_trial', 'forgetfit_handle_trial_form' );

function forgetfit_handle_trial_ajax() {
	$result = forgetfit_process_trial_request();
	if ( is_wp_error( $result ) ) {
		$data = $result->get_error_data();
		wp_send_json_error(
			array(
				'message' => $result->get_error_message(),
				'field'   => is_array( $data ) && isset( $data['field'] ) ? $data['field'] : '',
			),
			is_array( $data ) && isset( $data['status'] ) ? (int) $data['status'] : 400
		);
	}
	wp_send_json_success( $result );
}
add_action( 'wp_ajax_nopriv_forgetfit_trial_ajax', 'forgetfit_handle_trial_ajax' );
add_action( 'wp_ajax_forgetfit_trial_ajax', 'forgetfit_handle_trial_ajax' );

