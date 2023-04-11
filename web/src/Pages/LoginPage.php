<?php

/**
 * Used to create the output page
 */

namespace EasyReader\Pages;

use EasyReader\HTML\{HTMLBuilder, HTMLElement, HTMLPage};
use EasyReader\AuthManager;
use EasyReader\Database;

class LoginPage extends SitePage {
    public function __construct() {
        parent::__construct( 'Login' );
        $this->addStyleSheet( 'login-styles.css' );
    }

    protected function getBodyElements(): array {
        return [
            ...$this->getMainDisplay(),
        ];
    }

    private function getMainDisplay(): array {
        $isPost = ( $_SERVER['REQUEST_METHOD'] ?? 'GET' ) === 'POST';
        if ( !$isPost ) {
            return [ $this->getForm() ];
        }
        $submitError = $this->trySubmit();
        if ( $submitError !== '' ) {
            return [
                HTMLBuilder::element(
                    'p',
                    $submitError,
                    [ 'class' => 'er-error ' ]
                ),
                $this->getForm(),
            ];
        }
        return [
            HTMLBuilder::element(
                'p',
                'Account login successful!'
            ),
        ];
    }

    private function trySubmit(): string {
        $email = $_POST['er-email'];
        $pass = $_POST['er-password'];
        if ( $email === '' ) {
            return 'Missing email';
        } else if ( $pass === '' ) {
            return 'Missing password';
        }
        $db = new Database;
        $accountInfo = $db->getAccount( $email );
        if ( $accountInfo === null ) {
            return 'Email not associated with an account';
        }
        $hash = md5( $pass );
        if ( $hash !== $accountInfo->user_pass_hash ) {
            return 'Incorrect password';
        }
        AuthManager::loginSession( $accountInfo->user_id );
        return '';
    }

    private function getForm(): HTMLElement {
        return HTMLBuilder::element(
            'form',
            $this->getFormFields(),
            [
                'id' => 'oc-login',
                'action' => './login.php',
                'method' => 'POST',
                'class' => 'center-table',
            ]
        );
    }

    private function getFormFields(): array {
        return [
            HTMLBuilder::element(
                'h1',
                'Login'
            ),
            HTMLBuilder::element(
                'label',
                'Email:',
                [ 'for' => 'er-email' ]
            ),
            HTMLBuilder::element(
                'input',
                [],
                [ 'type' => 'email', 'id' => 'er-email', 'name' => 'er-email' ]
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
            HTMLBuilder::element('div', '', ['class' => 'space']),
            HTMLBuilder::element(
                'button',
                'Login',
                [ 'type' => 'submit', 'id' => 'er-login-submit' ]
            ),
        ];
    }
}