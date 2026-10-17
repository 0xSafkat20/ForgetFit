<?php
/**
 * Role integration tests.
 */

use ForgetFit\Core\Roles;

class ForgetFit_Core_Roles_Test extends WP_UnitTestCase {
	public function test_roles_receive_scoped_capabilities() {
		Roles::install();

		$member  = get_role( 'forgetfit_member' );
		$trainer = get_role( 'forgetfit_trainer' );
		$manager = get_role( 'forgetfit_manager' );

		$this->assertTrue( $member->has_cap( 'ff_manage_own_bookings' ) );
		$this->assertFalse( $member->has_cap( 'ff_manage_members' ) );
		$this->assertTrue( $trainer->has_cap( 'ff_record_member_progress' ) );
		$this->assertFalse( $trainer->has_cap( 'ff_manage_plans' ) );
		$this->assertTrue( $manager->has_cap( 'ff_manage_plans' ) );
		$this->assertTrue( get_role( 'administrator' )->has_cap( 'ff_view_reports' ) );
	}
}
