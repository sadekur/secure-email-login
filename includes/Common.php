<?php

namespace SecureEmailLogin\EmailLogin;

class Common {
	function __construct() {
		add_action( 'login_form', [ $this, 'custom_login_form_fields' ] );
		add_action( 'login_footer', [ $this, 'hidden_fields' ] );
		add_filter( 'authenticate', [ $this, 'email_login_authenticate' ], 20, 3 );
	}

	public function custom_login_form_fields() {
		?>
		<script type="text/javascript">
			document.addEventListener("DOMContentLoaded", function() {
				var loginDiv = document.getElementById('login');
				if (loginDiv) {
					loginDiv.classList.add('secure-email-login-form');
				}
				var passwordField = document.getElementById('user_pass');
				if (passwordField) {
					passwordField.parentElement.style.display = 'none';
				}
			});
		</script>
		<input type="hidden" name="otp_login" value="1">
		<?php
	}
	public function email_login_authenticate( $user, $username, $password ) {
		if ( empty($username ) ) return;
		if ( !is_a( $user, 'WP_User' ) ) {
			$user = get_user_by( 'email', $username );
			if (!$user) {
				return new \WP_Error( 'invalid_email', __( 'Invalid Email' ) );
			}
		}
		return $user;
	}

	public function hidden_fields() {
		?>
		<div class="secure-email-login-hidden-form">
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
}
