<?php

namespace SecureEmailLogin\EmailLogin;

class Common {

	function __construct() {
		add_filter( 'login_form', [ $this, 'custom_login_form_fields' ] );
		add_action( 'login_init', [ $this, 'handle_custom_login' ] );
		add_action('login_form', [ $this, 'verify_otp_login' ] );
	}

	// Custom login form fields: hides the password field with JS
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

public function handle_custom_login() {
    if (isset($_POST['log']) && !empty($_POST['log'])) {
        $user = get_user_by('email', sanitize_email($_POST['log']));
        if (!$user) {
            // Call the global WordPress function directly
            add_action('login_form', [$this, 'display_otp_fields']);
        }
    }
}

	// Display the OTP field
	public function display_otp_fields() {
		?>
		<p>
			<label for="user_name">Name<br />
			<input type="text" name="user_name" id="user_name" class="input" value="" size="20" /></label>
		</p>
		<p>
			<label for="user_otp">OTP<br />
			<input type="text" name="user_otp" id="user_otp" class="input" value="" size="20" /></label>
		</p>
		<?php
		$this->send_otp_to_email( sanitize_email( $_POST[ 'log' ] ) );
	}

	public function send_otp_to_email( $email ) {
		$otp = rand( 100000, 999999 );
		set_transient( 'otp_' . $email, $otp, 10 * MINUTE_IN_SECONDS );

		$subject = "Your OTP";
		$message = "Your OTP is: " . $otp;
		wp_mail( $email, $subject, $message );
	}

	public function verify_otp_login() {
		if (isset($_POST['user_otp'], $_POST['log'], $_POST['user_name']) && !empty($_POST['user_name'])) {
        $user_otp = $_POST['user_otp'];
        $email = $_POST['log'];
        $name = $_POST['user_name'];
        $stored_otp = get_transient('otp_' . $email);

        if ($user_otp == $stored_otp) {
            // OTP is valid, delete the transient
            delete_transient('otp_' . $email);

            // Check if user exists
            $user = get_user_by('email', $email);
            if (!$user) {
                // Create a new user if it doesn't exist
                $user_id = wp_create_user($email, wp_generate_password(), $email);
                // Set the user's display name
                wp_update_user(array(
                    'ID' => $user_id,
                    'display_name' => $name
                ));

                // Set role if necessary, default is 'subscriber'
                // $user_obj = new WP_User($user_id);
                // $user_obj->set_role('subscriber');

                // Log the user in
                wp_set_current_user($user_id, $email);
                wp_set_auth_cookie($user_id);
                do_action('wp_login', $email);

                // Redirect to the desired page or admin dashboard
                wp_redirect(admin_url());
                exit;
            } else {
                echo 'A user with this email already exists.';
            }
        } else {
            echo 'Invalid OTP.';
        }
    }
}
}
