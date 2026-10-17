<?php
/**
 * Plugin Name: ForgetFit Core
 * Plugin URI:  https://github.com/0xSafkat20/ForgetFit
 * Description: Operational data, permissions, dashboards, and workflows for the ForgetFit gym platform.
 * Version:     0.2.0
 * Author:      Safkat Khan
 * License:     GPL-2.0-or-later
 * Text Domain: forgetfit-core
 * Requires at least: 6.6
 * Requires PHP: 7.4
 *
 * @package ForgetFitCore
 */

namespace ForgetFit\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FORGETFIT_CORE_VERSION', '0.2.0' );
define( 'FORGETFIT_CORE_SCHEMA_VERSION', '1.2.0' );
define( 'FORGETFIT_CORE_FILE', __FILE__ );
define( 'FORGETFIT_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'FORGETFIT_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once FORGETFIT_CORE_PATH . 'includes/class-schema.php';
require_once FORGETFIT_CORE_PATH . 'includes/class-roles.php';
require_once FORGETFIT_CORE_PATH . 'includes/class-seeder.php';
require_once FORGETFIT_CORE_PATH . 'includes/class-audit-log.php';
require_once FORGETFIT_CORE_PATH . 'includes/class-lead-repository.php';
require_once FORGETFIT_CORE_PATH . 'includes/class-plan-repository.php';
require_once FORGETFIT_CORE_PATH . 'includes/class-activator.php';
require_once FORGETFIT_CORE_PATH . 'includes/class-admin-foundation.php';
require_once FORGETFIT_CORE_PATH . 'includes/class-admin-leads.php';
require_once FORGETFIT_CORE_PATH . 'includes/class-admin-plans.php';
require_once FORGETFIT_CORE_PATH . 'includes/class-plugin.php';

register_activation_hook( __FILE__, array( Activator::class, 'activate' ) );

/**
 * Boot the plugin after WordPress has loaded active plugins.
 */
function forgetfit_core_boot() {
	Plugin::instance()->boot();
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\\forgetfit_core_boot' );

/**
 * Stable theme-facing API for capturing a validated lead.
 *
 * @param array $data Validated lead fields.
 * @return array|\WP_Error
 */
function capture_lead( $data ) {
	return Lead_Repository::create( $data );
}

/**
 * Stable theme-facing API for active membership plans.
 *
 * @return array<int,array<string,mixed>>
 */
function public_plans() {
	return Plan_Repository::public_plans();
}

