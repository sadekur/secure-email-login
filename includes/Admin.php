<?php

namespace SecureEmailLogin\EmailLogin;

/**
 * The admin class
 */
class Admin {

    /**
     * Initialize the class
     */
    function __construct() {
        echo "Hello Admin";

        // new Admin\Menu();
        // new Admin\Leads_List_Table();
    }

    /**
     * Dispatch and bind actions
     *
     * @return void
     */
    // public function dispatch_actions( $addressbook ) {
    //     add_action( 'admin_init', [ $addressbook, 'form_handler' ] );
    //     add_action( 'admin_post_wd-ac-delete-address', [ $addressbook, 'delete_address' ] );
    // }
}
