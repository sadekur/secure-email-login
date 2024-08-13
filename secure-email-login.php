<?php
/**
 * Plugin Name:       Secure Email Login
 * Plugin URI:        https://wordpress.org/plugins/secure-email-login/
 * Description:       Secure Email Login is a a plugin that allows users to log in using just their email without a password.
 * Version:           1.0.0
 * Requires at least: 5.9
 * Requires PHP:      7.4
 * Author:            Sadekur Rahman
 * Author URI:        sadekurrahmansoikut.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       secure-email-login
 * Domain Path:       /languages
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class Secure_Email_Login {

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	const version = '1.0';

	/**
	 * Class construcotr
	 */
	private function __construct() {
		$this->define_constants();

		add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
	}

	public static function init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Define the required plugin constants
	 *
	 * @return void
	 */
	public function define_constants() {
		define( 'Secure_Email_Login_VERSION', self::version );
		define( 'Secure_Email_Login_FILE', __FILE__ );
		define( 'Secure_Email_Login_PATH', __DIR__ );
		define( 'Secure_Email_Login_URL', plugins_url( '', Secure_Email_Login_FILE ) );
		define( 'Secure_Email_Login_ASSETS', Secure_Email_Login_URL . '/assets' );
	}

	/**
	 * Initialize the plugin
	 *
	 * @return void
	 */
	public function init_plugin() {

		// new SecureEmailLogin\EmailLogin\Assets();
		// new SecureEmailLogin\EmailLogin\Email();
		// new SecureEmailLogin\EmailLogin\RestAPI();

		// if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		// 	new SecureEmailLogin\EmailLogin\Ajax();
		// }

		// if ( is_admin() ) {
		// 	new SecureEmailLogin\EmailLogin\Admin();
		// } else {
		// 	new SecureEmailLogin\EmailLogin\Frontend();
		// }

	}
}


function secure_email_login() {
	return Secure_Email_Login::init();
}

secure_email_login();