<?php

namespace minimalic\Fundamental\Controllers;

use SilverStripe\View\Requirements;
use DNADesign\Elemental\Controllers\ElementController;

class ModuleGalleryController extends ElementController
{

    public function init()
    {
        parent::init();

        if ($this->LightboxEnabled) {
            Requirements::javascript('minimalic/silverstripe-fundamental: client/dist/js/venobox/venobox.min.js');
            Requirements::css('minimalic/silverstripe-fundamental: client/dist/js/venobox/venobox.min.css');

            $selector = "selector: '.venobox-" . $this->getAnchor() . "',";
            $options = "fitView: true, spinner: 'wave', overlayColor: 'rgba(255,255,255,1.0)', titlePosition: 'bottom', toolsBackground: '#ffffff', toolsColor: '#000000',";
            Requirements::customScript(<<<JS
                new VenoBox({
                    $selector
                    $options
                });
                JS
            );
            Requirements::customCSS(<<<CSS
                .vbox-child {
                    box-shadow: none;
                }
                CSS
            );
        }
    }

}
