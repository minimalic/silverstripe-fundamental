# Change Log


## [2.3.0](https://github.com/minimalic/silverstripe-fundamental/releases/tag/2.3.0) (2024-06-10)

### Features:
* Gallery module now uses [VenoBox](https://github.com/nicolafranchini/VenoBox) as a built-in lightbox plugin
* Lightbox is enabled by default (found under the "Settings" tab)
* CSS/JS files will load only if the lightbox is enabled


## [2.2.1](https://github.com/minimalic/silverstripe-fundamental/releases/tag/2.2.1) (2024-06-06)

### Fixes:
* The `colymba/gridfield-bulk-editing-tools` is no more required, but recommended


## [2.2.0](https://github.com/minimalic/silverstripe-fundamental/releases/tag/2.2.0) (2024-06-06)

### Before update:
The table `ModularObjectSlide` was renamed to `ModularObject_Slide` for consistency with other objects - there is no migration script. Rename the table manually using SQL:
```sql
RENAME TABLE "ModularObjectSlide" TO "ModularObject_Slide"
```

### Features:
* Slideshow module now supports uploading multiple images at once (bulk upload)
* Draft badge for unpublished images (Gallery and Slideshow modules)

### Fixes:
* Fixed crash while trying to preview items with no images (Gallery module)
* Fixed looping through items with unpublished images (Gallery and Slideshow modules)


## [2.1.0](https://github.com/minimalic/silverstripe-fundamental/releases/tag/2.1.0) (2024-06-05)

### Features:
* New Gallery module for displaying image thumbnails as a grid
* New `colymba/gridfield-bulk-editing-tools` requirement to "Bulk Upload" images as `DataObjects`


## [2.0.0](https://github.com/minimalic/silverstripe-fundamental/releases/tag/2.0.0) (2024-06-02)

### Features:
* Higher requirements (Silverstripe 5.2)
* New `silverstripe/linkfield` requirement
* New linkfield based buttons (Bootstrap compatible) for `ModuleHeroBanner`, `ModuleHeroSplit` and `ModuleSlideshow`


## [1.2.1](https://github.com/minimalic/silverstripe-fundamental/releases/tag/1.2.1) (2024-05-31)

### Fixes:
* Update translations for Slideshow module


## [1.2.0](https://github.com/minimalic/silverstripe-fundamental/releases/tag/1.2.0) (2024-05-29)

### Features:
* Add new Slideshow module (Bootstrap Carousel compatible)


## [1.1.3](https://github.com/minimalic/silverstripe-fundamental/releases/tag/1.1.3) (2024-04-30)

### Features:
* Use Bootstrap breakpoints on split view
* Use custom classes with BEM naming convention


## [1.1.2](https://github.com/minimalic/silverstripe-fundamental/releases/tag/1.1.2) (2024-04-28)

### Fixes:
* Fix resampling images with dimensions below 3840px when enabling `force_resample` global configuration


## [1.1.1](https://github.com/minimalic/silverstripe-fundamental/releases/tag/1.1.1) (2024-04-28)

### Features:
* Add configuration for custom upload directory for images


## [1.1.0](https://github.com/minimalic/silverstripe-fundamental/releases/tag/1.1.0) (2024-04-24)

### Features:
* Add new Image Block module
* Add new Hero Banner Block module:
Displays a hero banner featuring text overlayed on an image.
* Add new Hero Split Block module:
Displays a hero split with image and text side-by-side.


## [1.0.1](https://github.com/minimalic/silverstripe-fundamental/releases/tag/1.0.1) (2024-04-21)

### Fixes/Features:
* Add translations (en, de)
* Remove unused code


## [1.0.0](https://github.com/minimalic/silverstripe-fundamental/releases/tag/1.0.0) (2024-04-21)

First release
