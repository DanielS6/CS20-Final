<?php

/**
 * Used to create the output page
 */

namespace EasyReader\Pages;

use EasyReader\HTML\{HTMLBuilder, HTMLElement, HTMLPage};
use EasyReader\AuthManager;
use EasyReader\Database;

class DemoPage extends SitePage {
    public function __construct() {
        parent::__construct( 'Demo' );
        $this->addScript( 'term-lookup.js' );
        $this->addStyleSheet( 'demo-styles.css' );
    }

    private function getStartingText(): string {
        if ( !AuthManager::isLoggedIn() ) {
            return 'Lorem ipsum is often used as placeholder text.';
        }
        $db = new Database;
        $text = $db->getCurrentUserText( AuthManager::getLoggedInUserId() );
        if ( $text === null ) {
            return 'Lorem ipsum is often used as placeholder text.';
        }
        return $text;
    }

    protected function getBodyElements(): array {
        return [
            HTMLBuilder::element( 'h1', 'EasyReader' ),
            HTMLBuilder::element( 'div', 'Definition...', [ 'id' => 'er-def' ] ),
            HTMLBuilder::element(
                'textarea', $this->getStartingText(),
                [ 'id' => 'er-text' ] ),
            HTMLBuilder::element( 'button', 'Search', [ 'id' => 'er-search' ] ),
            HTMLBuilder::element(
                'div',
                [
                    HTMLBuilder::element('strong', 'Term history:'),
                    HTMLBuilder::element(
                        'div',
                        [],
                        [ 'id' => 'er-search-history' ]
                    )
                ]
            ),
        ];
    }
}