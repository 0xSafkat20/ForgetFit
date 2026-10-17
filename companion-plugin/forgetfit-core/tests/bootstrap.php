<?php
/**
 * PHPUnit bootstrap for the standard WordPress test suite.
 */

$tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $tests_dir ) {
	$tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

$polyfills_dir = dirname( __DIR__ ) . '/vendor/yoast/phpunit-polyfills';
if ( is_dir( $polyfills_dir ) && ! defined( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH' ) ) {
	define( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH', $polyfills_dir );
}

if ( ! file_exists( $tests_dir . '/includes/functions.php' ) ) {
	fwrite( STDERR, "WordPress test library not found. Set WP_TESTS_DIR.\n" );
	exit( 1 );
}

require_once $tests_dir . '/includes/functions.php';

tests_add_filter(
	'muplugins_loaded',
	function () {
		require dirname( __DIR__ ) . '/forgetfit-core.php';
	}
);

require $tests_dir . '/includes/bootstrap.php';
