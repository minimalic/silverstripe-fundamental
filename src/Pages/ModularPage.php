<?php

namespace minimalic\Fundamental\Pages;

use TractorCow\Fluent\Model\Locale;
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

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        // Create copy of the ElementalArea when content is being localized by Fluent.
        if (class_exists(Locale::class)) {
            if (!$this->isDraftedInLocale() && $this->isInDB() && !$this->isArchived()) {
                $elementalArea = $this->ElementalArea();

                $elementalAreaNew = $elementalArea->duplicate();
                $this->ElementalAreaID = $elementalAreaNew->ID;
            }
        }

        return;
    }
}
