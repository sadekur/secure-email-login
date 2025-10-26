<?php
// Exit if accessed directly
if ( !defined('ABSPATH' ) ) {
	exit;
}

function sel_otp_transient_key( $email ) {
	return 'secure_email_login_otp_' . md5( strtolower( trim( $email ) ) );
}

function sel_otp_attempts_key( $email ) {
	return 'secure_email_login_attempts_' . md5( strtolower( trim( $email ) ) );
}