<?php
/**
 * Staff lead inbox and lead detail workflows.
 *
 * @package ForgetFitCore
 */

namespace ForgetFit\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Admin_Leads {
	/** Register menu and mutation handlers. */
	public static function register() {
		add_action( 'admin_menu', array( __CLASS__, 'add_menu' ) );
		add_action( 'admin_post_ff_update_lead', array( __CLASS__, 'handle_update' ) );
		add_action( 'admin_post_ff_add_lead_note', array( __CLASS__, 'handle_note' ) );
		add_action( 'admin_post_ff_export_leads', array( __CLASS__, 'handle_export' ) );
	}

	/** Add the lead inbox submenu. */
	public static function add_menu() {
		add_submenu_page(
			'forgetfit',
			__( 'Leads', 'forgetfit-core' ),
			__( 'Leads', 'forgetfit-core' ),
			'ff_manage_leads',
			'forgetfit-leads',
			array( __CLASS__, 'render' )
		);
	}

	/** Render list or detail view. */
	public static function render() {
		self::authorize();
		$lead_id = isset( $_GET['lead_id'] ) ? absint( $_GET['lead_id'] ) : 0;
		if ( $lead_id ) {
			self::render_detail( $lead_id );
			return;
		}
		self::render_list();
	}

	/** Process status and assignment updates. */
	public static function handle_update() {
		self::authorize();
		$lead_id = isset( $_POST['lead_id'] ) ? absint( $_POST['lead_id'] ) : 0;
		check_admin_referer( 'ff_update_lead_' . $lead_id );
		$status      = isset( $_POST['lead_status'] ) ? sanitize_key( wp_unslash( $_POST['lead_status'] ) ) : '';
		$assigned_to = isset( $_POST['assigned_to'] ) ? absint( $_POST['assigned_to'] ) : 0;
		$result       = Lead_Repository::update_workflow( $lead_id, $status, $assigned_to );
		self::redirect_detail( $lead_id, is_wp_error( $result ) ? 'error' : 'updated', is_wp_error( $result ) ? $result->get_error_message() : '' );
	}

	/** Process a new internal note. */
	public static function handle_note() {
		self::authorize();
		$lead_id = isset( $_POST['lead_id'] ) ? absint( $_POST['lead_id'] ) : 0;
		check_admin_referer( 'ff_add_lead_note_' . $lead_id );
		$note   = isset( $_POST['lead_note'] ) ? wp_unslash( $_POST['lead_note'] ) : '';
		$result = Lead_Repository::add_note( $lead_id, $note );
		self::redirect_detail( $lead_id, is_wp_error( $result ) ? 'error' : 'noted', is_wp_error( $result ) ? $result->get_error_message() : '' );
	}

	/** Export the active filtered lead view as CSV. */
	public static function handle_export() {
		self::authorize();
		check_admin_referer( 'ff_export_leads' );
		$filters = self::request_filters();
		$filters['page'] = 1;
		$filters['per_page'] = 5000;
		$result = Lead_Repository::query( $filters );
		Audit_Log::record( 'leads_exported', 'lead_export', 0, array( 'filters' => $filters, 'row_count' => $result['total'] ), get_current_user_id() );

		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=forgetfit-leads-' . gmdate( 'Y-m-d' ) . '.csv' );
		$output = fopen( 'php://output', 'w' );
		fputcsv( $output, array( 'ID', 'Name', 'Email', 'Phone', 'Status', 'Preferred visit', 'Preferred time', 'Fitness goal', 'Experience', 'Source', 'Consent UTC', 'Created UTC' ) );
		foreach ( $result['items'] as $lead ) {
			$row = array( $lead->id, $lead->name, $lead->email, $lead->phone, $lead->status, $lead->preferred_visit, $lead->preferred_time, $lead->fitness_goal, $lead->experience_level, $lead->source, $lead->consent_at, $lead->created_at );
			fputcsv( $output, array_map( array( __CLASS__, 'csv_cell' ), $row ) );
		}
		fclose( $output );
		exit;
	}

