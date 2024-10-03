<?php
/**
 * License and live update implementation.
 *
 * @package WP Story Premium
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

$prefix = 'wp-story-premium-generator';

CSF::createOptions(
	$prefix,
	array(
		'menu_title'         => esc_html__( 'License', 'wp-story-premium' ),
		'menu_slug'          => 'wp-story-premium-license',
		'menu_type'          => 'submenu',
		'menu_parent'        => 'edit.php?post_type=wp-story',
		'framework_title'    => esc_html__( 'License', 'wp-story-premium' ),
		'footer_text'        => '<a href="https://codecanyon.net/user/wpuzman/" target="_blank">wpuzman</a>',
		'theme'              => 'light',
		'footer_credit'      => '<a href="mailto:wpuzmann@gmail.com">wpuzmann@gmail.com</a>',
		'show_in_customizer' => false,
		'show_bar_menu'      => false,
		'database'           => 'option',
		'data_type'          => 'unserialize',
	)
);

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__( 'License', 'wp-story-premium' ),
		'icon'   => 'fas fa-cog',
		'fields' => array(
			array(
				'type'        => 'text',
				'id'          => 'wp-story-premium-purchase-code',
				'title'       => esc_html__( 'Purchase Code', 'wp-story-premium' ),
				'subtitle'    => esc_html__( 'Enter your license code to enable auto updates and support.', 'wp-story-premium' ),
				'placeholder' => esc_html__( '(e.g. 9g2b13fa-10aa-2267-883a-9201a94cf9b5)', 'wp-story-premium' ),
				'validate'    => 'wpstory_purchase_code_cb',
				'class'       => 'wpstory-box-generator-field',
			),
		),
	)
);

/**
 * Class Wpstory_Premium_Updater
 */
class Wpstory_Premium_Updater {
	/**
	 * Plugin basename
	 *
	 * @var string Basename.
	 */
	private $basename = '';

	/**
	 * Wpstory_Premium_Updater constructor.
	 *
	 * @param string $basename Plugin basename.
	 */
	public function __construct( $basename ) {
		$this->basename = $basename;

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_version' ) );
		add_filter( 'plugins_api', array( $this, 'shortcircuit_plugins_api_to_org' ), 10, 3 );
		add_action( 'install_plugins_pre_plugin-information', array( $this, 'plugin_update_popup' ) );
		add_filter( 'wupdates_gather_ids', array( $this, 'add_details' ), 10, 1 );
	}

