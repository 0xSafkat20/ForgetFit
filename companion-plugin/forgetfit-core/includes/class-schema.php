<?php
/**
 * Versioned database schema for gym operations.
 *
 * @package ForgetFitCore
 */

namespace ForgetFit\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Schema {
	const OPTION = 'forgetfit_core_schema_version';

	/**
	 * Return all plugin tables with the active WordPress prefix.
	 *
	 * @return array<string,string>
	 */
	public static function tables() {
		global $wpdb;

		return array(
			'leads'        => $wpdb->prefix . 'ff_leads',
			'lead_notes'   => $wpdb->prefix . 'ff_lead_notes',
			'plans'        => $wpdb->prefix . 'ff_plans',
			'memberships'  => $wpdb->prefix . 'ff_memberships',
			'sessions'     => $wpdb->prefix . 'ff_sessions',
			'bookings'     => $wpdb->prefix . 'ff_bookings',
			'checkins'     => $wpdb->prefix . 'ff_checkins',
			'progress'     => $wpdb->prefix . 'ff_progress',
			'audit_log'    => $wpdb->prefix . 'ff_audit_log',
		);
	}

	/**
	 * Install or upgrade all custom tables using dbDelta.
	 */
	public static function install() {
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$tables            = self::tables();
		$charset_collate   = $wpdb->get_charset_collate();
		$sql               = array();

		$sql[] = "CREATE TABLE {$tables['leads']} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			request_token char(36) NULL,
			name varchar(190) NOT NULL,
			email varchar(190) NOT NULL,
			phone varchar(40) NOT NULL DEFAULT '',
			interest varchar(100) NOT NULL DEFAULT '',
			preferred_visit datetime NULL,
			preferred_time varchar(30) NOT NULL DEFAULT '',
			fitness_goal varchar(190) NOT NULL DEFAULT '',
			experience_level varchar(50) NOT NULL DEFAULT '',
			status varchar(30) NOT NULL DEFAULT 'new',
			source varchar(80) NOT NULL DEFAULT 'website',
			consent_at datetime NULL,
			privacy_version varchar(40) NOT NULL DEFAULT '',
			assigned_to bigint(20) unsigned NULL,
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY request_token (request_token),
			KEY status (status),
			KEY email (email),
			KEY assigned_to (assigned_to),
			KEY preferred_visit (preferred_visit),
			KEY source (source),
			KEY created_at (created_at)
		) $charset_collate;";

