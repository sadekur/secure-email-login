<?php
/**
 * Plugin Name:       Password Less Login
 * Plugin URI:        https://github.com/sadekur/password-less-login
 * Description:       This is a plugin that allows users to log in using just their email without a password.
 * Version:           1.0.0
 * Requires at least: 5.9
 * Requires PHP:      7.4
 * Author:            Sadekur Rahman
 * Author URI:        https://github.com/sadekur/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       password-less-login
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class Password_Less_Login {

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
	 * @return Password_Less_Login
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
		new PasswordLess\Login\Assets();
		new PasswordLess\Login\Common();
		new PasswordLess\Login\RestAPI();

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			// new PasswordLess\Login\Ajax();
		}

		if ( is_admin() ) {
			new PasswordLess\Login\Admin();
		} else {
			new PasswordLess\Login\Frontend();
		}
	}
}

function password_less_login() {
	return Password_Less_Login::init();
}

password_less_login();
