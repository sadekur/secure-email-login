<?php

namespace PasswordLess\Login;

class Assets {

	public $plugin;
	public $slug;
	public $name;
	public $version;
	public $assets;

	function __construct() {
		// $this->plugin	= $plugin;
		// $this->slug		= $this->plugin['TextDomain'];
		// $this->name		= $this->plugin['Name'];
		// $this->version	= $this->plugin['Version'];
		// $this->assets 	= SECURE_EMAIL_LOGIN_ASSETS;

		add_action( 'login_enqueue_scripts', [ $this, 'register_login_assets' ] );
		// add_action('admin_enqueue_scripts', [$this, 'register_admin_assets']);
	}

	public function get_scripts() {
		return [
			'email-common-script' => [
				'src'     => SECURE_EMAIL_LOGIN_ASSETS . '/js/common.js',
				'version' => filemtime(SECURE_EMAIL_LOGIN_PATH . '/assets/js/common.js'),
				'deps'    => ['jquery']
			],
		];
	}

	public function get_styles() {
		return [
			'email-login-style' => [
				'src'     => SECURE_EMAIL_LOGIN_ASSETS . '/css/login-style.css',
				'version' => filemtime( SECURE_EMAIL_LOGIN_PATH . '/assets/css/login-style.css' )
			],
		];
	}

	public function register_login_assets() {
		$scripts 	= $this->get_scripts();
		$styles 	= $this->get_styles();

		wp_register_script( 'email-common-script', $scripts[ 'email-common-script' ][ 'src' ], $scripts[ 'email-common-script' ][ 'deps' ], $scripts[ 'email-common-script' ][ 'version' ], true );


		wp_localize_script( 'email-common-script', 'PASSWORDLESSLOGIN', [
			'ajaxurl' 		=> admin_url( 'admin-ajax.php'),
			'pastemailresturl' 	=> rest_url( "password-less-login/v1/submit-email" ),
			'otpresturl' 	=> rest_url( "password-less-login/v1/verify-otp" ),
			'nonce'  		=> wp_create_nonce( 'password-less-login' ),
			'error'   		=> __( 'Something went wrong', 'password-less-login' ),
			'adminUrl'     	=> admin_url(),
		]);

		wp_register_style( 'email-login-style', $styles[ 'email-login-style' ][ 'src' ], [], $styles[ 'email-login-style' ]['version' ] );

		wp_enqueue_script( 'email-common-script' );
		wp_enqueue_style( 'email-login-style' );
	}
}
