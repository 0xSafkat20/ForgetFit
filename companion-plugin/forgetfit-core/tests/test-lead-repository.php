<?php
/**
 * Lead repository integration tests.
 */

use ForgetFit\Core\Lead_Repository;
use ForgetFit\Core\Schema;

class ForgetFit_Core_Lead_Repository_Test extends WP_UnitTestCase {
	private $token = '11111111-2222-4333-8444-555555555555';

	public function tear_down() {
		global $wpdb;
		$lead_id = $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM ' . Schema::tables()['leads'] . ' WHERE request_token = %s', $this->token ) );
		if ( $lead_id ) {
			$wpdb->delete( Schema::tables()['lead_notes'], array( 'lead_id' => $lead_id ), array( '%d' ) );
			$wpdb->delete( Schema::tables()['audit_log'], array( 'object_type' => 'lead', 'object_id' => $lead_id ), array( '%s', '%d' ) );
			$wpdb->delete( Schema::tables()['leads'], array( 'id' => $lead_id ), array( '%d' ) );
		}
		parent::tear_down();
	}

	public function test_capture_is_idempotent_and_workflow_is_editable() {
		$user_id = self::factory()->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );
		$data = array(
			'request_token' => $this->token,
			'name' => 'Test Visitor',
			'email' => 'visitor@example.test',
			'preferred_visit' => '2026-11-01 00:00:00',
			'preferred_time' => 'morning',
			'fitness_goal' => 'strength',
			'experience_level' => 'new',
			'privacy_version' => 'test-1',
		);

		$first  = Lead_Repository::create( $data );
		$second = Lead_Repository::create( $data );

		$this->assertTrue( $first['created'] );
		$this->assertFalse( $second['created'] );
		$this->assertSame( $first['id'], $second['id'] );
		$member_id = self::factory()->user->create( array( 'role' => 'forgetfit_member' ) );
		$this->assertWPError( Lead_Repository::update_workflow( $first['id'], 'contacted', $member_id ) );
		$this->assertTrue( Lead_Repository::update_workflow( $first['id'], 'contacted', $user_id ) );
		$this->assertSame( 'contacted', Lead_Repository::find( $first['id'] )->status );
		$this->assertIsInt( Lead_Repository::add_note( $first['id'], 'Followed up by phone.' ) );
	}
}
