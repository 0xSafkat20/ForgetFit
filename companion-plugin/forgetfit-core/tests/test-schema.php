<?php
/**
 * Schema integration tests.
 */

use ForgetFit\Core\Schema;

class ForgetFit_Core_Schema_Test extends WP_UnitTestCase {
	public function test_all_operational_tables_are_installed() {
		global $wpdb;

		Schema::install();

		foreach ( Schema::tables() as $table ) {
			$actual = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table ) ) );
			$this->assertSame( $table, $actual, 'Missing expected table: ' . $table );
		}

		$this->assertSame( FORGETFIT_CORE_SCHEMA_VERSION, get_option( Schema::OPTION ) );
	}
}
