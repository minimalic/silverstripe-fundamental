<?php

namespace minimalic\Fundamental\Modules;

use SilverStripe\Forms\CheckboxField;

use DNADesign\Elemental\Models\BaseElement;

class ModuleHeroBanner extends ModuleImage
{
    private static $icon = 'font-icon-block-banner';

    private static $singular_name = 'Hero Banner Block';

    private static $plural_name = 'Hero Banner Blocks';

    private static $description = 'Image with Text inside as hero banner';

    private static $table_name = 'ModuleHeroBanner';

    private static $db = [
        'Content' => 'HTMLText',
        'FullHeight' => 'Boolean',
    ];

    private static $has_one = [
    ];

    private static $owns = [
    ];

    private static $defaults = [
    ];

    private static $inline_editable = false;

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fieldFullHeight = CheckboxField::create('FullHeight', _t(__CLASS__ . '.FullHeight', 'Display at Full Page Height'));

        $fields->addFieldsToTab('Root.Settings', [
            $fieldFullHeight,
        ], 'FullWidth');

        return $fields;
    }

    public function getSummary(): string
    {
        return _t(__CLASS__ . '.Summary', 'Displays text inside an image as a hero banner.');
    }

    public function getType()
    {
        return _t(__CLASS__ . '.Type', 'Hero Banner');
    }

    public function onBeforeWrite() {
        parent::onBeforeWrite();
    }

}
