# Silverstripe Fundamental - a foundation for modular pages

Provides essential, foundational elements for building modular pages using Silverstripe CMS 5 and the Elemental module.


## Requirements

* Silverstripe elemental
* Compatible with Silverstripe versions 4 and 5


## Installation

Install using Composer:
```sh
composer require minimalic/silverstripe-foundation
```

Refresh your database by navigating to your website's root directory in the shell and running:
`vendor/bin/sake dev/build "flush=all"`

Or use your base URL with:
`/dev/build?flush=all`


## Customization

### custom theme for ModularPage

Create your own `ModularPage.ss` inside your theme's `template` directory inside:
```sh
minimalic/Fundamental/Pages/Layout/
```


### remapping local Elemental extensions (optional)

This step is only necessary if you already using an `ElementalPageExtension` and want to replace it with page type coming with this module.

To remap existing `App\Pages\ModularPage` or `ElementalPage` (a class name of your custom Elemental page extension, if any) use this inside your `mysite.yml`:
```yaml
SilverStripe\ORM\DatabaseAdmin:
  classname_value_remapping:
    'App\Pages\ModularPage': 'minimalic\Fundamental\Pages\ModularPage'
```

Replace `App\Pages\ModularPage` with your class name. Also make sure your existing `table_name` is `ModularPage` - if it's not a case your should rename your table.

After rebuild you can delete these remap lines from yout `mysite.yml`. Your existing Elemental pages should now use this module.


## License

See [License](LICENSE)

Copyright (c) 2024, minimalic.com - Sebastian Finke
All rights reserved.
