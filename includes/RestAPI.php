<?php
namespace PasswordLess\Login;

class RestAPI {
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_email_login' ] );
        add_action( 'rest_api_init', [ $this, 'email_verification' ] );
    }

    public function register_email_login() {
        register_rest_route('password-less-login/v1', '/submit-email', [
            'methods'               => 'POST',
            'callback'              => [$this, 'handle_email_submission'],
            'permission_callback'   => function() {
                return !is_user_logged_in() || current_user_can( 'edit_posts' );
            },
        ]);
    }

    public function email_verification() {
        register_rest_route('password-less-login/v1', '/verify-otp', [
            'methods'               => 'POST',
            'callback'              => [$this, 'handle_otp_verification'],
            'permission_callback'   => function() {
                return !is_user_logged_in() || current_user_can( 'edit_posts' );
            },
        ]);
    }

    /**
     * Submit email: always send OTP (do NOT log user in here).
     */
    public function handle_email_submission( \WP_REST_Request $request ) {
        $response = [
            'status'  => 0,
            'message' => __( 'Something is wrong!', 'password-less-login' ),
        ];

        $nonce = sanitize_text_field( $request->get_param( 'nonce' ) );
        if ( ! wp_verify_nonce( $nonce, 'password-less-login' ) ) {
            $response['message'] = __( 'Unauthorized!', 'password-less-login' );
            return new \WP_REST_Response( $response, 403 );
        }

        $email = sanitize_email( $request->get_param( 'email' ) );
        if ( ! is_email( $email ) ) {
            $response['message'] = __( 'Invalid email address.', 'password-less-login' );
            return new \WP_REST_Response( $response, 400 );
        }

        // Throttle OTP requests per email (5 per 15 minutes)
        $attempts_key = pll_otp_attempts_key( $email );
        $attempts     = (int) get_transient( $attempts_key );
        if ( $attempts >= 5 ) {
            $response['message'] = __( 'Too many requests. Please try again later.', 'password-less-login' );
            return new \WP_REST_Response( $response, 429 );
        }
        set_transient( $attempts_key, $attempts + 1, 15 * MINUTE_IN_SECONDS );

        $user       = get_user_by( 'email', $email );
        $userExists = (bool) $user;

        // Generate OTP (6-digit) and store it transiently (10 minutes)
        $otp     = (string) wp_rand( 100000, 999999 );
        $otp_key = pll_otp_transient_key( $email );

        set_transient( $otp_key, $otp, 10 * MINUTE_IN_SECONDS );

        $subject = __( 'Your Login OTP', 'password-less-login' );

        $message = sprintf(
            __( 'Your OTP for login/registration is: %s. It expires in 10 minutes.', 'password-less-login' ),
            esc_html( $otp )
        );

        $headers = [ 'Content-Type: text/html; charset=UTF-8' ];
        wp_mail( $email, $subject, wpautop( $message ), $headers );

        return new \WP_REST_Response(
            [
                'userExists' => $userExists,
                'message'    => __( 'OTP sent to your email if the address is valid.', 'password-less-login' ),
            ],
            200
        );
    }
    /**
     * Verify OTP and log in or register user.
     */
    public function handle_otp_verification( \WP_REST_Request $request ) {
        $response = [
            'success' => false,
            'message' => __( 'Something is wrong!', 'password-less-login' ),
        ];

        $nonce = sanitize_text_field( $request->get_param( 'nonce' ) );
        if ( ! wp_verify_nonce( $nonce, 'password-less-login' ) ) {
            $response['message'] = __( 'Unauthorized!', 'password-less-login' );
            return new \WP_REST_Response( $response, 403 );
        }

        $email = sanitize_email( $request->get_param( 'email' ) );
        if ( ! is_email( $email ) ) {
            $response['message'] = __( 'Invalid email address.', 'password-less-login' );
            return new \WP_REST_Response( $response, 400 );
        }

        $otp = sanitize_text_field( $request->get_param( 'otp' ) );
        $name = sanitize_text_field( $request->get_param( 'name' ) );

        $attempts_key = pll_otp_attempts_key( $email ) . '_verify';
        $attempts = (int) get_transient( $attempts_key );
        if ( $attempts >= 5 ) {
            $response['message'] = __( 'Too many verification attempts. Try again later.', 'password-less-login' );
            return new \WP_REST_Response( $response, 429 );
        }
        set_transient( $attempts_key, $attempts + 1, 15 * MINUTE_IN_SECONDS );

        $otp_key = pll_otp_transient_key( $email );
        $stored_otp = get_transient( $otp_key );

        if ( empty( $stored_otp ) || (string) $stored_otp !== (string) $otp ) {
            $response['message'] = __( 'Invalid or expired OTP.', 'password-less-login' );
            return new \WP_REST_Response( $response, 403 );
        }

        delete_transient( $otp_key );
        delete_transient( pll_otp_attempts_key( $email ) );
        delete_transient( $attempts_key );

        $user = get_user_by( 'email', $email );
        if ( $user ) {
            wp_clear_auth_cookie();
            wp_set_current_user( $user->ID );
            wp_set_auth_cookie( $user->ID );

            return new \WP_REST_Response( [
                'success' => true,
                'message' => __( 'Login successful.', 'password-less-login' ),
                'user_id' => $user->ID,
            ], 200 );
        }

        $username_base = sanitize_user( ( $name ? $name : current( explode( '@', $email, 2 ) ) ), true );
        if ( empty( $username_base ) ) {
            $username_base = 'user_' . wp_generate_password( 6, false );
        }
        $username = $username_base;
        $suffix = 1;
        while ( username_exists( $username ) ) {
            $username = $username_base . $suffix;
            $suffix++;
        }

        $password = wp_generate_password( 20, true );
        $user_id = wp_create_user( $username, $password, $email );
        if ( is_wp_error( $user_id ) ) {
            $response['message'] = __( 'User creation failed.', 'password-less-login' );
            return new \WP_REST_Response( $response, 500 );
        }
        wp_update_user( [
            'ID'           => $user_id,
            'role'         => 'subscriber',
            'display_name' => $name ? $name : $username,
        ] );

        // Log in
        wp_clear_auth_cookie();
        wp_set_current_user( $user_id );
        wp_set_auth_cookie( $user_id );

        return new \WP_REST_Response( [
            'success' => true,
            'message' => __( 'Account created and logged in.', 'password-less-login' ),
            'user_id' => $user_id,
        ], 200 );
    }       
}