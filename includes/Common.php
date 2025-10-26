<?php

namespace PasswordLess\Login;

class Common {
	function __construct() {
		add_action( 'login_form', [ $this, 'custom_login_form_fields' ] );
		add_action( 'login_footer', [ $this, 'hidden_fields' ] );
		add_filter( 'authenticate', [ $this, 'email_login_authenticate' ], 20, 3 );
		add_action( 'login_footer', [ $this, 'optin_footer' ] );
	}

	public function custom_login_form_fields() {
		?>
			<input type="hidden" name="otp_login" value="1">
		<?php
	}
	public function email_login_authenticate( $user, $username, $password ) {
		if ( empty( $username ) ) return;
		if ( !is_a( $user, 'WP_User' ) ) {
			$user = get_user_by( 'email', $username );
			if (!$user) {
				return new \WP_Error( 
					'invalid_email', 
					__( 'Invalid Email', 'password-less-login' )
				);
			}
		}
		return $user;
	}

	public function hidden_fields() {
		?>
		<div class="password-less-login-hidden-form">
			<div class="login-logo">
				<a href="https://wordpress.org/" tabindex="-1">
					<img src="<?php echo esc_url( includes_url( 'images/w-logo-blue.png' ) ); ?>" alt="WordPress">
				</a>
			</div>
			<form id="otpForm" class="otp-form">
				<input type="email" id="otpEmail" name="email" readonly>
				<input type="text" id="otpName" name="name" placeholder="Your Name">
				<input type="text" id="otp" name="otp" placeholder="Enter OTP">
				<button type="submit">Submit</button>
			</form>
		</div>
		<?php
	}
	public function optin_footer() {
		echo '<div class="loader-container" id="formLoader" style="display: none;">' .
		'<img src="' . esc_url( SECURE_EMAIL_LOGIN_ASSETS . '/img/loader.gif' ) . '" alt="' . esc_attr( 'Loading...' ) . '">' .
		'</div>';
	}
}
