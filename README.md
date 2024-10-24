# Silverstripe Fundamental - a foundation for modular pages

Provides essential, foundational elements for building modular pages using Silverstripe CMS and the [Elemental](https://github.com/silverstripe/silverstripe-elemental) module.


## Requirements

* Silverstripe CMS 5.2 or higher
* [Silverstripe Elemental](https://github.com/silverstripe/silverstripe-elemental)
* [Silverstripe Grid Field Extensions Module](https://github.com/symbiote/silverstripe-gridfieldextensions)
* [Silverstripe link module](https://github.com/silverstripe/silverstripe-linkfield)


## Installation

To install Fundamental (with requirements included) use Composer:

```sh
composer require minimalic/silverstripe-fundamental
```

Refresh your database by navigating to your website's root directory in the shell and running:
`vendor/bin/sake dev/build "flush=all"`

Or, use your base URL with:
`/dev/build?flush=all`


## Recommended Extensions

* [GridField Bulk Editing Tools](https://github.com/colymba/GridFieldBulkEditingTools)

The Fundamental extension is optimized for the "Bulk Upload" feature provided by GridField Bulk Editing Tools.

Install using composer (optional):
```sh
composer require colymba/gridfield-bulk-editing-tools
```


## Available Modules

- Image Block (`ModuleImage`) - Block with single image banner
- Hero Banner Block (`ModuleHeroBanner`) - Displays a hero banner featuring text overlayed on an image
- Hero Split Block (`ModuleHeroSplit`) - Displays a hero split with image and text side-by-side. Allows switching the order
- Slideshow (`ModuleSlideshow`) - Image slider/carousel (Bootstrap compatible)
- Gallery (`ModuleGallery`) - Image thumbnails as a grid
- Teaser (`ModuleTeaser`) - Block with teaser items (with icon and buttons)


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


### Options for custom image upload directory

By default images (modules: "Image Block", "Hero Banner Block"...) will be uploaded to `images/parent-class/parent-title`, where `parent` is the page/object holding the module.
To change this behavior add this to your config (e.g. inside `mysite.yml`):

```yaml
minimalic\Fundamental\Modules\ModuleImage:
  image_directory_name: 'images'
  image_directory_sub_struct: 'class/parent'
```

For the `image_directory_name` configuration option you can chose the top directory name for uploaded files.
The available options for `image_directory_sub_struct` are:
- `parent` (parent page title)
- `class/parent` (parent page class/title, default)
- `element` (current element title)
- `class/element` (current element class/title)

The "Slideshow" and "Gallery" modules provide one additional `image_directory_sub_struct` option:
- `parent/element` (parent page title/current element title)

```yaml
minimalic\Fundamental\Modules\ModuleGallery:
  image_directory_name: 'images'
  image_directory_sub_struct: 'parent/element'
```


## Global configuration

### Set image resize quality (Optional)

Set the global image quality inside your config (e.g. `mysite.yml`) and enable forced resampling for not-resized images (optional):

```yaml
SilverStripe\Assets\File:
  force_resample: true

SilverStripe\Assets\Storage\DBFile:
  force_resample: true

SilverStripe\Core\Injector\Injector:
  SilverStripe\Assets\Image_Backend:
    properties:
      Quality: 70
```


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
