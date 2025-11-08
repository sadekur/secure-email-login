<?php
// Exit if accessed directly
if ( !defined('ABSPATH' ) ) {
	exit;
}

function pll_otp_transient_key( $email ) {
	return 'password_less_login_otp_' . md5( strtolower( trim( $email ) ) );
}

function pll_otp_attempts_key( $email ) {
	return 'password_less_login_otp_attempts_' . md5( strtolower( trim( $email ) ) );
}