<?php

/**
 * Used to create the output page
 */

namespace EasyReader\Pages;

use EasyReader\HTML\{HTMLBuilder, HTMLElement, HTMLPage};

class DemoPage extends SitePage {
    public function __construct() {
        parent::__construct( 'Demo' );
        $this->addScript( 'term-lookup.js' );
    }

    protected function getBodyElements(): array {
        return [
            HTMLBuilder::element( 'h1', 'Stuff and things' ),
            HTMLBuilder::element( 'div', 'Definition...', [ 'id' => 'er-def' ] ),
            HTMLBuilder::element(
                'textarea', 'Stuff, things, apple, Obama',
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