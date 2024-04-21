<?php

namespace minimalic\Fundamental\Pages;

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
}