	/**
	 * Check plugin version.
	 *
	 * @param string $transient Current version transient.
	 * @return mixed
	 */
	public function check_version( $transient ) {
		global $wp_version;

		if ( empty( $transient->checked ) || empty( $transient->checked[ $this->basename ] ) || ! empty( $transient->response[ $this->basename ] ) || ! empty( $transient->no_update[ $this->basename ] ) ) {
			return $transient;
		}

		$slug = dirname( $this->basename );

		include ABSPATH . WPINC . '/version.php';
		$http_args = array(
			'body'       => array(
				'slug'    => $slug,
				'plugin'  => $this->basename,
				'url'     => home_url( '/' ),
				'version' => 0,
				'locale'  => get_locale(),
				'phpv'    => phpversion(),
				'data'    => null,
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url( '/' ),
		);

		if ( ! empty( $transient->checked[ $this->basename ] ) ) {
			$http_args['body']['version'] = $transient->checked[ $this->basename ];
		}

		$optional_data = apply_filters( 'wupdates_call_data_request', $http_args['body']['data'], $slug, $http_args['body']['version'] );

		$optional_data = wp_json_encode( $optional_data );
		$w             = array();
		$re            = '';
		$s             = array();
		$sa            = md5( '15b2ca68320c4e9e3f4702c95c46580e614234cb' );
		$l             = strlen( $sa );
		$d             = $optional_data;
		$ii            = -1;
		while ( ++$ii < 256 ) {
			$w[ $ii ] = ord( substr( $sa, ( ( $ii % $l ) + 1 ), 1 ) );
			$s[ $ii ] = $ii;
		}
		$ii = -1;
		$j  = 0;
		while ( ++$ii < 256 ) {
			$j        = ( $j + $w[ $ii ] + $s[ $ii ] ) % 255;
			$t        = $s[ $j ];
			$s[ $ii ] = $s[ $j ];
			$s[ $j ]  = $t;
		}
		$l  = strlen( $d );
		$ii = -1;
		$j  = 0;
		$k  = 0;
		while ( ++$ii < $l ) {
			$j       = ( ++$j ) % 256;
			$k       = ( $k + $s[ $j ] ) % 255;
			$t       = $w[ $j ];
			$s[ $j ] = $s[ $k ];
			$s[ $k ] = $t;
			$x       = $s[ ( ( $s[ $j ] + $s[ $k ] ) % 255 ) ];

			$re .= chr( ord( $d[ $ii ] ) ^ $x );
		}
		$optional_data = bin2hex( $re );

		$http_args['body']['data'] = $optional_data;

		$url      = set_url_scheme( 'https://wupdates.com/wp-json/wup/v1/plugins/check_version/v4g36', 'http' );
		$http_url = $url;
		$ssl      = wp_http_supports( array( 'ssl' ) );

		if ( $ssl ) {
			$url = set_url_scheme( $url, 'https' );
		}

		$raw_response = wp_remote_post( $url, $http_args );
		if ( $ssl && is_wp_error( $raw_response ) ) {
			$raw_response = wp_remote_post( $http_url, $http_args );
		}

		if ( is_wp_error( $raw_response ) || 200 !== wp_remote_retrieve_response_code( $raw_response ) ) {
			return $transient;
		}

		$response = (array) json_decode( $raw_response['body'] );
		if ( ! empty( $response ) ) {
			do_action( 'wupdates_before_response', $response, $transient );
			if ( isset( $response['allow_update'] ) && $response['allow_update'] && isset( $response['transient'] ) ) {
				$transient->response[ $this->basename ] = (object) $response['transient'];
			} else {
				$transient->no_update[ $this->basename ] = (object) array(
					'slug'        => $slug,
					'plugin'      => $this->basename,
					'new_version' => ! empty( $response['version'] ) ? $response['version'] : '0.0.1',
				);
			}
			do_action( 'wupdates_after_response', $response, $transient );
		}

		return $transient;
	}

	/**
	 * Add plugin details.
	 *
	 * @param array $ids Plugin id.
	 * @return array|mixed
	 */
	public function add_details( $ids = array() ) {
		$ids[ $this->basename ] = array(
			'name'   => 'WP Story Premium',
			'slug'   => 'wp-story-premium',
			'id'     => 'v4g36',
			'type'   => 'plugin',
			'digest' => 'f14627402c18b068d3216cfc80026820',
		);

		return $ids;
	}

	/**
	 * Shortcircuit.
	 *
	 * @param string $res Response.
	 * @param string $action Action.
	 * @param array  $args Arguments.
	 * @return stdClass
	 */
	public function shortcircuit_plugins_api_to_org( $res, $action, $args ) {
		if ( 'plugin_information' !== $action || empty( $args->slug ) || 'wp-story-premium' !== $args->slug ) {
			return $res;
		}

		$screen = get_current_screen();

		if ( empty( $screen->id ) || ( 'update-core' !== $screen->id && 'update-core-network' !== $screen->id ) ) {
			return $res;
		}

		$res       = new stdClass();
		$transient = get_site_transient( 'update_plugins' );
		if ( isset( $transient->response[ $this->basename ]->tested ) ) {
			$res->tested = $transient->response[ $this->basename ]->tested;
		} else {
			$res->tested = false;
		}

		return $res;
	}

	/**
	 * Update popup.
	 */
	public function plugin_update_popup() {
		$slug = sanitize_key( $_GET['plugin'] ); // phpcs:ignore

		if ( 'wp-story-premium' !== $slug ) {
			return;
		}

		$error_msg = '<p>' . esc_html__( 'Could not retrieve version details. Please try again.', 'wp-story-premium' ) . '</p>';

		$transient = get_site_transient( 'update_plugins' );

		if ( empty( $transient->response[ $this->basename ]->url ) ) {
			echo $error_msg; // phpcs:ignore WordPress.Security.EscapeOutput
			exit;
		}

		$response = wp_remote_get( $transient->response[ $this->basename ]->url );
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			echo $error_msg; // phpcs:ignore WordPress.Security.EscapeOutput
			exit;
		}

		$data = wp_remote_retrieve_body( $response );

		if ( is_wp_error( $data ) || empty( $data ) ) {
			echo $error_msg; // phpcs:ignore WordPress.Security.EscapeOutput
		} else {
			echo $data; // phpcs:ignore WordPress.Security.EscapeOutput
		}

		exit;
	}
}

