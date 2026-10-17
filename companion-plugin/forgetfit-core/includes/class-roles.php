<?php
/**
 * ForgetFit roles and capabilities.
 *
 * @package ForgetFitCore
 */

namespace ForgetFit\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Roles {
	const VERSION = '1.0.0';
	const OPTION  = 'forgetfit_core_roles_version';

	/**
	 * All custom capabilities managed by this plugin.
	 *
	 * @return string[]
	 */
	public static function capabilities() {
		return array(
			'ff_access_member_portal',
			'ff_manage_own_bookings',
			'ff_manage_own_profile',
			'ff_view_own_progress',
			'ff_access_trainer_portal',
			'ff_view_assigned_sessions',
			'ff_manage_session_attendance',
			'ff_view_assigned_members',
			'ff_record_member_progress',
			'ff_manage_leads',
			'ff_manage_members',
			'ff_manage_plans',
			'ff_manage_schedule',
			'ff_manage_attendance',
			'ff_view_reports',
			'ff_manage_settings',
			'ff_view_audit_log',
		);
	}

	/**
	 * Add or update the application roles.
	 */
	public static function install() {
		$member_caps = array(
			'read'                    => true,
			'ff_access_member_portal' => true,
			'ff_manage_own_bookings'  => true,
			'ff_manage_own_profile'   => true,
			'ff_view_own_progress'    => true,
		);

		$trainer_caps = array_merge(
			$member_caps,
			array(
				'upload_files'                 => true,
				'ff_access_trainer_portal'     => true,
				'ff_view_assigned_sessions'    => true,
				'ff_manage_session_attendance' => true,
				'ff_view_assigned_members'     => true,
				'ff_record_member_progress'    => true,
			)
		);

		$manager_caps = array_fill_keys( self::capabilities(), true );
		$manager_caps['read'] = true;
		$manager_caps['upload_files'] = true;

		self::sync_role( 'forgetfit_member', __( 'ForgetFit Member', 'forgetfit-core' ), $member_caps );
		self::sync_role( 'forgetfit_trainer', __( 'ForgetFit Trainer', 'forgetfit-core' ), $trainer_caps );
		self::sync_role( 'forgetfit_manager', __( 'ForgetFit Manager', 'forgetfit-core' ), $manager_caps );

		$administrator = get_role( 'administrator' );
		if ( $administrator ) {
			foreach ( self::capabilities() as $capability ) {
				$administrator->add_cap( $capability );
			}
		}

		update_option( self::OPTION, self::VERSION, false );
	}

	/**
	 * Refresh capabilities after a plugin update.
	 */
	public static function maybe_upgrade() {
		if ( self::VERSION !== get_option( self::OPTION ) ) {
			self::install();
		}
	}

	/**
	 * Synchronize an existing or new role without removing unrelated caps.
	 *
	 * @param string $slug Role slug.
	 * @param string $name Display name.
	 * @param array  $capabilities Capabilities to add.
	 */
	private static function sync_role( $slug, $name, $capabilities ) {
		$role = get_role( $slug );
		if ( ! $role ) {
			add_role( $slug, $name, $capabilities );
			$role = get_role( $slug );
		}

		if ( $role ) {
			foreach ( $capabilities as $capability => $grant ) {
				$role->add_cap( $capability, $grant );
			}
		}
	}
}
