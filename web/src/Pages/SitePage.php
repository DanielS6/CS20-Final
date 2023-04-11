<?php

/**
 * Used to create the output page
 */

namespace EasyReader\Pages;

use EasyReader\HTML\{HTMLBuilder, HTMLElement, HTMLPage};
use EasyReader\AuthManager;

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

        $sideNav = $this->getSideNav();
        foreach ( $sideNav as $thing ) {
            $this->page->addBodyElement( $thing );
        }

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

   private function getSideNav(): array {
        return [
            HTMLBuilder::element(
                'div',
                $this->buildNavProfile(),
                [ 'class' => 'side-nav' ]
            ),
            HTMLBuilder::element('div', $this->buildPrevSearches(), []),
            HTMLBuilder::element('div', $this->buildSideNavControls(), [])];
            
    }
    
    private function buildPrevSearches(): HTMLElement {
        return HTMLBuilder::element( 'label', '' );
    }

    private function buildSideNavControls(): HTMLElement {  
        return HTMLBuilder::element( 'button', [], ['value' => 'Clear Search', 'class' => 'er-navButton' ]);
    }

    private function buildNavProfile(): HTMLElement {
        if ( AuthManager::isLoggedIn() ) {
            $loginOutLink = HTMLBuilder::link(
                './logout.php',
                'Log out',
                [ 'class' => 'er-navButton' ]
            );
        } else {
            $loginOutLink = HTMLBuilder::link(
                './login.php',
                'Log in',
                [ 'class' => 'er-navButton' ]
            );
        }
        return HTMLBuilder::element(
            'div',
            [
                HTMLBuilder::link(
                    './index.php',
                    HTMLBuilder::image('logo.svg', [ 'class' => 'er-logo'])
                ),
                HTMLBuilder::element('p', ['ex@gmail.com',
                    HTMLBuilder::image('profile.png', [ 'id' => 'er-imgProfile'])
                ], ['id' => 'er-profLine']),
              $loginOutLink
            ],
            [ 'id' => 'er-profile']
        );
    }

    abstract protected function getBodyElements(): array;
}