<?php

namespace minimalic\Fundamental\Modules;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\LinkField\Form\MultiLinkField;
use SilverStripe\LinkField\Models\Link;
use DNADesign\Elemental\Models\BaseElement;

class ModuleHeroSplit extends ModuleImage
{
    private static $icon = 'font-icon-block-promo-3';

    private static $singular_name = 'Hero Split Block';
    private static $plural_name = 'Hero Split Blocks';
    private static $description = 'Displays a hero split with image and text side-by-side. Allows switching the order.';
    private static $table_name = 'ModuleHeroSplit';

    private static $db = [
        'Content' => 'HTMLText',
        'SwitchOrder' => 'Boolean',
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
        'SwitchOrder' => false,
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

        $fieldSwitchOrder = CheckboxField::create(
            'SwitchOrder',
            _t(__CLASS__ . '.SwitchOrder', 'Switch Text and Image order')
        );

        $fields->addFieldsToTab('Root.Settings', [
            $fieldSwitchOrder,
        ], 'FullWidth');

        return $fields;
    }

    public function getSummary(): string
    {
        return _t(__CLASS__ . '.Summary', 'Displays text and image as side-by-side elements.');
    }

    public function getType()
    {
        return _t(__CLASS__ . '.Type', 'Hero Split');
    }

}
