<?php
/**
 * Starter operational records.
 *
 * @package ForgetFitCore
 */

namespace ForgetFit\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Seeder {
	/**
	 * Create the public site's three starter plans without duplicating data.
	 *
	 * @return int Number of inserted plans.
	 */
	public static function seed_plans() {
		global $wpdb;

		$table = Schema::tables()['plans'];
		$now   = current_time( 'mysql', true );
		$plans = array(
			array(
				'name'           => 'Club',
				'slug'           => 'club',
				'eyebrow'        => 'Train your way',
				'description'    => 'Everything you need for a flexible independent training routine.',
				'price_minor'    => 4500,
				'benefits'       => array( 'Full gym access', 'Fitness orientation', 'Program library' ),
				'is_featured'    => 0,
				'sort_order'     => 10,
			),
			array(
				'name'           => 'Together',
				'slug'           => 'together',
				'eyebrow'        => 'Most popular',
				'description'    => 'Full access plus the motivation and structure of unlimited group training.',
				'price_minor'    => 7900,
				'benefits'       => array( 'Everything in Club', 'Unlimited group sessions', 'Quarterly coach check-in' ),
				'is_featured'    => 1,
				'sort_order'     => 20,
			),
			array(
				'name'           => 'Guided',
				'slug'           => 'guided',
				'eyebrow'        => 'Focused progress',
				'description'    => 'A closer coaching relationship and a plan that evolves with your goals.',
				'price_minor'    => 14900,
				'benefits'       => array( 'Everything in Together', 'Two personal sessions monthly', 'Ongoing plan updates' ),
				'is_featured'    => 0,
				'sort_order'     => 30,
			),
		);
		$inserted = 0;

		foreach ( $plans as $plan ) {
			$exists = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$table} WHERE slug = %s LIMIT 1", $plan['slug'] ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			if ( $exists ) {
				$wpdb->query( $wpdb->prepare( "UPDATE {$table} SET eyebrow = %s WHERE id = %d AND eyebrow = ''", $plan['eyebrow'], $exists ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				continue;
			}

			$result = $wpdb->insert(
				$table,
				array(
					'name'           => $plan['name'],
					'slug'           => $plan['slug'],
					'eyebrow'        => $plan['eyebrow'],
					'description'    => $plan['description'],
					'price_minor'    => $plan['price_minor'],
					'currency'       => 'USD',
					'billing_period' => 'month',
					'benefits'       => wp_json_encode( $plan['benefits'] ),
					'is_featured'    => $plan['is_featured'],
					'is_active'      => 1,
					'sort_order'     => $plan['sort_order'],
					'created_at'     => $now,
					'updated_at'     => $now,
				),
				array( '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%d', '%d', '%d', '%s', '%s' )
			);

			if ( false !== $result ) {
				++$inserted;
			}
		}

		return $inserted;
	}
}
