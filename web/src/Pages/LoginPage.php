<?php

/**
 * Used to create the output page
 */

namespace EasyReader\Pages;

use EasyReader\HTML\{HTMLBuilder, HTMLElement, HTMLPage};

class LoginPage extends SitePage {
    public function __construct() {
        parent::__construct( 'Login' );
    }

    protected function getBodyElements(): array {
        return [
            HTMLBuilder::element( 'h1', 'LOGIN' ),
            // TODO <form> wrapper
            HTMLBuilder::element(
                'label',
                'Email:',
                [ 'for' => 'er-email' ]
            ),
            HTMLBuilder::element(
                'input',
                [],
                [ 'type' => 'text', 'id' => 'er-email', 'name' => 'er-email' ]
            ),
            HTMLBuilder::element( 'br' ),
            HTMLBuilder::element(
                'label',
                'Password:',
                [ 'for' => 'er-password' ]
            ),
            HTMLBuilder::element(
                'input',
                [],
                [ 'type' => 'password',
                    'id' => 'er-password',
                    'name' => 'er-password' ]
            ),
        ];
    }
}