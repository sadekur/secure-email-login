<?php

namespace SecureEmailLogin\EmailLogin;

class Common {
	function __construct() {
		add_action('login_form', [$this, 'custom_login_form_fields']);
		add_action('wp_footer', [$this, 'hidden_fields']);
		add_filter('authenticate', [$this, 'email_login_authenticate'], 20, 3);
	}

	public function custom_login_form_fields() {
		?>
		<script type="text/javascript">
			document.addEventListener("DOMContentLoaded", function() {
				var passwordField = document.getElementById('user_pass');
				if (passwordField) {
					passwordField.parentElement.removeChild(passwordField);
				}
			});
		</script>
		<?php
	}

	public function email_login_authenticate($user, $username, $password) {
		if (empty($username)) return;
	
		// Prevent WordPress from continuing with normal auth if this hook returns anything other than null.
		if (!is_a($user, 'WP_User')) {
			$user = get_user_by('email', $username);
			if (!$user) {
				return new \WP_Error('invalid_email', __('Invalid Email'));
			}
		}
	
		// If we're here, the user exists, let WordPress handle the rest (like setting cookies)
		return $user;
	}
	
	

	public function hidden_fields() {
		?>
		<form id="otpForm" style="display:none;">
			<input type="email" id="otpEmail" name="email" readonly>
			<input type="text" id="otpName" name="name" placeholder="Your Name">
			<input type="text" id="otp" name="otp" placeholder="Enter OTP">
			<button type="submit">Submit</button>
		</form>
		<?php
	}
}
