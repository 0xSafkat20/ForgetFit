<?php
/**
 * Plugin activation tasks.
 *
 * @package ForgetFitCore
 */

namespace ForgetFit\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Activator {
	/**
	 * Install the data model, permissions, and starter records.
	 */
	public static function activate() {
		Schema::install();
		Roles::install();
		Seeder::seed_plans();

		update_option( 'forgetfit_core_version', FORGETFIT_CORE_VERSION, false );
		update_option( 'forgetfit_core_activated_at', current_time( 'mysql', true ), false );
	}
}
