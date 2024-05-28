<?php

namespace minimalic\Fundamental\Objects;

use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\View\Parsers\URLSegmentFilter;
use minimalic\Fundamental\Modules\ModuleSlideshow;

class ObjectSlide extends DataObject
{

    private static $singular_name = 'Slide';

    private static $plural_name = 'Slides';

    private static $description = 'Slide for a slideshow';

    private static $table_name = 'ModularObjectSlide';

    /**
     * Main Directory for uploaded Image, empty String for none
     *
     * @string
     */
    private static $image_directory_name = 'images';

    /**
     * Subdirectory for uploaded Image. Available options:
     * 'parent', 'class/parent', 'element', 'class/element', 'parent/element', '' (empty: disabled)
     *
     * @string
     */
    private static $image_directory_sub_struct = 'parent/element';

    private static $db = [
        'Sort' => 'Int',
        'Title' => 'Varchar',
        'Content' => 'Text',
        'Enabled' => 'Boolean',
    ];

    private static $has_one = [
        'Image' => Image::class,
        'ModuleSlideshow' => ModuleSlideshow::class,
    ];

    private static $owns = [
        'Image',
    ];

    private static $summary_fields = [
        'Enabled.Nice' => 'Enabled',
        'Image.CMSThumbnail' => 'Image',
        // 'Image.StripThumbnail' => 'Image',
        'Title',
    ];

    private static $default_sort = 'Sort ASC';

    private static $defaults = [
        'Enabled' => true,
    ];

    public function populateDefaults()
    {
        $this->Enabled = true;
        parent::populateDefaults();
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(['Sort', 'ModuleSlideshowID']);

        $fieldEnabled = CheckboxField::create('Enabled', _t(__CLASS__ . '.Enabled', 'Enable slide'));

        $fieldImage = UploadField::create('Image');
        $imageUploadPath = $this->generateUploadDirectory();
        if (!empty($imageUploadPath) && $imageUploadPath != '/') {
            $fieldImage->setFolderName($imageUploadPath);
        }

        $fields->addFieldsToTab('Root.Main', [
            $fieldEnabled,
            $fieldImage,
        ], 'Title');

        return $fields;
    }

    /**
     * Return parent object
     *
     * @return ModuleSlideshow
     */
    public function Parent()
    {
        if ($this->ModuleSlideshow()) {
            return $this->ModuleSlideshow();
        }

        return null;
    }

    /**
     * Generate image upload directory based on config
     *
     * @return string
     */
    public function generateUploadDirectory()
    {
        $filter = URLSegmentFilter::create();
        $uploadPath = '';
        $configuredDirectory = $this->config()->get('image_directory_name');
        $configuredSubDirectory = $this->config()->get('image_directory_sub_struct');
        $parentPage = $this->Parent()->Parent()->getOwnerPage();

        if (!empty($configuredDirectory)) {
            $normalizedDirectory = $filter->filter($configuredDirectory);
            $uploadPath = $normalizedDirectory . '/';
        }

        if ($configuredSubDirectory == 'class/parent' && $parentPage && $parentPage->exists()) {
            $className = basename(str_replace('\\', '/', $parentPage->ClassName));
            $classSegment = $filter->filter($className);
            $titleSegment = $filter->filter($parentPage->Title);
            $uploadPath .=  $classSegment . '/' . $titleSegment . '/';
        } elseif ($configuredSubDirectory == 'parent' && $parentPage && $parentPage->exists()) {
            $titleSegment = $filter->filter($parentPage->Title);
            $uploadPath .=  $titleSegment . '/';
        } elseif ($configuredSubDirectory == 'class/element' && !empty($this->Parent()->Title)) {
            $className = basename(str_replace('\\', '/', $this->ClassName));
            $classSegment = $filter->filter($className);
            $titleSegment = $filter->filter($this->Parent()->Title);
            $uploadPath .=  $classSegment . '/' . $titleSegment . '/';
        } elseif ($configuredSubDirectory == 'element' && !empty($this->Parent()->Title)) {
            $titleSegment = $filter->filter($this->Parent()->Title);
            $uploadPath .=  $titleSegment . '/';
        } elseif ($configuredSubDirectory == 'parent/element' && $parentPage && $parentPage->exists() && !empty($this->Parent()->Title)) {
            $parentPageSegment = $filter->filter($parentPage->Title);
            $titleSegment = $filter->filter($this->Parent()->Title);
            $uploadPath .=  $parentPageSegment . '/' . $titleSegment . '/';
        }

        return $uploadPath;
    }

    /**
     * Resize the image based on provided dimensions
     *
     * @return Image
     */
    public function getResizedImage()
    {
        $imageSource = $this->Image();

        if ($this->Parent()) {
            $width = $this->Parent()->Width;
            $height = $this->Parent()->Height;

            if ($width > 0 && $height > 0) {
                return $imageSource->FillMax($width, $height);
            } elseif ($width > 0) {
                return $imageSource->ScaleMaxWidth($width);
            } elseif ($height > 0) {
                return $imageSource->ScaleMaxWidth($height);
            }

        }
        if ($imageSource->getWidth() > 3840 || $imageSource->getHeight() > 3840) {
            return $imageSource->FitMax(3840, 3840);
        }

        return $imageSource;
    }

    /**
     * Ensure only numeric input for dimensions (before writing to the database)
     */
    public function onBeforeWrite() {
        parent::onBeforeWrite();

        if (!$this->Sort) {
            $this->Sort = ObjectSlide::get()->max('Sort') + 1;
        }
    }

}
