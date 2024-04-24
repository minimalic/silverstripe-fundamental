# Silverstripe Fundamental - a foundation for modular pages

Provides essential, foundational elements for building modular pages using Silverstripe CMS 5 and the [Elemental](https://github.com/silverstripe/silverstripe-elemental) module.


## Requirements

* Silverstripe CMS version 4 or 5
* Silverstripe Elemental


## Installation

Install using Composer:

```sh
composer require minimalic/silverstripe-fundamental
```

Refresh your database by navigating to your website's root directory in the shell and running:
`vendor/bin/sake dev/build "flush=all"`

Or, use your base URL with:
`/dev/build?flush=all`


## Customization

### Custom Theme for ModularPage

Create your own `ModularPage.ss` template inside your theme's `template` directory at:
```
minimalic/Fundamental/Pages/Layout/
```


### Custom Themes for Modules

By default the Fundamental extension uses Bootstrap 5 classes for templating.

To override default templates for modules/blocks, create your own template file (e.g., `ModuleImage.ss`) inside your theme's `template` directory at:

```
minimalic/Fundamental/Modules/
```

Alternatively copy the entire `vendor/minimalic/silverstripe-fundamental/templates/` directory to your `templates/` directory and customize all template overrides.


### Remap Local Elemental Extensions (Optional)

This step is necessary if you are already using an ElementalPageExtension and want to replace it with the page type provided by this module.

To remap existing `App\Pages\ModularPage` or `ElementalPage` (the class name of your custom Elemental page extension, if any), use the following configuration in your `mysite.yml`:

```yaml
SilverStripe\ORM\DatabaseAdmin:
  classname_value_remapping:
    'App\Pages\ModularPage': 'minimalic\Fundamental\Pages\ModularPage'
```

Replace `App\Pages\ModularPage` with your class name. Also, ensure your existing `table_name` is `ModularPage`; if not, you should rename your table.

After rebuilding your database, you can delete these remap lines from your mysite.yml. Your existing Elemental pages should now use this module.


## License

See [License](LICENSE)

Copyright (c) 2024, minimalic.com - Sebastian Finke
All rights reserved.
