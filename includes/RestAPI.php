<?php
namespace SecureEmailLogin\EmailLogin;

class RestAPI {
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_email_login_routes']);
        add_action('rest_api_init', [$this, 'register_email_secureemaillogin']);
    }

    public function register_email_login_routes() {
        register_rest_route('secureemaillogin/v1', '/submit-email', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_email_submission'],
            'permission_callback' => '__return_true',
        ]);
    }

    public function register_email_secureemaillogin() {
        register_rest_route('secureemaillogin/v1', '/verify-otp', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_otp_verification'],
            'permission_callback' => '__return_true',
        ]);
    }

    public function handle_email_submission(\WP_REST_Request $request) {
        $email = $request->get_param('email');
        $user = get_user_by('email', $email);
        if ($user) {
            wp_clear_auth_cookie();
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);
            return new \WP_REST_Response(['userExists' => true], 200);
        } else {
            $otp = rand(100000, 999999);
            set_transient('otp_' . $email, $otp, 10 * MINUTE_IN_SECONDS);
            $subject = "Your Login OTP";
            $message = "Here is your OTP for login: " . $otp;
            $headers = ['Content-Type: text/html; charset=UTF-8'];
            wp_mail($email, $subject, $message, $headers);
            return new \WP_REST_Response(['userExists' => false], 200);
        }
    }

    public function handle_otp_verification(\WP_REST_Request $request) {
        $email = $request->get_param('email');
        $name = $request->get_param('name');
        $otp = $request->get_param('otp');
        $stored_otp = get_transient('otp_' . $email);
        if ($otp == $stored_otp) {
            $user_id = wp_create_user($email, wp_generate_password(), $email);
            wp_update_user(['ID' => $user_id, 'display_name' => $name]);
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
            delete_transient('otp_' . $email);
            return new \WP_REST_Response(['success' => true], 200);
        } else {
            return new \WP_REST_Response(['success' => false, 'message' => 'Invalid OTP'], 403);
        }
    }
}