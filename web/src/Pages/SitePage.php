<?php

/**
 * Used to create the output page
 */

namespace EasyReader\Pages;

use EasyReader\HTML\{HTMLBuilder, HTMLElement, HTMLPage};

abstract class SitePage {
    private HTMLPage $page;
    private bool $loadedBodyContent = false;

    /** @param string $pageTitle */
    protected function __construct( string $pageTitle ) {
        $this->page = new HTMLPage();
        $this->page->addHeadElement(
            HTMLBuilder::element( 'title', $pageTitle )
        );
        // Prevent trying to read a favicon that we don't have
        $this->page->addHeadElement(
            HTMLBuilder::element(
                'link',
                [],
                [ 'rel' => 'icon', 'href' => 'data:,' ]
            )
        );
        // Always add global-styles.css
        $this->addStyleSheet( 'global-styles.css' );
        $this->page->addBodyElement( $this->getHeading() );
        // Body from getBodyElements() is added in getOutput() so that subclass
        // constructor code after calling this parent constructor can take
        // effect
    }

    protected function addScript( string $fileName ): void {
        $this->page->addHeadElement(
            HTMLBuilder::element(
                'script',
                [],
                [ 'src' => "/resources/{$fileName}" ]
            )
        );
    }
    protected function addStyleSheet( string $fileName ): void {
        $this->page->addHeadElement(
            HTMLBuilder::element(
                'link',
                [],
                [
                    'rel' => 'stylesheet',
                    'type' => 'text/css',
                    'href' => "/resources/{$fileName}",
                ]
            )
        );
    }

    public function getOutput(): string {
        // Don't load multiple times
        if ( !$this->loadedBodyContent ) {
            $this->loadedBodyContent = true;
            $this->page->addBodyElement(
                HTMLBuilder::element(
                    'div',
                    $this->getBodyElements(),
                    [ 'class' => 'body-content-wrapper' ]
                )
            );
        }
        return $this->page->getPageOutput();
    }

    private function getHeading(): HTMLElement {
        return HTMLBuilder::element(
            'span',
            'COMMON HEADING'
        );
    }
    abstract protected function getBodyElements(): array;
}