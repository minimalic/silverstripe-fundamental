<?php

namespace minimalic\Fundamental\Modules;

use SilverStripe\Forms\DropdownField;
use SilverStripe\LinkField\Form\MultiLinkField;
use SilverStripe\LinkField\Models\Link;
use DNADesign\Elemental\Models\BaseElement;

class ModuleHeroBanner extends ModuleImage
{
    private static $icon = 'font-icon-block-banner';

    private static $singular_name = 'Hero Banner Block';
    private static $plural_name = 'Hero Banner Blocks';
    private static $description = 'Displays a hero banner featuring text overlayed on an image.';
    private static $table_name = 'ModuleHeroBanner';

    private static $db = [
        'Content' => 'HTMLText',
        'DisplayHeight' => 'Varchar',
    ];

    private static $has_many = [
        'Links' => Link::class . '.Owner',
    ];

    private static $owns = [
        'Links',
    ];

    private static array $cascade_deletes = [
        'Links',
    ];

    private static array $cascade_duplicates = [
        'Links',
    ];

    private static $defaults = [
        'DisplayHeight' => 'default',
    ];

    private static $inline_editable = false;

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(['Links']);

        $fieldLinks = MultiLinkField::create('Links', _t(__CLASS__ . '.Links', 'Links'));

        $fields->addFieldsToTab('Root.Main', [
            $fieldLinks,
        ], 'Image');

        $heights = [
            'default' => _t(__CLASS__ . '.HeightDefault', 'Default (use site-wide settings)'),
            'content' => _t(__CLASS__ . '.HeightContent', 'Stretched to Content'),
            '50vh'    => _t(__CLASS__ . '.Height50vh', '50% of Viewport Height'),
            '75vh'    => _t(__CLASS__ . '.Height75vh', '75% of Viewport Height'),
            '100vh'   => _t(__CLASS__ . '.Height100vh', '100% of Viewport Height'),
            '360px'   => _t(__CLASS__ . '.Height360px', '360 Pixels'),
            '500px'   => _t(__CLASS__ . '.Height500px', '500 Pixels'),
            '640px'   => _t(__CLASS__ . '.Height640px', '640 Pixels'),
            '800px'   => _t(__CLASS__ . '.Height800px', '800 Pixels'),
        ];

        $fieldDisplayHeight = DropdownField::create(
            'DisplayHeight',
            _t(__CLASS__ . '.DisplayHeight', 'Banner Display Height'),
            $heights
        );

        $fields->addFieldsToTab('Root.Settings', [
            $fieldDisplayHeight,
        ], 'FullWidth');

        return $fields;
    }

    public function getSummary(): string
    {
        return _t(__CLASS__ . '.Summary', 'Displays a hero banner with text overlayed on an image.');
    }

    public function getType()
    {
        return _t(__CLASS__ . '.Type', 'Hero Banner');
    }

}