	/** Render the searchable, filterable inbox. */
	private static function render_list() {
		$filters = self::request_filters();
		$result  = Lead_Repository::query( $filters );
		$statuses = Lead_Repository::statuses();
		$sources = Lead_Repository::distinct_values( 'source' );
		$goals = Lead_Repository::distinct_values( 'fitness_goal' );
		$export_url = wp_nonce_url(
			add_query_arg(
				array( 'action' => 'ff_export_leads', 'lead_status' => $filters['status'], 'lead_search' => $filters['search'], 'lead_source' => $filters['source'], 'lead_goal' => $filters['goal'], 'visit_from' => $filters['visit_from'], 'visit_to' => $filters['visit_to'] ),
				admin_url( 'admin-post.php' )
			),
			'ff_export_leads'
		);
		?>
		<div class="wrap ff-core-admin ff-leads">
			<div class="ff-admin-title">
				<div><p class="ff-core-eyebrow"><?php esc_html_e( 'Visitor pipeline', 'forgetfit-core' ); ?></p><h1><?php esc_html_e( 'Leads', 'forgetfit-core' ); ?></h1><p><?php esc_html_e( 'Follow every free-pass enquiry from first contact to membership.', 'forgetfit-core' ); ?></p></div>
				<a class="button" href="<?php echo esc_url( $export_url ); ?>"><?php esc_html_e( 'Export filtered CSV', 'forgetfit-core' ); ?></a>
			</div>
			<?php self::notice(); ?>
			<form class="ff-filter-bar" method="get">
				<input type="hidden" name="page" value="forgetfit-leads">
				<label><span><?php esc_html_e( 'Search', 'forgetfit-core' ); ?></span><input type="search" name="lead_search" value="<?php echo esc_attr( $filters['search'] ); ?>" placeholder="<?php esc_attr_e( 'Name, email, or phone', 'forgetfit-core' ); ?>"></label>
				<label><span><?php esc_html_e( 'Status', 'forgetfit-core' ); ?></span><select name="lead_status"><option value=""><?php esc_html_e( 'All statuses', 'forgetfit-core' ); ?></option><?php foreach ( $statuses as $key => $label ) : ?><option value="<?php echo esc_attr( $key ); ?>" <?php selected( $filters['status'], $key ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?></select></label>
				<label><span><?php esc_html_e( 'Source', 'forgetfit-core' ); ?></span><select name="lead_source"><option value=""><?php esc_html_e( 'All sources', 'forgetfit-core' ); ?></option><?php foreach ( $sources as $source ) : ?><option value="<?php echo esc_attr( $source ); ?>" <?php selected( $filters['source'], $source ); ?>><?php echo esc_html( self::humanize( $source ) ); ?></option><?php endforeach; ?></select></label>
				<label><span><?php esc_html_e( 'Goal', 'forgetfit-core' ); ?></span><select name="lead_goal"><option value=""><?php esc_html_e( 'All goals', 'forgetfit-core' ); ?></option><?php foreach ( $goals as $goal ) : ?><option value="<?php echo esc_attr( $goal ); ?>" <?php selected( $filters['goal'], $goal ); ?>><?php echo esc_html( self::humanize( $goal ) ); ?></option><?php endforeach; ?></select></label>
				<label><span><?php esc_html_e( 'Visit from', 'forgetfit-core' ); ?></span><input type="date" name="visit_from" value="<?php echo esc_attr( $filters['visit_from'] ); ?>"></label>
				<label><span><?php esc_html_e( 'Visit to', 'forgetfit-core' ); ?></span><input type="date" name="visit_to" value="<?php echo esc_attr( $filters['visit_to'] ); ?>"></label>
				<button class="button button-primary" type="submit"><?php esc_html_e( 'Filter', 'forgetfit-core' ); ?></button>
				<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=forgetfit-leads' ) ); ?>"><?php esc_html_e( 'Reset', 'forgetfit-core' ); ?></a>
			</form>

			<div class="ff-table-wrap">
				<table class="wp-list-table widefat fixed striped ff-data-table">
					<thead><tr><th><?php esc_html_e( 'Lead', 'forgetfit-core' ); ?></th><th><?php esc_html_e( 'Status', 'forgetfit-core' ); ?></th><th><?php esc_html_e( 'Visit preference', 'forgetfit-core' ); ?></th><th><?php esc_html_e( 'Goal', 'forgetfit-core' ); ?></th><th><?php esc_html_e( 'Owner', 'forgetfit-core' ); ?></th><th><?php esc_html_e( 'Received', 'forgetfit-core' ); ?></th></tr></thead>
					<tbody>
					<?php if ( ! $result['items'] ) : ?>
						<tr><td colspan="6" class="ff-empty"><strong><?php esc_html_e( 'No leads match this view.', 'forgetfit-core' ); ?></strong><span><?php esc_html_e( 'New free-pass requests will appear here automatically.', 'forgetfit-core' ); ?></span></td></tr>
					<?php else : foreach ( $result['items'] as $lead ) : ?>
						<?php $owner = $lead->assigned_to ? get_userdata( $lead->assigned_to ) : null; ?>
						<tr>
							<td data-colname="<?php esc_attr_e( 'Lead', 'forgetfit-core' ); ?>"><strong><a href="<?php echo esc_url( admin_url( 'admin.php?page=forgetfit-leads&lead_id=' . absint( $lead->id ) ) ); ?>"><?php echo esc_html( $lead->name ); ?></a></strong><small><a href="mailto:<?php echo esc_attr( $lead->email ); ?>"><?php echo esc_html( $lead->email ); ?></a><?php echo $lead->phone ? ' · ' . esc_html( $lead->phone ) : ''; ?></small></td>
							<td data-colname="<?php esc_attr_e( 'Status', 'forgetfit-core' ); ?>"><span class="ff-status ff-status--<?php echo esc_attr( $lead->status ); ?>"><?php echo esc_html( isset( $statuses[ $lead->status ] ) ? $statuses[ $lead->status ] : $lead->status ); ?></span></td>
							<td data-colname="<?php esc_attr_e( 'Visit preference', 'forgetfit-core' ); ?>"><?php echo esc_html( self::format_date( $lead->preferred_visit ) ); ?><small><?php echo esc_html( self::humanize( $lead->preferred_time ) ); ?></small></td>
							<td data-colname="<?php esc_attr_e( 'Goal', 'forgetfit-core' ); ?>"><?php echo esc_html( self::humanize( $lead->fitness_goal ) ); ?></td>
							<td data-colname="<?php esc_attr_e( 'Owner', 'forgetfit-core' ); ?>"><?php echo esc_html( $owner ? $owner->display_name : __( 'Unassigned', 'forgetfit-core' ) ); ?></td>
							<td data-colname="<?php esc_attr_e( 'Received', 'forgetfit-core' ); ?>"><?php echo esc_html( self::format_date( $lead->created_at, true ) ); ?></td>
						</tr>
					<?php endforeach; endif; ?>
					</tbody>
				</table>
			</div>
			<?php self::pagination( $result, $filters ); ?>
		</div>
		<?php
	}

	/** Render a lead record and its follow-up history. */
	private static function render_detail( $lead_id ) {
		$lead = Lead_Repository::find( $lead_id );
		if ( ! $lead ) {
			wp_die( esc_html__( 'Lead not found.', 'forgetfit-core' ) );
		}
		$statuses = Lead_Repository::statuses();
		$notes    = Lead_Repository::notes( $lead_id );
		$staff    = get_users( array( 'role__in' => array( 'administrator', 'forgetfit_manager' ), 'orderby' => 'display_name', 'order' => 'ASC' ) );
		?>
		<div class="wrap ff-core-admin ff-lead-detail">
			<a class="ff-back-link" href="<?php echo esc_url( admin_url( 'admin.php?page=forgetfit-leads' ) ); ?>">← <?php esc_html_e( 'Back to leads', 'forgetfit-core' ); ?></a>
			<div class="ff-admin-title"><div><p class="ff-core-eyebrow"><?php echo esc_html( sprintf( __( 'Lead #%d', 'forgetfit-core' ), $lead->id ) ); ?></p><h1><?php echo esc_html( $lead->name ); ?></h1><p><?php echo esc_html( sprintf( __( 'Received %s', 'forgetfit-core' ), self::format_date( $lead->created_at, true ) ) ); ?></p></div><span class="ff-status ff-status--<?php echo esc_attr( $lead->status ); ?>"><?php echo esc_html( $statuses[ $lead->status ] ); ?></span></div>
			<?php self::notice(); ?>
			<div class="ff-detail-grid">
				<div class="ff-detail-main">
					<section class="ff-core-panel ff-contact-card">
						<div class="ff-core-panel__header"><h2><?php esc_html_e( 'Contact and visit', 'forgetfit-core' ); ?></h2><span><?php echo esc_html( self::humanize( $lead->source ) ); ?></span></div>
						<dl class="ff-detail-list">
							<div><dt><?php esc_html_e( 'Email', 'forgetfit-core' ); ?></dt><dd><a href="mailto:<?php echo esc_attr( $lead->email ); ?>"><?php echo esc_html( $lead->email ); ?></a></dd></div>
							<div><dt><?php esc_html_e( 'Phone', 'forgetfit-core' ); ?></dt><dd><?php echo $lead->phone ? '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $lead->phone ) ) . '">' . esc_html( $lead->phone ) . '</a>' : esc_html__( 'Not provided', 'forgetfit-core' ); ?></dd></div>
							<div><dt><?php esc_html_e( 'Preferred visit', 'forgetfit-core' ); ?></dt><dd><?php echo esc_html( self::format_date( $lead->preferred_visit ) . ' · ' . self::humanize( $lead->preferred_time ) ); ?></dd></div>
							<div><dt><?php esc_html_e( 'Fitness goal', 'forgetfit-core' ); ?></dt><dd><?php echo esc_html( self::humanize( $lead->fitness_goal ) ); ?></dd></div>
							<div><dt><?php esc_html_e( 'Experience', 'forgetfit-core' ); ?></dt><dd><?php echo esc_html( self::humanize( $lead->experience_level ) ); ?></dd></div>
							<div><dt><?php esc_html_e( 'Consent', 'forgetfit-core' ); ?></dt><dd><?php echo esc_html( self::format_date( $lead->consent_at, true ) ); ?><small><?php echo esc_html( $lead->privacy_version ); ?></small></dd></div>
						</dl>
					</section>

					<section class="ff-core-panel">
						<div class="ff-core-panel__header"><h2><?php esc_html_e( 'Internal notes', 'forgetfit-core' ); ?></h2><span><?php echo esc_html( number_format_i18n( count( $notes ) ) ); ?></span></div>
						<form class="ff-note-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
							<input type="hidden" name="action" value="ff_add_lead_note"><input type="hidden" name="lead_id" value="<?php echo esc_attr( $lead->id ); ?>"><?php wp_nonce_field( 'ff_add_lead_note_' . $lead->id ); ?>
							<label for="ff-lead-note"><?php esc_html_e( 'Add a follow-up note', 'forgetfit-core' ); ?></label><textarea id="ff-lead-note" name="lead_note" rows="4" maxlength="2000" required></textarea><button class="button button-primary" type="submit"><?php esc_html_e( 'Add note', 'forgetfit-core' ); ?></button>
						</form>
						<div class="ff-note-timeline">
							<?php if ( ! $notes ) : ?><p class="ff-empty-inline"><?php esc_html_e( 'No internal notes yet.', 'forgetfit-core' ); ?></p><?php else : foreach ( $notes as $note ) : ?><article><header><strong><?php echo esc_html( $note->display_name ?: __( 'Former staff member', 'forgetfit-core' ) ); ?></strong><time><?php echo esc_html( self::format_date( $note->created_at, true ) ); ?></time></header><p><?php echo nl2br( esc_html( $note->note ) ); ?></p></article><?php endforeach; endif; ?>
						</div>
					</section>
				</div>

				<aside class="ff-detail-side">
					<section class="ff-core-panel">
						<div class="ff-core-panel__header"><h2><?php esc_html_e( 'Workflow', 'forgetfit-core' ); ?></h2></div>
						<form class="ff-workflow-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
							<input type="hidden" name="action" value="ff_update_lead"><input type="hidden" name="lead_id" value="<?php echo esc_attr( $lead->id ); ?>"><?php wp_nonce_field( 'ff_update_lead_' . $lead->id ); ?>
							<label for="ff-lead-status"><?php esc_html_e( 'Status', 'forgetfit-core' ); ?></label><select id="ff-lead-status" name="lead_status"><?php foreach ( $statuses as $key => $label ) : ?><option value="<?php echo esc_attr( $key ); ?>" <?php selected( $lead->status, $key ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?></select>
							<label for="ff-assigned-to"><?php esc_html_e( 'Owner', 'forgetfit-core' ); ?></label><select id="ff-assigned-to" name="assigned_to"><option value="0"><?php esc_html_e( 'Unassigned', 'forgetfit-core' ); ?></option><?php foreach ( $staff as $person ) : ?><option value="<?php echo esc_attr( $person->ID ); ?>" <?php selected( (int) $lead->assigned_to, (int) $person->ID ); ?>><?php echo esc_html( $person->display_name ); ?></option><?php endforeach; ?></select>
							<button class="button button-primary" type="submit"><?php esc_html_e( 'Save workflow', 'forgetfit-core' ); ?></button>
						</form>
					</section>
				</aside>
			</div>
		</div>
		<?php
	}

	/** Ensure the current user can manage leads. */
	private static function authorize() {
		if ( ! current_user_can( 'ff_manage_leads' ) ) {
			wp_die( esc_html__( 'You do not have permission to manage leads.', 'forgetfit-core' ) );
		}
	}

	/** Return sanitized list filters. */
	private static function request_filters() {
		return array(
			'search'   => isset( $_GET['lead_search'] ) ? sanitize_text_field( wp_unslash( $_GET['lead_search'] ) ) : '',
			'status'   => isset( $_GET['lead_status'] ) ? sanitize_key( wp_unslash( $_GET['lead_status'] ) ) : '',
			'source'   => isset( $_GET['lead_source'] ) ? sanitize_key( wp_unslash( $_GET['lead_source'] ) ) : '',
			'goal'     => isset( $_GET['lead_goal'] ) ? sanitize_key( wp_unslash( $_GET['lead_goal'] ) ) : '',
			'visit_from' => isset( $_GET['visit_from'] ) ? sanitize_text_field( wp_unslash( $_GET['visit_from'] ) ) : '',
			'visit_to' => isset( $_GET['visit_to'] ) ? sanitize_text_field( wp_unslash( $_GET['visit_to'] ) ) : '',
			'page'     => isset( $_GET['paged'] ) ? max( 1, absint( $_GET['paged'] ) ) : 1,
			'per_page' => 20,
		);
	}

	/** Render pagination links. */
	private static function pagination( $result, $filters ) {
		if ( $result['pages'] <= 1 ) {
			return;
		}
		$base = add_query_arg( array( 'page' => 'forgetfit-leads', 'lead_search' => $filters['search'], 'lead_status' => $filters['status'], 'lead_source' => $filters['source'], 'lead_goal' => $filters['goal'], 'visit_from' => $filters['visit_from'], 'visit_to' => $filters['visit_to'], 'paged' => '%#%' ), admin_url( 'admin.php' ) );
		echo '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post( paginate_links( array( 'base' => $base, 'format' => '', 'current' => $result['page'], 'total' => $result['pages'], 'prev_text' => '‹', 'next_text' => '›' ) ) ) . '</div></div>';
	}

	/** Redirect back to a lead after a mutation. */
	private static function redirect_detail( $lead_id, $notice, $message = '' ) {
		$url = add_query_arg( array( 'page' => 'forgetfit-leads', 'lead_id' => absint( $lead_id ), 'ff_notice' => sanitize_key( $notice ) ), admin_url( 'admin.php' ) );
		if ( $message ) {
			$url = add_query_arg( 'ff_message', rawurlencode( $message ), $url );
		}
		wp_safe_redirect( $url );
		exit;
	}

	/** Render a redirect notice. */
	private static function notice() {
		$key = isset( $_GET['ff_notice'] ) ? sanitize_key( wp_unslash( $_GET['ff_notice'] ) ) : '';
		if ( ! $key ) {
			return;
		}
		$messages = array( 'updated' => __( 'Lead workflow updated.', 'forgetfit-core' ), 'noted' => __( 'Internal note added.', 'forgetfit-core' ) );
		$is_error = 'error' === $key;
		$message = $is_error && isset( $_GET['ff_message'] ) ? sanitize_text_field( rawurldecode( wp_unslash( $_GET['ff_message'] ) ) ) : ( isset( $messages[ $key ] ) ? $messages[ $key ] : '' );
		if ( $message ) {
			echo '<div class="notice ' . esc_attr( $is_error ? 'notice-error' : 'notice-success' ) . ' is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
		}
	}

	/** Format UTC database values in the site timezone. */
	private static function format_date( $value, $with_time = false ) {
		if ( ! $value ) {
			return __( 'Not set', 'forgetfit-core' );
		}
		$timestamp = strtotime( $value . ' UTC' );
		return wp_date( get_option( 'date_format' ) . ( $with_time ? ' · ' . get_option( 'time_format' ) : '' ), $timestamp, wp_timezone() );
	}

	/** Humanize a stored key for admin display. */
	private static function humanize( $value ) {
		return ucwords( str_replace( array( '_', '-' ), ' ', (string) $value ) );
	}

	/** Prevent spreadsheet formula execution in exported visitor fields. */
	private static function csv_cell( $value ) {
		$value = (string) $value;
		if ( preg_match( '/^[=+\-@]/', $value ) ) {
			return "'" . $value;
		}
		return $value;
	}
}
