<?php
/**
 * Initial WordPress admin experience for ForgetFit operations.
 *
 * @package ForgetFitCore
 */

namespace ForgetFit\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Admin_Foundation {
	/**
	 * Register admin hooks.
	 */
	public static function register() {
		add_action( 'admin_menu', array( __CLASS__, 'add_menu' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
	}

	/**
	 * Add the branded operations menu.
	 */
	public static function add_menu() {
		add_menu_page(
			__( 'ForgetFit Operations', 'forgetfit-core' ),
			__( 'ForgetFit', 'forgetfit-core' ),
			'ff_view_reports',
			'forgetfit',
			array( __CLASS__, 'render' ),
			'dashicons-heart',
			26
		);
	}

	/**
	 * Load assets only on the plugin dashboard.
	 *
	 * @param string $hook Current admin hook.
	 */
	public static function enqueue_assets( $hook ) {
		$allowed_hooks = array( 'toplevel_page_forgetfit', 'forgetfit_page_forgetfit-leads', 'forgetfit_page_forgetfit-plans' );
		if ( ! in_array( $hook, $allowed_hooks, true ) ) {
			return;
		}

		wp_enqueue_style(
			'forgetfit-core-admin',
			FORGETFIT_CORE_URL . 'assets/css/admin.css',
			array(),
			FORGETFIT_CORE_VERSION
		);
	}

	/**
	 * Render the operations dashboard.
	 */
	public static function render() {
		if ( ! current_user_can( 'ff_view_reports' ) ) {
			wp_die( esc_html__( 'You do not have permission to view this page.', 'forgetfit-core' ) );
		}

		$cards  = self::summary_cards();
		$tables = self::table_statuses();
		$roles  = array(
			'forgetfit_member'  => __( 'Member', 'forgetfit-core' ),
			'forgetfit_trainer' => __( 'Trainer', 'forgetfit-core' ),
			'forgetfit_manager' => __( 'Manager', 'forgetfit-core' ),
		);
		?>
		<div class="wrap ff-core-admin">
			<header class="ff-core-hero">
				<div>
					<p class="ff-core-eyebrow"><?php esc_html_e( 'ForgetFit operations', 'forgetfit-core' ); ?></p>
					<h1><?php esc_html_e( 'Operations overview', 'forgetfit-core' ); ?></h1>
					<p><?php esc_html_e( 'Manage the visitor pipeline and membership offers from one reliable operational workspace.', 'forgetfit-core' ); ?></p>
					<div class="ff-core-actions"><a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=forgetfit-leads' ) ); ?>"><?php esc_html_e( 'Open lead inbox', 'forgetfit-core' ); ?></a><a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=forgetfit-plans' ) ); ?>"><?php esc_html_e( 'Manage plans', 'forgetfit-core' ); ?></a></div>
				</div>
				<span class="ff-core-badge"><?php esc_html_e( 'Lead CRM live', 'forgetfit-core' ); ?></span>
			</header>

			<section class="ff-core-cards" aria-label="<?php esc_attr_e( 'System summary', 'forgetfit-core' ); ?>">
				<?php foreach ( $cards as $card ) : ?>
					<article class="ff-core-card">
						<span><?php echo esc_html( $card['label'] ); ?></span>
						<strong><?php echo esc_html( number_format_i18n( $card['value'] ) ); ?></strong>
						<small><?php echo esc_html( $card['description'] ); ?></small>
					</article>
				<?php endforeach; ?>
			</section>

			<div class="ff-core-grid">
				<section class="ff-core-panel">
					<div class="ff-core-panel__header">
						<h2><?php esc_html_e( 'Database health', 'forgetfit-core' ); ?></h2>
						<span><?php echo esc_html( get_option( Schema::OPTION, 'Not installed' ) ); ?></span>
					</div>
					<ul class="ff-core-status-list">
						<?php foreach ( $tables as $table ) : ?>
							<li>
								<span><code><?php echo esc_html( $table['name'] ); ?></code></span>
								<strong class="<?php echo $table['exists'] ? 'is-ready' : 'is-missing'; ?>">
									<?php echo esc_html( $table['exists'] ? __( 'Ready', 'forgetfit-core' ) : __( 'Missing', 'forgetfit-core' ) ); ?>
								</strong>
							</li>
						<?php endforeach; ?>
					</ul>
				</section>

				<section class="ff-core-panel">
					<div class="ff-core-panel__header">
						<h2><?php esc_html_e( 'Access model', 'forgetfit-core' ); ?></h2>
						<span><?php esc_html_e( 'Least privilege', 'forgetfit-core' ); ?></span>
					</div>
					<ul class="ff-core-status-list">
						<?php foreach ( $roles as $slug => $label ) : ?>
							<li>
								<span><?php echo esc_html( $label ); ?><small><?php echo esc_html( $slug ); ?></small></span>
								<strong class="<?php echo get_role( $slug ) ? 'is-ready' : 'is-missing'; ?>">
									<?php echo esc_html( get_role( $slug ) ? __( 'Ready', 'forgetfit-core' ) : __( 'Missing', 'forgetfit-core' ) ); ?>
								</strong>
							</li>
						<?php endforeach; ?>
					</ul>
				</section>
			</div>

			<section class="ff-core-next">
				<div>
					<p class="ff-core-eyebrow"><?php esc_html_e( 'Next build milestone', 'forgetfit-core' ); ?></p>
					<h2><?php esc_html_e( 'Member onboarding and dashboard', 'forgetfit-core' ); ?></h2>
					<p><?php esc_html_e( 'The next phase converts qualified leads into secure member accounts with plan history and a protected self-service dashboard.', 'forgetfit-core' ); ?></p>
				</div>
				<span aria-hidden="true">03</span>
			</section>
		</div>
		<?php
	}

