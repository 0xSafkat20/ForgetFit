<?php
/**
 * Membership plan persistence and public view models.
 *
 * @package ForgetFitCore
 */

namespace ForgetFit\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Plan_Repository {
	/**
	 * Supported billing periods.
	 *
	 * @return array<string,string>
	 */
	public static function billing_periods() {
		return array(
			'month' => __( 'per month', 'forgetfit-core' ),
			'year'  => __( 'per year', 'forgetfit-core' ),
			'once'  => __( 'one-time', 'forgetfit-core' ),
		);
	}

	/**
	 * Return all plans for administration.
	 *
	 * @return array
	 */
	public static function all() {
		global $wpdb;
		$table = Schema::tables()['plans'];
		return $wpdb->get_results( "SELECT * FROM {$table} ORDER BY sort_order ASC, id ASC" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	}

	/**
	 * Find a single plan.
	 *
	 * @param int $plan_id Plan ID.
	 * @return object|null
	 */
	public static function find( $plan_id ) {
		global $wpdb;
		$table = Schema::tables()['plans'];
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d", absint( $plan_id ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	}

	/**
	 * Active plans mapped for public templates.
	 *
	 * @return array<int,array<string,mixed>>
	 */
	public static function public_plans() {
		global $wpdb;
		$table = Schema::tables()['plans'];
		$rows  = $wpdb->get_results( "SELECT * FROM {$table} WHERE is_active = 1 ORDER BY sort_order ASC, id ASC" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$periods = self::billing_periods();
		$plans = array();

		foreach ( $rows as $row ) {
			$benefits = json_decode( $row->benefits, true );
			if ( ! is_array( $benefits ) ) {
				$benefits = array();
			}
			$currency_symbols = array( 'USD' => '$', 'EUR' => '€', 'GBP' => '£', 'BDT' => '৳' );
			$symbol = isset( $currency_symbols[ $row->currency ] ) ? $currency_symbols[ $row->currency ] : $row->currency . ' ';
			$amount = number_format_i18n( (int) $row->price_minor / 100, 0 === (int) $row->price_minor % 100 ? 0 : 2 );
			$plans[] = array(
				'id'          => (int) $row->id,
				'label'       => isset( $row->eyebrow ) && $row->eyebrow ? $row->eyebrow : ( $row->is_featured ? __( 'Most popular', 'forgetfit-core' ) : __( 'Train your way', 'forgetfit-core' ) ),
				'name'        => $row->name,
				'slug'        => $row->slug,
				'price'       => $symbol . $amount,
				'period'      => isset( $periods[ $row->billing_period ] ) ? $periods[ $row->billing_period ] : $row->billing_period,
				'description' => $row->description,
				'benefits'    => array_map( 'sanitize_text_field', $benefits ),
				'featured'    => (bool) $row->is_featured,
			);
		}

		return $plans;
	}

	/**
	 * Create or update a plan after validating admin input.
	 *
	 * @param array $data Plan input.
	 * @param int   $plan_id Existing plan ID.
	 * @return int|\WP_Error
	 */
	public static function save( $data, $plan_id = 0 ) {
		global $wpdb;

		$table       = Schema::tables()['plans'];
		$plan_id     = absint( $plan_id );
		$name        = isset( $data['name'] ) ? sanitize_text_field( $data['name'] ) : '';
		$slug_input  = isset( $data['slug'] ) ? sanitize_text_field( $data['slug'] ) : '';
		$slug        = $slug_input ? sanitize_title( $slug_input ) : sanitize_title( $name );
		$eyebrow     = isset( $data['eyebrow'] ) ? sanitize_text_field( $data['eyebrow'] ) : '';
		$description = isset( $data['description'] ) ? sanitize_textarea_field( $data['description'] ) : '';
		$currency    = isset( $data['currency'] ) ? strtoupper( sanitize_text_field( $data['currency'] ) ) : 'USD';
		$period      = isset( $data['billing_period'] ) ? sanitize_key( $data['billing_period'] ) : 'month';
		$price       = isset( $data['price'] ) ? str_replace( ',', '', sanitize_text_field( $data['price'] ) ) : '';
		$benefits    = isset( $data['benefits'] ) ? $data['benefits'] : array();

		if ( is_string( $benefits ) ) {
			$benefits = preg_split( '/\r\n|\r|\n/', $benefits );
		}
		$benefits = array_slice( array_values( array_filter( array_map( 'sanitize_text_field', (array) $benefits ) ) ), 0, 12 );

		if ( ! $name || ! $slug || ! $description || ! preg_match( '/^\d+(?:\.\d{1,2})?$/', $price ) || (float) $price > 1000000 ) {
			return new \WP_Error( 'invalid_plan', __( 'Enter a name, description, slug, and valid price with up to two decimal places.', 'forgetfit-core' ) );
		}
		if ( ! $benefits ) {
			return new \WP_Error( 'missing_benefits', __( 'Add at least one membership benefit.', 'forgetfit-core' ) );
		}
		if ( ! preg_match( '/^[A-Z]{3}$/', $currency ) || ! isset( self::billing_periods()[ $period ] ) ) {
			return new \WP_Error( 'invalid_plan_options', __( 'Choose a valid currency and billing period.', 'forgetfit-core' ) );
		}
		$duplicate = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$table} WHERE slug = %s AND id != %d LIMIT 1", $slug, $plan_id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		if ( $duplicate ) {
			return new \WP_Error( 'duplicate_slug', __( 'A plan already uses that slug.', 'forgetfit-core' ) );
		}

		$now = current_time( 'mysql', true );
		$record = array(
			'name'           => $name,
			'slug'           => $slug,
			'eyebrow'        => $eyebrow,
			'description'    => $description,
			'price_minor'    => (int) round( (float) $price * 100 ),
			'currency'       => $currency,
			'billing_period' => $period,
			'benefits'       => wp_json_encode( $benefits ),
			'is_featured'    => ! empty( $data['is_featured'] ) ? 1 : 0,
			'is_active'      => ! empty( $data['is_active'] ) ? 1 : 0,
			'sort_order'     => isset( $data['sort_order'] ) ? intval( $data['sort_order'] ) : 0,
			'updated_at'     => $now,
		);
		$formats = array( '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%d', '%d', '%d', '%s' );

		if ( $plan_id ) {
			if ( ! self::find( $plan_id ) ) {
				return new \WP_Error( 'plan_not_found', __( 'Plan not found.', 'forgetfit-core' ) );
			}
			$result = $wpdb->update( $table, $record, array( 'id' => $plan_id ), $formats, array( '%d' ) );
			$action = 'plan_updated';
		} else {
			$record['created_at'] = $now;
			$formats[] = '%s';
			$result = $wpdb->insert( $table, $record, $formats );
			$plan_id = (int) $wpdb->insert_id;
			$action = 'plan_created';
		}

		if ( false === $result ) {
			return new \WP_Error( 'plan_not_saved', __( 'The plan could not be saved.', 'forgetfit-core' ) );
		}
		Audit_Log::record( $action, 'plan', $plan_id, array( 'name' => $name, 'is_active' => $record['is_active'] ), get_current_user_id() );
		return $plan_id;
	}

	/**
	 * Activate or archive a plan without deleting membership history.
	 *
	 * @param int  $plan_id Plan ID.
	 * @param bool $active Desired state.
	 * @return bool|\WP_Error
	 */
	public static function set_active( $plan_id, $active ) {
		global $wpdb;
		if ( ! self::find( $plan_id ) ) {
			return new \WP_Error( 'plan_not_found', __( 'Plan not found.', 'forgetfit-core' ) );
		}
		$result = $wpdb->update(
			Schema::tables()['plans'],
			array( 'is_active' => $active ? 1 : 0, 'updated_at' => current_time( 'mysql', true ) ),
			array( 'id' => absint( $plan_id ) ),
			array( '%d', '%s' ),
			array( '%d' )
		);
		if ( false === $result ) {
			return new \WP_Error( 'plan_not_updated', __( 'The plan could not be updated.', 'forgetfit-core' ) );
		}
		Audit_Log::record( $active ? 'plan_activated' : 'plan_archived', 'plan', $plan_id, array(), get_current_user_id() );
		return true;
	}
}
