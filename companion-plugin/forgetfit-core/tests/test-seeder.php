<?php
/**
 * Starter plan integration tests.
 */

use ForgetFit\Core\Schema;
use ForgetFit\Core\Seeder;

class ForgetFit_Core_Seeder_Test extends WP_UnitTestCase {
	public function test_plan_seed_is_idempotent() {
		global $wpdb;

		Schema::install();
		$table = Schema::tables()['plans'];
		$wpdb->query( "TRUNCATE TABLE {$table}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.DirectDatabaseQuery.SchemaChange

		$this->assertSame( 3, Seeder::seed_plans() );
		$this->assertSame( 0, Seeder::seed_plans() );
		$this->assertSame( 3, (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table}" ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$this->assertSame( 1, (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table} WHERE is_featured = 1" ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	}
}