$plugin_updates = new Wpstory_Premium_Updater( 'wp-story-premium/wp-story-premium.php' );

/**
 * Check is license code has valid template.
 *
 * @param string $purchase_code From options $_POST[] value.
 * @return bool
 * @since 1.1.0
 */
function wpstory_is_valid_purchase_code( $purchase_code ) {
	$purchase_code = preg_replace( '#([a-z0-9]{8})-?([a-z0-9]{4})-?([a-z0-9]{4})-?([a-z0-9]{4})-?([a-z0-9]{12})#', '$1-$2-$3-$4-$5', strtolower( $purchase_code ) );
	if ( 36 === strlen( $purchase_code ) ) {
		return true;
	}
	return false;
}

/**
 * Validate license code.
 *
 * @param string $val Purchase code rom theme license page.
 * @return string Validation message.
 * @since 1.1.0
 */
function wpstory_purchase_code_cb( $val ) {
	$purchase_code = sanitize_text_field( $val );

	if ( empty( $purchase_code ) ) {
		return esc_html__( 'Please enter a purchase code.', 'wp-story-premium' );
	}

	if ( ! wpstory_is_valid_purchase_code( $val ) ) {
		return esc_html__( 'Please enter a valid purchase code.', 'wp-story-premium' );
	}

	$http_args = array(
		'body' => array(
			'slug'          => 'wp-story-premium',
			'url'           => home_url(),
			'purchase_code' => $purchase_code,
		),
	);

	$url      = set_url_scheme( 'https://wupdates.com/wp-json/wup/v1/front/check_envato_purchase_code/v4g36', 'http' );
	$http_url = $url;
	$ssl      = wp_http_supports( array( 'ssl' ) );

	if ( $ssl ) {
		$url = set_url_scheme( $url, 'https' );
	}

	$raw_response = wp_remote_post( $url, $http_args );

	if ( $ssl && is_wp_error( $raw_response ) ) {
		$raw_response = wp_remote_post( $http_url, $http_args );
	}

	if ( is_wp_error( $raw_response ) || 200 !== wp_remote_retrieve_response_code( $raw_response ) ) {
		return esc_html__( 'We are sorry but we couldn\'t connect to the verification server. Please try again later.', 'wp-story-premium' );
	} else {
		$response = json_decode( $raw_response['body'], true );
		if ( ! empty( $response ) ) {
			if ( isset( $response['purchase_code'] ) && 'valid' === $response['purchase_code'] ) {
				update_option( strtolower( 'wp-story-premium-purchase-code' ), $purchase_code );
				$notices            = get_option( 'wpstory_premium_notices' );
				$notices['license'] = array( 'version' => WPSTORY_LICENSE_NOTICE_VERSION );
				update_option( 'wpstory_premium_notices', $notices );
				set_site_transient( 'update_themes', null );
			} else {
				if ( isset( $response['reason'] ) && ! empty( $response['reason'] ) && 'out_of_support' === $response['reason'] ) {
					return esc_html__( 'Your purchase\'s support period has ended. Please extend it to receive automatic updates.', 'wp-story-premium' );
				} else {
					return esc_html__( 'Could not find a sale with this purchase code. Please double check.', 'wp-story-premium' );
				}
			}
		}
	}
}