	/**
	 * Fetch safe high-level totals for the overview.
	 *
	 * @return array<int,array<string,mixed>>
	 */
	private static function summary_cards() {
		global $wpdb;

		$tables = Schema::tables();
		$items  = array(
			array( 'key' => 'plans', 'label' => __( 'Plans', 'forgetfit-core' ), 'description' => __( 'Active membership offers', 'forgetfit-core' ), 'where' => ' WHERE is_active = 1' ),
			array( 'key' => 'leads', 'label' => __( 'Leads', 'forgetfit-core' ), 'description' => __( 'Free-pass enquiries', 'forgetfit-core' ), 'where' => '' ),
			array( 'key' => 'memberships', 'label' => __( 'Memberships', 'forgetfit-core' ), 'description' => __( 'All membership records', 'forgetfit-core' ), 'where' => '' ),
			array( 'key' => 'sessions', 'label' => __( 'Sessions', 'forgetfit-core' ), 'description' => __( 'Scheduled training sessions', 'forgetfit-core' ), 'where' => '' ),
		);
		$cards = array();

		foreach ( $items as $item ) {
			$table = $tables[ $item['key'] ];
			$value = self::table_exists( $table ) ? (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table}{$item['where']}" ) : 0; // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$cards[] = array(
				'label'       => $item['label'],
				'value'       => $value,
				'description' => $item['description'],
			);
		}

		return $cards;
	}

	/**
	 * List table availability.
	 *
	 * @return array<int,array<string,mixed>>
	 */
	private static function table_statuses() {
		$statuses = array();
		foreach ( Schema::tables() as $table ) {
			$statuses[] = array(
				'name'   => $table,
				'exists' => self::table_exists( $table ),
			);
		}
		return $statuses;
	}

	/**
	 * Determine whether an internal table exists.
	 *
	 * @param string $table Full table name.
	 * @return bool
	 */
	private static function table_exists( $table ) {
		global $wpdb;
		return $table === $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table ) ) );
	}
}
