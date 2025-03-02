<?php

namespace SecureEmailLogin\EmailLogin;

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


		wp_localize_script( 'email-common-script', 'EMAILLOGIN', [
			'ajaxurl' 		=> admin_url( 'admin-ajax.php'),
			'emailresturl' 	=> rest_url( "secureemaillogin/v1/submit-email" ),
			'otpresturl' 	=> rest_url( "secureemaillogin/v1/verify-otp" ),
			'nonce'  		=> wp_create_nonce( 'secureemaillogin'),
			'error'   		=> __( 'Something went wrong', 'secure-email-login' ),
			'adminUrl'     	=> admin_url(),
		]);

		wp_register_style( 'email-login-style', $styles[ 'email-login-style' ][ 'src' ], [], $styles[ 'email-login-style' ]['version' ] );

		wp_enqueue_script( 'email-common-script' );
		wp_enqueue_style( 'email-login-style' );
	}

	// public function register_admin_assets() {
	// 	$scripts 	= $this->get_scripts();
	// 	$styles 	= $this->get_styles();

	// 	wp_register_script( 'email-login-admin-script', $scripts[ 'email-login-admin-script' ][ 'src' ], $scripts['email-login-admin-script' ][ 'deps' ], $scripts[ 'email-login-admin-script' ][ 'version' ], true );
		
	// 	wp_localize_script( 'email-login-admin-script', 'email-login', [
	// 		'nonce'   => wp_create_nonce( 'nonce' ),
	// 		'confirm' => __( 'Are you sure?', 'email-login-crm' ),
	// 		'ajaxurl' => admin_url( 'admin-ajax.php' ),
	// 		'error'   => __( 'Something went wrong', 'email-login-crm' )
	// 	]);

	// 	wp_register_style( 'email-login-admin-style', $styles[ 'email-login-admin-style' ][ 'src' ], [], $styles['email-login-admin-style' ][ 'version' ]);
	// 	wp_register_style( 'jquery-ui', $styles[ 'jquery-ui' ][ 'src' ], [], $styles[ 'jquery-ui' ][ 'version' ] );

	// 	wp_enqueue_script( 'email-login-admin-script' );
	// 	wp_enqueue_style( 'email-login-admin-style' );
	// 	wp_enqueue_style( 'jquery-ui' );
	// }
}
