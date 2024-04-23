<?php

namespace minimalic\Fundamental\Modules;

use SilverStripe\Forms\CheckboxField;

use DNADesign\Elemental\Models\BaseElement;

class ModuleHeroSplit extends ModuleImage
{
    private static $icon = 'font-icon-block-promo-3';

    private static $singular_name = 'Hero Split Block';

    private static $plural_name = 'Hero Split Blocks';

    private static $description = 'Hero Split with Image and Text side-by-side';

    private static $table_name = 'ModuleHeroSplit';

    private static $db = [
        'Content' => 'HTMLText',
        'SwitchOrder' => 'Boolean',
    ];

    private static $has_one = [
    ];

    private static $owns = [
    ];

    private static $defaults = [
        'SwitchOrder' => false,
    ];

    private static $inline_editable = false;

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fieldSwitchOrder = CheckboxField::create('SwitchOrder', _t(__CLASS__ . '.SwitchOrder', 'Switch Text and Image order'));

        $fields->addFieldsToTab('Root.Settings', [
            $fieldSwitchOrder,
        ], 'FullWidth');

        return $fields;
    }

    public function getSummary(): string
    {
        return _t(__CLASS__ . '.Summary', 'Displays text and image as side-by-side block.');
    }

    public function getType()
    {
        return _t(__CLASS__ . '.Type', 'Hero Split');
    }

    public function onBeforeWrite() {
        parent::onBeforeWrite();
    }

}
