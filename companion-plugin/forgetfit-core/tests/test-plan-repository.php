<?php
/**
 * Plan repository integration tests.
 */

use ForgetFit\Core\Plan_Repository;
use ForgetFit\Core\Schema;

class ForgetFit_Core_Plan_Repository_Test extends WP_UnitTestCase {
	private $plan_id = 0;

	public function tear_down() {
		global $wpdb;
		if ( $this->plan_id ) {
			$wpdb->delete( Schema::tables()['audit_log'], array( 'object_type' => 'plan', 'object_id' => $this->plan_id ), array( '%s', '%d' ) );
			$wpdb->delete( Schema::tables()['plans'], array( 'id' => $this->plan_id ), array( '%d' ) );
		}
		parent::tear_down();
	}

	public function test_plan_can_be_published_and_archived() {
		$this->plan_id = Plan_Repository::save(
			array(
				'name' => 'Test Plan',
				'description' => 'A test-only membership plan.',
				'eyebrow' => 'Test offer',
				'price' => '29.50',
				'currency' => 'USD',
				'billing_period' => 'month',
				'benefits' => "Benefit one\nBenefit two",
				'is_active' => true,
			)
		);

		$this->assertIsInt( $this->plan_id );
		$this->assertSame( 'test-plan', Plan_Repository::find( $this->plan_id )->slug );
		$this->assertSame( 2950, (int) Plan_Repository::find( $this->plan_id )->price_minor );
		$this->assertTrue( Plan_Repository::set_active( $this->plan_id, false ) );
		$this->assertSame( 0, (int) Plan_Repository::find( $this->plan_id )->is_active );
	}
}
