<?php

namespace minimalic\Fundamental\Pages;

use DNADesign\Elemental\Extensions\ElementalPageExtension;
use DNADesign\Elemental\Models\ElementalArea;

use Page;

class ModularPage extends Page
{
    private static string $table_name = 'ModularPage';

    private static $icon_class = 'font-icon-p-alt-2';

    private static $singular_name = 'Modular Page';
    private static $plural_name = 'Modular Pages';
    private static $description = 'Page with modular elements';

    private static $field_include = [
        'ElementalAreaID',
    ];

    // private static $extensions = [
    //     ElementalPageExtension::class,
    // ];

    private static $db = [
    ];

    private static $has_one = [
    ];

    private static $owns = [
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab('Root.Main', [

        ]);

        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        return;
    }

}
