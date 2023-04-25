<?php

/**
 * Used to create the output page
 */

namespace EasyReader\Pages;

use EasyReader\HTML\HTMLBuilder;
use EasyReader\HTML\HTMLElement;

class AboutPage extends SitePage {
    public function __construct() {
        parent::__construct( 'About' );
        $this->addStyleSheet( 'about-styles.css' );
    }

    private function getDaniel(): HTMLElement {
        return HTMLBuilder::element(
            'div',
            [
                HTMLBuilder::image('daniel.jpg', [ 'id' => 'er-daniel']),
                HTMLBuilder::element('p',
            <<<END
            Emily - From the Class of ‘24, Daniel is a Computer Science major who has seniority in our group ,and like Emily, also uses said seniority 
            to claim Social Security checks illegally.
            END
                ),
            ],
            ['class' => 'er-daniel']
        );
    }

    private function getEmily(): HTMLElement {
        return HTMLBuilder::element(
            'div',
            [
                HTMLBuilder::image('emily.jpg', [ 'id' => 'er-emily']),
                HTMLBuilder::element('p',
                <<<END
            Emily - From the Class of ‘23, Emily is a Computer Science major who has seniority in our group  and also uses said seniority 
            to claim Social Security checks illegally.
            END
                ),
            ],
            ['class' => 'er-emily']
        );
    }
    private function getJacob(): HTMLElement {
        return HTMLBuilder::element(
            'div',
            [
                HTMLBuilder::image('Jacob.jpg', [ 'id' => 'er-jacob']),
                HTMLBuilder::element('p',
                <<<END
            From the Class of '25, Jacob is a Computer Science major whose claim to fame is running a pyramid scheme type organization
            that preys on the vulnerable youth.
            END
                ),
            ],
            ['class' => 'er-jacob']
        );
    }
    private function getJoey(): HTMLElement {
        return HTMLBuilder::element(
            'div',
            [
                HTMLBuilder::image('joey.png', [ 'id' => 'er-joey']),
                HTMLBuilder::element('p',
                <<<END
            From the Class of '25, Joey is a Computer Science major, known by many to funnel millions into the pockets
            of local politicians to keep his exploitive businesses thriving.
            END
                ),
            ],
            ['class' => 'er-joey']
        );
    }

    protected function getBodyElements(): array {
        return [
            HTMLBuilder::element( 'h1', 'About' ),
            
            HTMLBuilder::element(
                'p',
                <<<END
                Welcome to Easy Reader! We are a reader that allows you to select and find information for your given text.
                Here you will be able to get information on any word, phrase, person through through our in-depth. 
                Let EasyReader be your assistant as you parse through text. Useful for defining, context-analysis, translation and more!
                END
            ),
            HTMLBuilder::element(
                'p',
                <<<END
                
                With a subscription, you can save all of your previous text to be remembered across all your devices.
                Additionally, you will have access to an exclusive portal to submit bug reports and feature requests - for
                users without a subscription this service is provided on an AS-IS BASIS with no guarantees of future reliability.
                END                
            ),
            HTMLBuilder::element('h2', 'Meet the Team'),
            $this->getDaniel(),
            $this->getEmily(),
            $this->getJacob(),
            $this->getJoey(),
        ];
    }
}