<?php

/**
 * Used to create the output page
 */

namespace EasyReader\Pages;

use EasyReader\HTML\HTMLBuilder;
use EasyReader\AuthManager;

class LogoutPage extends SitePage {
    public function __construct() {
        parent::__construct( 'Logout' );
    }

    protected function getBodyElements(): array {
        AuthManager::logOut();
        return [
            HTMLBuilder::element( 'p', 'Log out successful ' ),
        ];
    }

}