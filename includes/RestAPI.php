<?php
namespace SecureEmailLogin\EmailLogin;

class RestAPI {
	public function __construct() {
		add_action( 'rest_api_init', [ $this,'register_email_login_routes' ] );
	}

	public function register_email_login_routes() {
		register_rest_route('email-login/v1', '/send-otp/', array(
			'methods' => 'POST',
			'callback' => 'send_otp_to_email',
			'permission_callback' => '__return_true'
		));

		register_rest_route('email-login/v1', '/verify-otp/', array(
			'methods' => 'POST',
			'callback' => 'verify_otp_login',
			'permission_callback' => '__return_true'
		));
	}
}