/**
 * Append license code for sending data.
 *
 * @param array  $optional_data Sending data for validation.
 * @param string $slug Plugin slug.
 * @return array|null
 * @since 1.1.0
 */
function wpstory_send_purchase_code( $optional_data, $slug ) {
	$purchase_code = sanitize_text_field( get_option( 'wp-story-premium-purchase-code' ) );

	if ( null === $optional_data ) {
		$optional_data = array();
	}

	$optional_data['envato_purchase_code'] = $purchase_code;

	return $optional_data;
}

add_filter( 'wupdates_call_data_request', 'wpstory_send_purchase_code', 10, 2 );

/**
 * Display license notice.
 * It will be hidden if license value is valid on database or clicked dismiss button.
 *
 * @since 1.1.0
 */
function wpstory_license_notice() {
	$buy_now_t = '<span class="dashicons dashicons-cart"></span>' . esc_html__( 'Buy Now', 'wp-story-premium' );
	$buy_now_l = '<a class="button" href="https://codecanyon.net/checkout/from_item/27546341?license=regular&size=source&support=bundle_12month&ref=wp-admin" target="_blank">' . $buy_now_t . '</a>';
	$code      = get_option( 'wp-story-premium-purchase-code' );
	$notices   = get_option( 'wpstory_premium_notices' );

	if ( ! empty( $code ) ) {
		return;
	}

	$notice = $notices['license'] ?? null;
	$ver    = $notice['version'] ?? null;

	if ( $ver === WPSTORY_LICENSE_NOTICE_VERSION ) {
		return;
	}
	?>
	<div class="notice notice-warning wpstory-notice wpstory-notice--license">
		<h3><?php esc_html_e( 'WP Story Premium', 'wp-story-premium' ); ?></h3>
		<p><strong style="margin-right: 5px;"><?php esc_html_e( 'Get new features and support.', 'wp-story-premium' ); ?></strong><?php echo $buy_now_l; ?></p>
		<p><?php esc_html_e( 'Please activate "Wp Story Premium" for getting updates and support!', 'wp-story-premium' ); ?></p>
		<p>
			<a href="<?php echo esc_url( 'edit.php?post_type=wp-story&page=wp-story-premium-license' ); ?>"><?php esc_html_e( 'Activate', 'wp-story-premium' ); ?></a>
			<a href="?wpstory-dismissed" class="wpstory-notice-dismiss" data-type="license"><?php esc_html_e( 'Dismiss', 'wp-story-premium' ); ?></a>
		</p>
		<a href="?wpstory-dismissed" class="notice-dismiss wpstory-notice-dismiss" data-type="license"></a>
	</div>
	<?php
}

add_action( 'admin_notices', 'wpstory_license_notice' );

/**
 * Hide license notice.
 * It adds "wp-story-premium-license-notice" value to options table.
 * If this value is true, license notice doesn't display.
 * It checks $_GET[] value and redirects referer url.
 *
 * @since 1.1.0
 */
function wpstory_dismiss_license_notice() {
	if ( isset( $_GET['wpstory-dismissed'] ) ) { // phpcs:ignore
		if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
			$notices            = get_option( 'wpstory_premium_notices' );
			$notices['license'] = array( 'version' => WPSTORY_LICENSE_NOTICE_VERSION );
			update_option( 'wpstory_premium_notices', $notices );
			wp_redirect( $_SERVER['HTTP_REFERER'] ); // phpcs:ignore
			exit();
		}
	}
}

add_action( 'admin_init', 'wpstory_dismiss_license_notice' );