		$sql[] = "CREATE TABLE {$tables['lead_notes']} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			lead_id bigint(20) unsigned NOT NULL,
			author_id bigint(20) unsigned NOT NULL,
			note text NOT NULL,
			created_at datetime NOT NULL,
			PRIMARY KEY  (id),
			KEY lead_id (lead_id),
			KEY author_id (author_id)
		) $charset_collate;";

		$sql[] = "CREATE TABLE {$tables['plans']} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			name varchar(190) NOT NULL,
			slug varchar(190) NOT NULL,
			eyebrow varchar(80) NOT NULL DEFAULT '',
			description text NOT NULL,
			price_minor bigint(20) unsigned NOT NULL DEFAULT 0,
			currency char(3) NOT NULL DEFAULT 'USD',
			billing_period varchar(30) NOT NULL DEFAULT 'month',
			benefits longtext NOT NULL,
			is_featured tinyint(1) NOT NULL DEFAULT 0,
			is_active tinyint(1) NOT NULL DEFAULT 1,
			sort_order int(11) NOT NULL DEFAULT 0,
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY slug (slug),
			KEY is_active (is_active),
			KEY sort_order (sort_order)
		) $charset_collate;";

		$sql[] = "CREATE TABLE {$tables['memberships']} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			user_id bigint(20) unsigned NOT NULL,
			plan_id bigint(20) unsigned NOT NULL,
			status varchar(30) NOT NULL DEFAULT 'pending',
			starts_at datetime NULL,
			expires_at datetime NULL,
			cancelled_at datetime NULL,
			cancellation_reason text NOT NULL,
			external_reference varchar(190) NOT NULL DEFAULT '',
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY  (id),
			KEY user_id (user_id),
			KEY plan_id (plan_id),
			KEY status (status),
			KEY expires_at (expires_at)
		) $charset_collate;";

		$sql[] = "CREATE TABLE {$tables['sessions']} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			program_id bigint(20) unsigned NULL,
			trainer_id bigint(20) unsigned NULL,
			title varchar(190) NOT NULL,
			description text NOT NULL,
			location varchar(190) NOT NULL DEFAULT '',
			starts_at datetime NOT NULL,
			ends_at datetime NOT NULL,
			capacity int(11) unsigned NOT NULL DEFAULT 1,
			status varchar(30) NOT NULL DEFAULT 'scheduled',
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY  (id),
			KEY trainer_id (trainer_id),
			KEY program_id (program_id),
			KEY starts_at (starts_at),
			KEY status (status)
		) $charset_collate;";

		$sql[] = "CREATE TABLE {$tables['bookings']} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			session_id bigint(20) unsigned NOT NULL,
			user_id bigint(20) unsigned NOT NULL,
			status varchar(30) NOT NULL DEFAULT 'confirmed',
			booked_at datetime NOT NULL,
			cancelled_at datetime NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY session_user (session_id,user_id),
			KEY user_id (user_id),
			KEY status (status)
		) $charset_collate;";

		$sql[] = "CREATE TABLE {$tables['checkins']} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			user_id bigint(20) unsigned NOT NULL,
			session_id bigint(20) unsigned NULL,
			checked_in_at datetime NOT NULL,
			method varchar(30) NOT NULL DEFAULT 'manual',
			recorded_by bigint(20) unsigned NULL,
			PRIMARY KEY  (id),
			KEY user_id (user_id),
			KEY session_id (session_id),
			KEY checked_in_at (checked_in_at)
		) $charset_collate;";

		$sql[] = "CREATE TABLE {$tables['progress']} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			user_id bigint(20) unsigned NOT NULL,
			trainer_id bigint(20) unsigned NULL,
			metric_key varchar(80) NOT NULL,
			metric_value decimal(12,3) NULL,
			metric_unit varchar(30) NOT NULL DEFAULT '',
			note text NOT NULL,
			recorded_at datetime NOT NULL,
			PRIMARY KEY  (id),
			KEY user_id (user_id),
			KEY trainer_id (trainer_id),
			KEY metric_key (metric_key),
			KEY recorded_at (recorded_at)
		) $charset_collate;";

		$sql[] = "CREATE TABLE {$tables['audit_log']} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			actor_id bigint(20) unsigned NULL,
			action varchar(100) NOT NULL,
			object_type varchar(80) NOT NULL DEFAULT '',
			object_id bigint(20) unsigned NULL,
			context longtext NOT NULL,
			ip_hash char(64) NOT NULL DEFAULT '',
			created_at datetime NOT NULL,
			PRIMARY KEY  (id),
			KEY actor_id (actor_id),
			KEY action (action),
			KEY object_lookup (object_type,object_id),
			KEY created_at (created_at)
		) $charset_collate;";

		foreach ( $sql as $statement ) {
			dbDelta( $statement );
		}
		self::ensure_upgrade_columns( $tables );

		if ( self::is_ready( $tables ) ) {
			update_option( self::OPTION, FORGETFIT_CORE_SCHEMA_VERSION, false );
		}
	}

	/**
	 * Upgrade the schema only when its version changes.
	 */
	public static function maybe_upgrade() {
		if ( FORGETFIT_CORE_SCHEMA_VERSION !== get_option( self::OPTION ) ) {
			self::install();
			Seeder::seed_plans();
		}
	}

	/**
	 * Ensure additive upgrade columns exist when dbDelta skips an alteration.
	 *
	 * @param array<string,string> $tables Plugin tables.
	 */
	private static function ensure_upgrade_columns( $tables ) {
		global $wpdb;

		$required = array(
			$tables['leads'] => array(
				'request_token'   => 'request_token char(36) NULL AFTER id',
				'preferred_time'  => "preferred_time varchar(30) NOT NULL DEFAULT '' AFTER preferred_visit",
				'privacy_version' => "privacy_version varchar(40) NOT NULL DEFAULT '' AFTER consent_at",
			),
			$tables['plans'] => array(
				'eyebrow' => "eyebrow varchar(80) NOT NULL DEFAULT '' AFTER slug",
			),
		);

		foreach ( $required as $table => $columns ) {
			$existing = $wpdb->get_col( "SHOW COLUMNS FROM {$table}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			foreach ( $columns as $column => $definition ) {
				if ( ! in_array( $column, $existing, true ) ) {
					$wpdb->query( "ALTER TABLE {$table} ADD COLUMN {$definition}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.DirectDatabaseQuery.SchemaChange
				}
			}
		}
	}

	/** Confirm required tables and upgrade columns exist before advancing version. */
	private static function is_ready( $tables ) {
		global $wpdb;

		foreach ( $tables as $table ) {
			$found = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table ) ) );
			if ( $table !== $found ) {
				return false;
			}
		}

		$required = array(
			$tables['leads'] => array( 'request_token', 'preferred_time', 'privacy_version' ),
			$tables['plans'] => array( 'eyebrow' ),
		);
		foreach ( $required as $table => $columns ) {
			$existing = $wpdb->get_col( "SHOW COLUMNS FROM {$table}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			if ( array_diff( $columns, $existing ) ) {
				return false;
			}
		}

		return true;
	}
}
