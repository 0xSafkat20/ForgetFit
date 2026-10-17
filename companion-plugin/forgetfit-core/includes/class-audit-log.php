<?php
/**
 * Append-only audit events for operational changes.
 *
 * @package ForgetFitCore
 */

namespace ForgetFit\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Audit_Log {
	/**
	 * Record a structured event without exposing a raw visitor IP address.
	 *
	 * @param string $action Action identifier.
	 * @param string $object_type Domain object type.
	 * @param int    $object_id Domain object ID.
	 * @param array  $context Additional non-sensitive context.
	 * @param int    $actor_id Optional actor user ID.
	 * @return bool
	 */
	public static function record( $action, $object_type, $object_id, $context = array(), $actor_id = 0 ) {
		global $wpdb;

		$ip      = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		$ip_hash = $ip ? hash_hmac( 'sha256', $ip, wp_salt( 'auth' ) ) : '';
		$result  = $wpdb->insert(
			Schema::tables()['audit_log'],
			array(
				'actor_id'    => $actor_id ? absint( $actor_id ) : null,
				'action'      => sanitize_key( $action ),
				'object_type' => sanitize_key( $object_type ),
				'object_id'   => absint( $object_id ) ?: null,
				'context'     => wp_json_encode( $context ),
				'ip_hash'     => $ip_hash,
				'created_at'  => current_time( 'mysql', true ),
			),
			array( '%d', '%s', '%s', '%d', '%s', '%s', '%s' )
		);

		return false !== $result;
	}
}
