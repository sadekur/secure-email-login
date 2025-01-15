<?php
namespace SecureEmailLogin\EmailLogin;

class RestAPI {
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'sel_register_email_login_routes' ] );
        add_action( 'rest_api_init', [ $this, 'sel_register_email_secureemaillogin' ] );
    }

    public function sel_register_email_login_routes() {
        register_rest_route('secureemaillogin/v1', '/submit-email', [
            'methods'               => 'POST',
            'callback'              => [$this, 'sel_handle_email_submission'],
            'permission_callback'   => '__return_true',
        ]);
    }

    public function sel_register_email_secureemaillogin() {
        register_rest_route('secureemaillogin/v1', '/verify-otp', [
            'methods'               => 'POST',
            'callback'              => [$this, 'sel_handle_otp_verification'],
            'permission_callback'   => '__return_true',
        ]);
    }

    public function sel_handle_email_submission( \WP_REST_Request $request ) {
        $response[ 'status' ]     = 0;
        $response[ 'message' ]    = __( 'Something is wrong!', 'secure-email-login' );
        $nonce = sanitize_text_field( $request->get_param( 'nonce' ) );

        if ( ! wp_verify_nonce( $nonce, 'secureemaillogin' ) ) {
            $response[ 'message' ] = __( 'Unauthorized!', 'secure-email-login' );
            return new \WP_REST_Response( $response, 403 );
        }

        $email = sanitize_email( $request->get_param( 'email' ) );
        if ( ! is_email( $email ) ) {
            $response['message'] = __( 'Invalid email address.', 'secure-email-login' );
            return new \WP_REST_Response( $response, 400 );
        }

        $user   = get_user_by( 'email', $email );
        if ( $user ) {
            wp_clear_auth_cookie();
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);
            return new \WP_REST_Response( [ 'userExists' => true ], 200 );
        } else {
            $otp = rand( 100000, 999999 );
            set_transient( 'secure_email_login_otp_' . $email, $otp, 10 * MINUTE_IN_SECONDS );
            $subject = __( 'Your Login OTP', 'secure-email-login' );
            $message = sprintf( __( 'Here is your OTP for login: %s', 'secure-email-login' ), $otp );
            $headers = [ 'Content-Type: text/html; charset=UTF-8' ];
            wp_mail( $email, $subject, $message, $headers );
            return new \WP_REST_Response ([ 'userExists' => false ], 200 );
        }

        $response['status']     = 1;
        $response['message']    = __( 'Synchronization Complete', 'secure-email-login' );
        wp_send_json( $response );
    }

    public function sel_handle_otp_verification( \WP_REST_Request $request ) {
        $response = [
            'success' => false,
            'message' => __( 'Something is wrong!', 'secure-email-login' ),
        ];
    
        // Sanitize and validate the nonce
        $nonce = sanitize_text_field( $request->get_param( 'nonce' ) );
        if ( ! wp_verify_nonce( $nonce, 'secureemaillogin' ) ) {
            $response['message'] = __( 'Unauthorized!', 'secure-email-login' );
            return new \WP_REST_Response( $response, 403 );
        }
    
        // Sanitize and validate email
        $email = sanitize_email( $request->get_param( 'email' ) );
        if ( ! is_email( $email ) ) {
            $response['message'] = __( 'Invalid email address.', 'secure-email-login' );
            return new \WP_REST_Response( $response, 400 );
        }
    
        // Sanitize name and OTP
        $name = sanitize_text_field( $request->get_param( 'name' ) );
        $otp = sanitize_text_field( $request->get_param( 'otp' ) );
    
        // Validate OTP
        $stored_otp = get_transient( 'secure_email_login_otp_' . $email );
        if ( $otp !== $stored_otp ) {
            $response['message'] = __( 'Invalid OTP.', 'secure-email-login' );
            return new \WP_REST_Response( $response, 403 );
        }
    
        // Process user creation and authentication
        $user_id = wp_create_user( $email, wp_generate_password(), $email );
        if ( is_wp_error( $user_id ) ) {
            $response['message'] = __( 'User creation failed.', 'secure-email-login' );
            return new \WP_REST_Response( $response, 500 );
        }
    
        // Update user role to 'Editor'
        wp_update_user( [
            'ID'    => $user_id,
            'role'  => 'editor',
        ] );
    
        // Update user details
        wp_update_user( [
            'ID'           => $user_id,
            'display_name' => $name,
        ] );
    
        // Authenticate and log in the user
        wp_set_current_user( $user_id );
        wp_set_auth_cookie( $user_id );
    
        // Remove the used OTP
        delete_transient( 'secure_email_login_otp_' . $email );
    
        // Success response
        return new \WP_REST_Response( [ 
            'success' => true, 
            'message' => __( 'User authenticated successfully.', 'secure-email-login' ), 
        ], 200 );
    }       
}