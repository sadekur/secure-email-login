<?php

namespace Thrail\Crm\Frontend;

class Thrail_Crm_Shortcode {

    public function __construct() {
        add_shortcode( 'thrail_crm_optin', [ $this, 'thrail_crm_optin_form' ] );
        add_action( 'wp_footer', [ $this, 'thrail_crm_optin_footer' ] );
    }

    public function thrail_crm_optin_form() {
        $form_html = '<div class="form-container">
        <form id="thrailCrmOptinForm" action="" method="post">
            <label for="thrail_crm_name">' . esc_html__( 'Name:', 'thrail-crm' ) . '</label>
            <input type="text" id="thrail_crm_name" name="name" required placeholder="' . esc_attr__( 'Enter your name', 'thrail-crm' ) . '">
            <label for="thrail_crm_email">' . esc_html__( 'Email:', 'thrail-crm' ) . '</label>
            <input type="email" id="thrail_crm_email" name="email" required placeholder="' . esc_attr__( 'Enter your email', 'thrail-crm' ) . '">
            <input type="submit" value="' . esc_attr__( 'Subscribe', 'thrail-crm' ) . '">
        </form>
        </div>';
        return $form_html;
    }

    public function thrail_crm_optin_footer() {
        if ( ! is_admin() ) {
            echo '<div class="loader-container" id="thrailCrmFormLoader" style="display: none;">' .
                 '<img src="' . esc_url( THRAIL_CRM_ASSETS . '/img/loader.gif' ) . '" alt="' . esc_attr__( 'Loading...', 'thrail-crm' ) . '">' .
                 '</div>';
        }
    }   
}
