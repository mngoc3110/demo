<?php
/**
 * Display notice on admin page after theme installed before 5 days
 *
 * @package Fort
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class to display the `Upgrade to Pro` admin notice.
 *
 * Class Fort_Theme_Notice
 */
class Fort_Theme_Notice {

	/**
	 * Currently active theme in the site.
	 *
	 * @var \WP_Theme
	 */
	protected $active_theme;

	/**
	 * Current user id.
	 *
	 * @var int Current user id.
	 */
	protected $current_user_data;

	/**
	 * Constructor function for `Upgrade To Pro` admin notice.
	 *
	 * Fort_Theme_Notice constructor.
	 */
	public function __construct() {

		add_action( 'after_setup_theme', array( $this, 'fort_pro_theme_notice' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	}

	/**
	 * Function to hold the available themes, which have pro version available.
	 *
	 * @return array Theme lists.
	 */
	public static function get_theme() {

		$theme_name = array(
			'fort'      => 'https://www.candidthemes.com/themes/fort-pro/',
		);

		return $theme_name;

	}
	public function fort_pro_theme_notice() {

		global $current_user;
		$this->current_user_data = $current_user;
		$this->active_theme      = wp_get_theme();

		

		$option = get_option( 'fort_theme_activate_start_time' );

		if ( ! $option ) {
			update_option( 'fort_theme_activate_start_time', time() );
		}

		add_action( 'admin_notices', array( $this, 'fort_pro_theme_notice_markup' ), 0 );
		add_action( 'admin_init', array( $this, 'fort_pro_theme_notice_partial_ignore' ), 0 );
		add_action( 'admin_init', array( $this, 'fort_pro_theme_notice_ignore' ), 0 );

	}
	public function enqueue_scripts() {

		wp_enqueue_style( 'fort-notice', get_template_directory_uri() . '/candidthemes/notice/admin-notice.css', array(), '4.5.0' );
	}
	public function fort_pro_theme_notice_markup() {

		$theme_name             = self::get_theme();
		$current_theme           = strtolower( $this->active_theme );
		$theme_notice_start_time = get_option( 'fort_theme_activate_start_time' );
		$buy_before_questions    = ( 'fort' !== $current_theme ) ? "https://www.candidthemes.com/contact/" : "https://www.candidthemes.com/contact/";
		$ignore_notice_permanent = get_user_meta( $this->current_user_data->ID, 'fort_nag_fort_pro_theme_notice_ignore', true );
		$ignore_notice_partially = get_user_meta( $this->current_user_data->ID, 'fort_nag_fort_pro_theme_notice_partial_ignore', true );

		if ( ! array_key_exists( $current_theme, $theme_name ) ) {
			return;
		}
		if ( ( $theme_notice_start_time > strtotime( '-15 days' ) ) || ( $ignore_notice_partially > strtotime( '-5 days' ) ) || ( $ignore_notice_permanent ) ) {
			return;
		}
		?>
		<div class="fort-admin-notice updated fort-pro-admin-notice">
			<p>
				<?php
				$pro_link = '<a target="_blank" href=" ' . esc_url( $theme_name[ $current_theme ] ) . ' ">' . esc_html__( 'unlock more features with pro theme', 'fort' ) . '</a>';

				printf(
					esc_html__(
						/* Translators: %1$s current user display name., %2$s Currently activated theme., %3$s Pro theme link., %4$s Coupon code. */
						'Howdy, %1$s! You\'ve been using %2$s theme for a while now, and we hope you\'re happy with it. If you are looking for the premium features, you can %3$s. Moreover, you can use the coupon code %4$s to get 20 percent discount. Enjoy!', 'fort'
					),
					'<strong>' . esc_html( $this->current_user_data->display_name ) . '</strong>',
					$this->active_theme,
					$pro_link,
					'<code>offer20</code>'
				);
				?>
			</p>

			<div class="links">
				<a href="<?php echo esc_url( $theme_name[ $current_theme ] ); ?>" class="btn button-primary"
				   target="_blank">
					<span class="dashicons dashicons-cart"></span>
					<span><?php esc_html_e( 'Unlock More Features', 'fort' ); ?></span>
				</a>

				<a href="?fort_nag_fort_pro_theme_notice_partial_ignore=1" class="btn button-secondary">
					<span class="dashicons dashicons-calendar-alt"></span>
					<span><?php esc_html_e( 'Remind later', 'fort' ); ?></span>
				</a>

				<a href="<?php echo esc_url( $buy_before_questions ); ?>"
				   class="btn button-secondary" target="_blank">
					<span class="dashicons dashicons-email-alt"></span>
					<span><?php esc_html_e( 'Contact Us', 'fort' ); ?></span>
				</a>
			</div>

			<a class="fort-pro-admin-notice-dismiss" href="?fort_nag_fort_pro_theme_notice_ignore=1"></a>
		</div>

		<?php
	}
	public function fort_pro_theme_notice_partial_ignore() {

		$user_id = $this->current_user_data->ID;

		if ( isset( $_GET['fort_nag_fort_pro_theme_notice_partial_ignore'] ) && '1' == $_GET['fort_nag_fort_pro_theme_notice_partial_ignore'] ) {
			update_user_meta( $user_id, 'fort_nag_fort_pro_theme_notice_partial_ignore', time() );
		}

	}
	public function fort_pro_theme_notice_ignore() {

		$user_id = $this->current_user_data->ID;

		if ( isset( $_GET['fort_nag_fort_pro_theme_notice_ignore'] ) && '1' == $_GET['fort_nag_fort_pro_theme_notice_ignore'] ) {
			update_user_meta( $user_id, 'fort_nag_fort_pro_theme_notice_ignore', time() );
		}

	}
}
new Fort_Theme_Notice();
