<?php
/**
 * ForgetFit Core uninstall routine.
 *
 * Operational data is retained by default. Define FORGETFIT_CORE_REMOVE_DATA as
 * true before uninstalling only when permanent deletion is explicitly intended.
 *
 * @package ForgetFitCore
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( ! defined( 'FORGETFIT_CORE_REMOVE_DATA' ) || true !== FORGETFIT_CORE_REMOVE_DATA ) {
	return;
}

global $wpdb;

$table_suffixes = array(
	'ff_lead_notes',
	'ff_bookings',
	'ff_checkins',
	'ff_progress',
	'ff_audit_log',
	'ff_memberships',
	'ff_sessions',
	'ff_leads',
	'ff_plans',
);

foreach ( $table_suffixes as $suffix ) {
	$table = $wpdb->prefix . $suffix;
	$wpdb->query( "DROP TABLE IF EXISTS `{$table}`" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.DirectDatabaseQuery.SchemaChange
}

$capabilities = array(
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

$administrator = get_role( 'administrator' );
if ( $administrator ) {
	foreach ( $capabilities as $capability ) {
		$administrator->remove_cap( $capability );
	}
}

remove_role( 'forgetfit_member' );
remove_role( 'forgetfit_trainer' );
remove_role( 'forgetfit_manager' );

delete_option( 'forgetfit_core_version' );
delete_option( 'forgetfit_core_activated_at' );
delete_option( 'forgetfit_core_schema_version' );
delete_option( 'forgetfit_core_roles_version' );
