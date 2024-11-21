<?php
/**
 * Plugin Name:       Secure Email Login
 * Plugin URI:        https://github.com/sadekur/Secure-Email-Login
 * Description:       Secure Email Login is a plugin that allows users to log in using just their email without a password.
 * Version:           1.0.0
 * Requires at least: 5.9
 * Requires PHP:      7.4
 * Author:            Sadekur Rahman
 * Author URI:        https://github.com/sadekur/
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
	 * Class constructor
	 */
	private function __construct() {
		$this->define_constants();
		add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
	}

	/**
	 * Singleton instance
	 *
	 * @return Secure_Email_Login
	 */
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
		define( 'SECURE_EMAIL_LOGIN_VERSION', self::version );
		define( 'SECURE_EMAIL_LOGIN_FILE', __FILE__ );
		define( 'SECURE_EMAIL_LOGIN_PATH', __DIR__ );
		define( 'SECURE_EMAIL_LOGIN_URL', plugins_url( '', SECURE_EMAIL_LOGIN_FILE ) );
		define( 'SECURE_EMAIL_LOGIN_ASSETS', SECURE_EMAIL_LOGIN_URL . '/assets' );
	}

	/**
	 * Initialize the plugin
	 *
	 * @return void
	 */
	public function init_plugin() {
		new SecureEmailLogin\EmailLogin\Assets();
		new SecureEmailLogin\EmailLogin\Common();
		new SecureEmailLogin\EmailLogin\RestAPI();

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			new SecureEmailLogin\EmailLogin\Ajax();
		}

		if ( is_admin() ) {
			new SecureEmailLogin\EmailLogin\Admin();
		} else {
			new SecureEmailLogin\EmailLogin\Frontend();
		}
	}
}

function secure_email_login() {
	return Secure_Email_Login::init();
}

secure_email_login();
