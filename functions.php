<?php
/** ForgetFit theme functions. @package ForgetFit */
if ( ! defined( 'ABSPATH' ) ) { exit; }
define( 'FORGETFIT_VERSION', '3.3.0' );

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
	wp_localize_script( 'forgetfit-main', 'forgetfitTheme', array( 'menuOpen' => __( 'Open menu', 'forgetfit' ), 'menuClose' => __( 'Close menu', 'forgetfit' ) ) );
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
 * Create the core marketing and policy pages once for new or existing installs.
 * Existing pages and their content are never overwritten.
 */
function forgetfit_maybe_create_essential_pages() {
	if ( '1' === get_option( 'forgetfit_essential_pages_v3' ) ) {
		return;
	}

	$pages = array(
		'about'              => array( __( 'About the Gym', 'forgetfit' ), 'template-about.php' ),
		'programs'           => array( __( 'Programs', 'forgetfit' ), 'template-programs.php' ),
		'membership'         => array( __( 'Membership and Pricing', 'forgetfit' ), 'template-membership.php' ),
		'trainers'           => array( __( 'Trainers', 'forgetfit' ), 'template-trainers.php' ),
		'contact'            => array( __( 'Contact and Directions', 'forgetfit' ), 'template-contact.php' ),
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
			if ( in_array( get_page_template_slug( $page->ID ), array( '', 'default' ), true ) ) {
				update_post_meta( $page->ID, '_wp_page_template', $details[1] );
			}
		}

		if ( 'privacy-policy' === $slug && $page && ! get_option( 'wp_page_for_privacy_policy' ) ) {
			update_option( 'wp_page_for_privacy_policy', $page->ID );
		}
	}

	update_option( 'forgetfit_essential_pages_v3', '1' );
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

function forgetfit_handle_trial_form() {
	if ( ! isset( $_POST['forgetfit_trial_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['forgetfit_trial_nonce'] ) ), 'forgetfit_trial' ) ) { wp_die( esc_html__( 'Security check failed. Please go back and try again.', 'forgetfit' ) ); }
	$name = isset( $_POST['trial_name'] ) ? sanitize_text_field( wp_unslash( $_POST['trial_name'] ) ) : '';
	$email = isset( $_POST['trial_email'] ) ? sanitize_email( wp_unslash( $_POST['trial_email'] ) ) : '';
	$phone = isset( $_POST['trial_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['trial_phone'] ) ) : '';
	$program = isset( $_POST['trial_program'] ) ? sanitize_text_field( wp_unslash( $_POST['trial_program'] ) ) : '';
	$status = 'error';
	if ( $name && is_email( $email ) ) {
		$target = get_theme_mod( 'forgetfit_email', get_option( 'admin_email' ) );
		$status = wp_mail( $target, sprintf( __( 'New free-pass request from %s', 'forgetfit' ), $name ), sprintf( "Name: %s\nEmail: %s\nPhone: %s\nInterest: %s", $name, $email, $phone, $program ), array( 'Reply-To: ' . $name . ' <' . $email . '>' ) ) ? 'success' : 'error';
	}
	$redirect = wp_get_referer() ?: home_url( '/' );
	wp_safe_redirect( add_query_arg( 'trial', $status, $redirect ) . '#contact' );
	exit;
}
add_action( 'admin_post_nopriv_forgetfit_trial', 'forgetfit_handle_trial_form' );
add_action( 'admin_post_forgetfit_trial', 'forgetfit_handle_trial_form' );

