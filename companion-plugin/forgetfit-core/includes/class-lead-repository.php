<?php
/**
 * Persistence and business rules for free-pass leads.
 *
 * @package ForgetFitCore
 */

namespace ForgetFit\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Lead_Repository {
	/**
	 * Allowed pipeline statuses.
	 *
	 * @return array<string,string>
	 */
	public static function statuses() {
		return array(
			'new'          => __( 'New', 'forgetfit-core' ),
			'contacted'    => __( 'Contacted', 'forgetfit-core' ),
			'visit_booked' => __( 'Visit booked', 'forgetfit-core' ),
			'won'          => __( 'Won', 'forgetfit-core' ),
			'lost'         => __( 'Lost', 'forgetfit-core' ),
		);
	}

	/**
	 * Persist a validated lead idempotently.
	 *
	 * @param array $data Lead data.
	 * @return array|\WP_Error Array containing id and created flag.
	 */
	public static function create( $data ) {
		global $wpdb;

		$table         = Schema::tables()['leads'];
		$request_token = isset( $data['request_token'] ) ? sanitize_text_field( $data['request_token'] ) : '';
		$name          = isset( $data['name'] ) ? sanitize_text_field( $data['name'] ) : '';
		$email         = isset( $data['email'] ) ? sanitize_email( $data['email'] ) : '';

		if ( ! preg_match( '/^[a-f0-9-]{36}$/i', $request_token ) ) {
			return new \WP_Error( 'invalid_request_token', __( 'The request identifier is invalid.', 'forgetfit-core' ) );
		}
		if ( ! $name || ! is_email( $email ) ) {
			return new \WP_Error( 'invalid_lead', __( 'A valid name and email address are required.', 'forgetfit-core' ) );
		}

		$existing = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$table} WHERE request_token = %s LIMIT 1", $request_token ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		if ( $existing ) {
			return array( 'id' => (int) $existing, 'created' => false );
		}

		$now    = current_time( 'mysql', true );
		$result = $wpdb->insert(
			$table,
			array(
				'request_token'   => $request_token,
				'name'            => $name,
				'email'           => $email,
				'phone'           => isset( $data['phone'] ) ? sanitize_text_field( $data['phone'] ) : '',
				'interest'        => isset( $data['interest'] ) ? sanitize_key( $data['interest'] ) : '',
				'preferred_visit' => isset( $data['preferred_visit'] ) ? sanitize_text_field( $data['preferred_visit'] ) : null,
				'preferred_time'  => isset( $data['preferred_time'] ) ? sanitize_key( $data['preferred_time'] ) : '',
				'fitness_goal'    => isset( $data['fitness_goal'] ) ? sanitize_key( $data['fitness_goal'] ) : '',
				'experience_level'=> isset( $data['experience_level'] ) ? sanitize_key( $data['experience_level'] ) : '',
				'status'          => 'new',
				'source'          => isset( $data['source'] ) ? sanitize_key( $data['source'] ) : 'website',
				'privacy_version' => isset( $data['privacy_version'] ) ? sanitize_text_field( $data['privacy_version'] ) : '',
				'consent_at'      => ! empty( $data['consent_at'] ) ? sanitize_text_field( $data['consent_at'] ) : $now,
				'assigned_to'     => null,
				'created_at'      => $now,
				'updated_at'      => $now,
			),
			array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s' )
		);

		if ( false === $result ) {
			if ( false !== stripos( (string) $wpdb->last_error, 'duplicate' ) ) {
				$existing = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$table} WHERE request_token = %s LIMIT 1", $request_token ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				if ( $existing ) {
					return array( 'id' => (int) $existing, 'created' => false );
				}
			}
			return new \WP_Error( 'lead_not_saved', __( 'The request could not be saved.', 'forgetfit-core' ) );
		}

		$lead_id = (int) $wpdb->insert_id;
		Audit_Log::record( 'lead_created', 'lead', $lead_id, array( 'source' => isset( $data['source'] ) ? sanitize_key( $data['source'] ) : 'website' ) );
		return array( 'id' => $lead_id, 'created' => true );
	}

	/**
	 * Find one lead.
	 *
	 * @param int $lead_id Lead ID.
	 * @return object|null
	 */
	public static function find( $lead_id ) {
		global $wpdb;
		$table = Schema::tables()['leads'];
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d", absint( $lead_id ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	}

	/**
	 * Return distinct non-empty values for a safe inbox filter.
	 *
	 * @param string $field Supported field name.
	 * @return string[]
	 */
	public static function distinct_values( $field ) {
		global $wpdb;
		if ( ! in_array( $field, array( 'source', 'fitness_goal' ), true ) ) {
			return array();
		}
		$table = Schema::tables()['leads'];
		return array_map( 'strval', $wpdb->get_col( "SELECT DISTINCT {$field} FROM {$table} WHERE {$field} != '' ORDER BY {$field} ASC" ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	}

	/**
	 * Query leads using admin-safe filters.
	 *
	 * @param array $args Query arguments.
	 * @return array{items:array,total:int,pages:int,page:int}
	 */
	public static function query( $args = array() ) {
		global $wpdb;

		$defaults = array( 'search' => '', 'status' => '', 'source' => '', 'goal' => '', 'visit_from' => '', 'visit_to' => '', 'page' => 1, 'per_page' => 20 );
		$args     = wp_parse_args( $args, $defaults );
		$table    = Schema::tables()['leads'];
		$where    = array( '1=1' );
		$values   = array();

		if ( $args['status'] && isset( self::statuses()[ $args['status'] ] ) ) {
			$where[]  = 'status = %s';
			$values[] = $args['status'];
		}
		if ( $args['source'] ) {
			$where[]  = 'source = %s';
			$values[] = sanitize_key( $args['source'] );
		}
		if ( $args['goal'] ) {
			$where[]  = 'fitness_goal = %s';
			$values[] = sanitize_key( $args['goal'] );
		}
		$visit_from = self::date_boundary_utc( $args['visit_from'], false );
		if ( $visit_from ) {
			$where[]  = 'preferred_visit >= %s';
			$values[] = $visit_from;
		}
		$visit_to = self::date_boundary_utc( $args['visit_to'], true );
		if ( $visit_to ) {
			$where[]  = 'preferred_visit <= %s';
			$values[] = $visit_to;
		}
		if ( $args['search'] ) {
			$like     = '%' . $wpdb->esc_like( sanitize_text_field( $args['search'] ) ) . '%';
			$where[]  = '(name LIKE %s OR email LIKE %s OR phone LIKE %s)';
			$values[] = $like;
			$values[] = $like;
			$values[] = $like;
		}

		$where_sql = implode( ' AND ', $where );
		$count_sql = "SELECT COUNT(*) FROM {$table} WHERE {$where_sql}";
		if ( $values ) {
			$count_sql = $wpdb->prepare( $count_sql, $values ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		}
		$total    = (int) $wpdb->get_var( $count_sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$per_page = max( 1, min( 5000, absint( $args['per_page'] ) ) );
		$page     = max( 1, absint( $args['page'] ) );
		$offset   = ( $page - 1 ) * $per_page;
		$list_sql = "SELECT * FROM {$table} WHERE {$where_sql} ORDER BY created_at DESC, id DESC LIMIT %d OFFSET %d";
		$list_values = array_merge( $values, array( $per_page, $offset ) );
		$items    = $wpdb->get_results( $wpdb->prepare( $list_sql, $list_values ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		return array(
			'items' => $items,
			'total' => $total,
			'pages' => max( 1, (int) ceil( $total / $per_page ) ),
			'page'  => $page,
		);
	}

	/**
	 * Update the pipeline status and assignee.
	 *
	 * @param int    $lead_id Lead ID.
	 * @param string $status New status.
	 * @param int    $assigned_to Assignee user ID or zero.
	 * @return bool|\WP_Error
	 */
	public static function update_workflow( $lead_id, $status, $assigned_to = 0 ) {
		global $wpdb;

		$lead = self::find( $lead_id );
		if ( ! $lead ) {
			return new \WP_Error( 'lead_not_found', __( 'Lead not found.', 'forgetfit-core' ) );
		}
		if ( ! isset( self::statuses()[ $status ] ) ) {
			return new \WP_Error( 'invalid_status', __( 'Choose a valid lead status.', 'forgetfit-core' ) );
		}
		$assigned_to = absint( $assigned_to );
		$assignee = $assigned_to ? get_user_by( 'id', $assigned_to ) : null;
		if ( $assigned_to && ( ! $assignee || ! user_can( $assignee, 'ff_manage_leads' ) ) ) {
			return new \WP_Error( 'invalid_assignee', __( 'Choose a staff member who can manage leads.', 'forgetfit-core' ) );
		}

		$result = $wpdb->update(
			Schema::tables()['leads'],
			array( 'status' => $status, 'assigned_to' => $assigned_to ?: null, 'updated_at' => current_time( 'mysql', true ) ),
			array( 'id' => absint( $lead_id ) ),
			array( '%s', '%d', '%s' ),
			array( '%d' )
		);
		if ( false === $result ) {
			return new \WP_Error( 'lead_not_updated', __( 'The lead could not be updated.', 'forgetfit-core' ) );
		}

		Audit_Log::record( 'lead_workflow_updated', 'lead', $lead_id, array( 'from' => $lead->status, 'to' => $status, 'assigned_to' => $assigned_to ), get_current_user_id() );
		return true;
	}

	/**
	 * Add an internal note.
	 *
	 * @param int    $lead_id Lead ID.
	 * @param string $note Note body.
	 * @return int|\WP_Error
	 */
	public static function add_note( $lead_id, $note ) {
		global $wpdb;
		$note = trim( sanitize_textarea_field( $note ) );
		if ( ! self::find( $lead_id ) || '' === $note ) {
			return new \WP_Error( 'invalid_note', __( 'Enter a note for an existing lead.', 'forgetfit-core' ) );
		}
		$result = $wpdb->insert(
			Schema::tables()['lead_notes'],
			array( 'lead_id' => absint( $lead_id ), 'author_id' => get_current_user_id(), 'note' => $note, 'created_at' => current_time( 'mysql', true ) ),
			array( '%d', '%d', '%s', '%s' )
		);
		if ( false === $result ) {
			return new \WP_Error( 'note_not_saved', __( 'The note could not be saved.', 'forgetfit-core' ) );
		}
		Audit_Log::record( 'lead_note_added', 'lead', $lead_id, array( 'note_id' => (int) $wpdb->insert_id ), get_current_user_id() );
		return (int) $wpdb->insert_id;
	}

	/**
	 * Return lead notes with author names.
	 *
	 * @param int $lead_id Lead ID.
	 * @return array
	 */
	public static function notes( $lead_id ) {
		global $wpdb;
		$notes = Schema::tables()['lead_notes'];
		$users = $wpdb->users;
		return $wpdb->get_results( $wpdb->prepare( "SELECT n.*, u.display_name FROM {$notes} n LEFT JOIN {$users} u ON u.ID = n.author_id WHERE n.lead_id = %d ORDER BY n.created_at DESC, n.id DESC", absint( $lead_id ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	}

	/** Convert a local date filter into a UTC database boundary. */
	private static function date_boundary_utc( $value, $end_of_day ) {
		$value = sanitize_text_field( (string) $value );
		$date  = \DateTimeImmutable::createFromFormat( '!Y-m-d', $value, wp_timezone() );
		$errors = \DateTimeImmutable::getLastErrors();
		if ( ! $date || ( is_array( $errors ) && ( $errors['warning_count'] || $errors['error_count'] ) ) || $date->format( 'Y-m-d' ) !== $value ) {
			return '';
		}
		if ( $end_of_day ) {
			$date = $date->setTime( 23, 59, 59 );
		}
		return $date->setTimezone( new \DateTimeZone( 'UTC' ) )->format( 'Y-m-d H:i:s' );
	}
}
