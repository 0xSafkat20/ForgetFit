<?php
/**
 * Main plugin coordinator.
 *
 * @package ForgetFitCore
 */

namespace ForgetFit\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Plugin {
	/** @var Plugin|null */
	private static $instance = null;

	/** @var bool */
	private $booted = false;

	/**
	 * Return the singleton plugin instance.
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register runtime hooks and run safe version checks.
	 */
	public function boot() {
		if ( $this->booted ) {
			return;
		}
		$this->booted = true;

		load_plugin_textdomain( 'forgetfit-core', false, dirname( plugin_basename( FORGETFIT_CORE_FILE ) ) . '/languages' );
		Schema::maybe_upgrade();
		Roles::maybe_upgrade();
		if ( FORGETFIT_CORE_VERSION !== get_option( 'forgetfit_core_version' ) ) {
			update_option( 'forgetfit_core_version', FORGETFIT_CORE_VERSION, false );
		}

		if ( is_admin() ) {
			Admin_Foundation::register();
			Admin_Leads::register();
			Admin_Plans::register();
		}

		add_filter( 'plugin_action_links_' . plugin_basename( FORGETFIT_CORE_FILE ), array( $this, 'action_links' ) );
	}

	/**
	 * Add a direct dashboard link on the Plugins screen.
	 *
	 * @param array $links Existing action links.
	 * @return array
	 */
	public function action_links( $links ) {
		if ( current_user_can( 'ff_view_reports' ) ) {
			array_unshift( $links, '<a href="' . esc_url( admin_url( 'admin.php?page=forgetfit' ) ) . '">' . esc_html__( 'Dashboard', 'forgetfit-core' ) . '</a>' );
		}
		return $links;
	}
}

