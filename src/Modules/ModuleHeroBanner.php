<?php

namespace minimalic\Fundamental\Modules;

use SilverStripe\Forms\DropdownField;

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
        'DisplayHeight' => 'Varchar',
    ];

    private static $has_one = [
    ];

    private static $owns = [
    ];

    private static $defaults = [
        'DisplayHeight' => 'default',
    ];

    private static $inline_editable = false;

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $heights = [
            'default' => 'Default',
            'content' => 'Stretched to Content',
            '50vh' => '50% of Viewport Height',
            '75vh' => '75% of Viewport Height',
            '100vh' => '100% of Viewport Height',
            '360px' => '360 Pixels',
            '500px' => '500 Pixels',
            '640px' => '640 Pixels',
            '800px' => '800 Pixels',
        ];

        $fieldDisplayHeight = DropdownField::create('DisplayHeight', _t(__CLASS__ . '.DisplayHeight', 'Banner Display Height'), $heights);

        $fields->addFieldsToTab('Root.Settings', [
            $fieldDisplayHeight,
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
