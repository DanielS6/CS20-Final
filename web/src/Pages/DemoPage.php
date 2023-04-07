<?php

/**
 * Used to create the output page
 */

namespace EasyReader\Pages;

use EasyReader\HTML\{HTMLBuilder, HTMLElement, HTMLPage};

class DemoPage extends SitePage {
    public function __construct() {
        parent::__construct( 'Demo' );
    }

    protected function getBodyElements(): array {
        return [
            HTMLBuilder::element( 'h1', 'Stuff and things' )
        ];
    }
}