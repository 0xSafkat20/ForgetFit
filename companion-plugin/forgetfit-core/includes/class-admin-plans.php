<?php
/**
 * Membership plan management screens.
 *
 * @package ForgetFitCore
 */

namespace ForgetFit\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Admin_Plans {
	/** Register menu and handlers. */
	public static function register() {
		add_action( 'admin_menu', array( __CLASS__, 'add_menu' ) );
		add_action( 'admin_post_ff_save_plan', array( __CLASS__, 'handle_save' ) );
		add_action( 'admin_post_ff_toggle_plan', array( __CLASS__, 'handle_toggle' ) );
	}

	/** Add plan management below Leads. */
	public static function add_menu() {
		add_submenu_page(
			'forgetfit',
			__( 'Membership Plans', 'forgetfit-core' ),
			__( 'Plans', 'forgetfit-core' ),
			'ff_manage_plans',
			'forgetfit-plans',
			array( __CLASS__, 'render' )
		);
	}

	/** Render plan list with an edit form. */
	public static function render() {
		self::authorize();
		$edit_id = isset( $_GET['plan_id'] ) ? absint( $_GET['plan_id'] ) : 0;
		$editing = $edit_id ? Plan_Repository::find( $edit_id ) : null;
		$plans   = Plan_Repository::all();
		$periods = Plan_Repository::billing_periods();
		?>
		<div class="wrap ff-core-admin ff-plans-admin">
			<div class="ff-admin-title"><div><p class="ff-core-eyebrow"><?php esc_html_e( 'Offer management', 'forgetfit-core' ); ?></p><h1><?php esc_html_e( 'Membership plans', 'forgetfit-core' ); ?></h1><p><?php esc_html_e( 'Control the prices and benefits shown on the public membership cards.', 'forgetfit-core' ); ?></p></div><a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=forgetfit-plans#plan-editor' ) ); ?>"><?php esc_html_e( 'Add plan', 'forgetfit-core' ); ?></a></div>
			<?php self::notice(); ?>
			<div class="ff-plans-layout">
				<section class="ff-core-panel ff-plans-list">
					<div class="ff-core-panel__header"><h2><?php esc_html_e( 'Current plans', 'forgetfit-core' ); ?></h2><span><?php echo esc_html( number_format_i18n( count( $plans ) ) ); ?></span></div>
					<div class="ff-table-wrap"><table class="wp-list-table widefat fixed striped ff-data-table"><thead><tr><th><?php esc_html_e( 'Plan', 'forgetfit-core' ); ?></th><th><?php esc_html_e( 'Price', 'forgetfit-core' ); ?></th><th><?php esc_html_e( 'Order', 'forgetfit-core' ); ?></th><th><?php esc_html_e( 'Visibility', 'forgetfit-core' ); ?></th><th><?php esc_html_e( 'Actions', 'forgetfit-core' ); ?></th></tr></thead><tbody>
					<?php foreach ( $plans as $plan ) : ?>
						<tr>
							<td data-colname="<?php esc_attr_e( 'Plan', 'forgetfit-core' ); ?>"><strong><?php echo esc_html( $plan->name ); ?></strong><small><?php echo esc_html( $plan->slug ); ?><?php echo $plan->is_featured ? ' · ' . esc_html__( 'Featured', 'forgetfit-core' ) : ''; ?></small></td>
							<td data-colname="<?php esc_attr_e( 'Price', 'forgetfit-core' ); ?>"><?php echo esc_html( $plan->currency . ' ' . number_format_i18n( $plan->price_minor / 100, 2 ) ); ?><small><?php echo esc_html( isset( $periods[ $plan->billing_period ] ) ? $periods[ $plan->billing_period ] : $plan->billing_period ); ?></small></td>
							<td data-colname="<?php esc_attr_e( 'Order', 'forgetfit-core' ); ?>"><?php echo esc_html( $plan->sort_order ); ?></td>
							<td data-colname="<?php esc_attr_e( 'Visibility', 'forgetfit-core' ); ?>"><span class="ff-status <?php echo $plan->is_active ? 'ff-status--won' : 'ff-status--lost'; ?>"><?php echo esc_html( $plan->is_active ? __( 'Active', 'forgetfit-core' ) : __( 'Archived', 'forgetfit-core' ) ); ?></span></td>
							<td data-colname="<?php esc_attr_e( 'Actions', 'forgetfit-core' ); ?>">
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=forgetfit-plans&plan_id=' . absint( $plan->id ) . '#plan-editor' ) ); ?>"><?php esc_html_e( 'Edit', 'forgetfit-core' ); ?></a>
								<form class="ff-inline-action" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post"<?php if ( $plan->is_active ) : ?> onsubmit="return window.confirm('<?php echo esc_js( __( 'Archive this plan? It will be removed from the public membership page.', 'forgetfit-core' ) ); ?>');"<?php endif; ?>>
									<input type="hidden" name="action" value="ff_toggle_plan"><input type="hidden" name="plan_id" value="<?php echo esc_attr( $plan->id ); ?>"><input type="hidden" name="active" value="<?php echo esc_attr( $plan->is_active ? 0 : 1 ); ?>"><?php wp_nonce_field( 'ff_toggle_plan_' . $plan->id ); ?><button class="button-link" type="submit"><?php echo esc_html( $plan->is_active ? __( 'Archive', 'forgetfit-core' ) : __( 'Activate', 'forgetfit-core' ) ); ?></button>
								</form>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody></table></div>
				</section>

				<section id="plan-editor" class="ff-core-panel ff-plan-editor">
					<div class="ff-core-panel__header"><h2><?php echo esc_html( $editing ? __( 'Edit plan', 'forgetfit-core' ) : __( 'Add plan', 'forgetfit-core' ) ); ?></h2><?php if ( $editing ) : ?><a href="<?php echo esc_url( admin_url( 'admin.php?page=forgetfit-plans#plan-editor' ) ); ?>"><?php esc_html_e( 'Cancel', 'forgetfit-core' ); ?></a><?php endif; ?></div>
					<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
						<input type="hidden" name="action" value="ff_save_plan"><input type="hidden" name="plan_id" value="<?php echo esc_attr( $editing ? $editing->id : 0 ); ?>"><?php wp_nonce_field( 'ff_save_plan' ); ?>
						<label for="ff-plan-name"><?php esc_html_e( 'Plan name', 'forgetfit-core' ); ?></label><input id="ff-plan-name" name="plan_name" type="text" maxlength="190" value="<?php echo esc_attr( $editing ? $editing->name : '' ); ?>" required>
						<label for="ff-plan-slug"><?php esc_html_e( 'Slug', 'forgetfit-core' ); ?></label><input id="ff-plan-slug" name="plan_slug" type="text" maxlength="190" value="<?php echo esc_attr( $editing ? $editing->slug : '' ); ?>" placeholder="generated-from-name">
						<label for="ff-plan-eyebrow"><?php esc_html_e( 'Card label', 'forgetfit-core' ); ?></label><input id="ff-plan-eyebrow" name="plan_eyebrow" type="text" maxlength="80" value="<?php echo esc_attr( $editing ? $editing->eyebrow : '' ); ?>" placeholder="Train your way">
						<label for="ff-plan-description"><?php esc_html_e( 'Description', 'forgetfit-core' ); ?></label><textarea id="ff-plan-description" name="plan_description" rows="4" maxlength="1000" required><?php echo esc_textarea( $editing ? $editing->description : '' ); ?></textarea>
						<div class="ff-form-row"><label for="ff-plan-price"><span><?php esc_html_e( 'Price', 'forgetfit-core' ); ?></span><input id="ff-plan-price" name="plan_price" type="number" min="0" step="0.01" value="<?php echo esc_attr( $editing ? number_format( $editing->price_minor / 100, 2, '.', '' ) : '' ); ?>" required></label><label for="ff-plan-currency"><span><?php esc_html_e( 'Currency', 'forgetfit-core' ); ?></span><input id="ff-plan-currency" name="plan_currency" type="text" minlength="3" maxlength="3" value="<?php echo esc_attr( $editing ? $editing->currency : 'USD' ); ?>" required></label></div>
						<div class="ff-form-row"><label for="ff-plan-period"><span><?php esc_html_e( 'Billing period', 'forgetfit-core' ); ?></span><select id="ff-plan-period" name="plan_billing_period"><?php foreach ( $periods as $key => $label ) : ?><option value="<?php echo esc_attr( $key ); ?>" <?php selected( $editing ? $editing->billing_period : 'month', $key ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?></select></label><label for="ff-plan-order"><span><?php esc_html_e( 'Display order', 'forgetfit-core' ); ?></span><input id="ff-plan-order" name="plan_sort_order" type="number" step="1" value="<?php echo esc_attr( $editing ? $editing->sort_order : 40 ); ?>"></label></div>
						<label for="ff-plan-benefits"><?php esc_html_e( 'Benefits — one per line', 'forgetfit-core' ); ?></label><textarea id="ff-plan-benefits" name="plan_benefits" rows="6" required><?php $benefits = $editing ? json_decode( $editing->benefits, true ) : array(); echo esc_textarea( is_array( $benefits ) ? implode( "\n", $benefits ) : '' ); ?></textarea>
						<label class="ff-check"><input name="plan_featured" type="checkbox" value="1" <?php checked( $editing ? $editing->is_featured : false ); ?>> <?php esc_html_e( 'Feature this plan', 'forgetfit-core' ); ?></label>
						<label class="ff-check"><input name="plan_active" type="checkbox" value="1" <?php checked( $editing ? $editing->is_active : true ); ?>> <?php esc_html_e( 'Show this plan publicly', 'forgetfit-core' ); ?></label>
						<button class="button button-primary button-large" type="submit"><?php echo esc_html( $editing ? __( 'Update plan', 'forgetfit-core' ) : __( 'Create plan', 'forgetfit-core' ) ); ?></button>
					</form>
				</section>
			</div>
		</div>
		<?php
	}

	/** Save a plan. */
	public static function handle_save() {
		self::authorize();
		check_admin_referer( 'ff_save_plan' );
		$plan_id = isset( $_POST['plan_id'] ) ? absint( $_POST['plan_id'] ) : 0;
		$data = array(
			'name'           => isset( $_POST['plan_name'] ) ? wp_unslash( $_POST['plan_name'] ) : '',
			'slug'           => isset( $_POST['plan_slug'] ) ? wp_unslash( $_POST['plan_slug'] ) : '',
			'eyebrow'        => isset( $_POST['plan_eyebrow'] ) ? wp_unslash( $_POST['plan_eyebrow'] ) : '',
			'description'    => isset( $_POST['plan_description'] ) ? wp_unslash( $_POST['plan_description'] ) : '',
			'price'          => isset( $_POST['plan_price'] ) ? wp_unslash( $_POST['plan_price'] ) : '',
			'currency'       => isset( $_POST['plan_currency'] ) ? wp_unslash( $_POST['plan_currency'] ) : '',
			'billing_period' => isset( $_POST['plan_billing_period'] ) ? wp_unslash( $_POST['plan_billing_period'] ) : '',
			'benefits'       => isset( $_POST['plan_benefits'] ) ? wp_unslash( $_POST['plan_benefits'] ) : '',
			'is_featured'    => isset( $_POST['plan_featured'] ),
			'is_active'      => isset( $_POST['plan_active'] ),
			'sort_order'     => isset( $_POST['plan_sort_order'] ) ? intval( $_POST['plan_sort_order'] ) : 0,
		);
		$result = Plan_Repository::save( $data, $plan_id );
		$url = add_query_arg( array( 'page' => 'forgetfit-plans', 'ff_notice' => is_wp_error( $result ) ? 'error' : 'saved' ), admin_url( 'admin.php' ) );
		if ( is_wp_error( $result ) ) {
			$url = add_query_arg( 'ff_message', rawurlencode( $result->get_error_message() ), $url );
		}
		wp_safe_redirect( $url );
		exit;
	}

	/** Toggle plan visibility. */
	public static function handle_toggle() {
		self::authorize();
		$plan_id = isset( $_POST['plan_id'] ) ? absint( $_POST['plan_id'] ) : 0;
		check_admin_referer( 'ff_toggle_plan_' . $plan_id );
		$active = isset( $_POST['active'] ) && 1 === absint( $_POST['active'] );
		$result = Plan_Repository::set_active( $plan_id, $active );
		$url = add_query_arg( array( 'page' => 'forgetfit-plans', 'ff_notice' => is_wp_error( $result ) ? 'error' : 'toggled' ), admin_url( 'admin.php' ) );
		if ( is_wp_error( $result ) ) {
			$url = add_query_arg( 'ff_message', rawurlencode( $result->get_error_message() ), $url );
		}
		wp_safe_redirect( $url );
		exit;
	}

	/** Check plan-management permission. */
	private static function authorize() {
		if ( ! current_user_can( 'ff_manage_plans' ) ) {
			wp_die( esc_html__( 'You do not have permission to manage plans.', 'forgetfit-core' ) );
		}
	}

	/** Render a result notice. */
	private static function notice() {
		$key = isset( $_GET['ff_notice'] ) ? sanitize_key( wp_unslash( $_GET['ff_notice'] ) ) : '';
		if ( ! $key ) {
			return;
		}
		$is_error = 'error' === $key;
		$messages = array( 'saved' => __( 'Membership plan saved.', 'forgetfit-core' ), 'toggled' => __( 'Plan visibility updated.', 'forgetfit-core' ) );
		$message = $is_error && isset( $_GET['ff_message'] ) ? sanitize_text_field( rawurldecode( wp_unslash( $_GET['ff_message'] ) ) ) : ( isset( $messages[ $key ] ) ? $messages[ $key ] : '' );
		if ( $message ) {
			echo '<div class="notice ' . esc_attr( $is_error ? 'notice-error' : 'notice-success' ) . ' is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
		}
	}
}
