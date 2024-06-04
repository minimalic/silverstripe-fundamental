<?php

namespace minimalic\Fundamental\Objects;

use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\View\Parsers\URLSegmentFilter;
use SilverStripe\LinkField\Form\MultiLinkField;
use SilverStripe\LinkField\Models\Link;
use minimalic\Fundamental\Modules\ModuleGallery;

class ObjectGalleryImage extends DataObject
{

    private static $singular_name = 'Image';

    private static $plural_name = 'Images';

    private static $description = 'Image for the gallery';

    private static $table_name = 'ModularObject_GalleryImage';

    private static $db = [
        'Sort' => 'Int',
        'Title' => 'Varchar',
        'Enabled' => 'Boolean',
    ];

    private static $has_one = [
        'Image' => Image::class,
        'ModuleGallery' => ModuleGallery::class,
    ];

    private static $has_many = [
    ];

    private static $owns = [
        'Image',
    ];

    private static $summary_fields = [
        // 'Enabled.Nice' => 'Enabled',
        // 'ImageEnabled' => '',
        // 'Image.CMSThumbnail' => 'Image',
        'GridFieldThumbnail' => 'Image',
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

        $fields->removeByName(['Sort', 'ModuleGalleryID']);

        $fieldEnabled = CheckboxField::create('Enabled', _t(__CLASS__ . '.Enabled', 'Enable Image'));

        $fieldImage = UploadField::create('Image');
        if ($this->Parent()) {
            $imageUploadPath = $this->Parent()->generateUploadDirectory();
            if (!empty($imageUploadPath) && $imageUploadPath != '/') {
                $fieldImage->setFolderName($imageUploadPath);
            }
        }

        $fields->addFieldsToTab('Root.Main', [
            $fieldEnabled,
            $fieldImage,
        ], 'Title');

        return $fields;
    }

    /**
     * Generate an "Enabled" check mark for the GridField preview
     * Hack the CSS to display GridField item with gray background if disabled
     *
     * @return DBHTMLText
     */
    public function ImageEnabled()
    {
        if ($this->Enabled) {
            $enabledIndicator = DBHTMLText::create()->setValue('
                <div class="item-enabled" style="display: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
                        <path d="M2.755,15.982l2.263,-2.262l7.333,7.333l14.631,-14.631l2.263,2.262l-16.894,16.894l-9.596,-9.596Z" style="fill:#000000;"/>
                    </svg>
                </div>
            ');
        } else {
            $enabledIndicator = DBHTMLText::create()->setValue('

                <style>
                    /* .table tbody tr.ss-gridfield-item:has(.item-disabled) { */
                    .table tbody tr:has(.item-disabled),
                    .table tbody tr.even:has(.item-disabled) {
                        background-color: #eee;
                    }
                    .table tbody tr:has(.item-disabled):hover,
                    .table tbody tr.even:has(.item-disabled):hover {
                        background-color: #e0e4e7;
                    }
                </style>
            ');
        }

        return $enabledIndicator;
    }

    /**
     * Generate High DPI Thumbnail for GridField preview
     * Add a "disabled" badge to disabled GridField items
     * Hack the CSS to display disabled GridField items with gray background
     *
     * @return DBHTMLText
     */
    public function GridFieldThumbnail()
    {
        $imageSource = $this->Image()->FitMax(300,300);
        $imageRetinaWidth = $imageSource->getWidth() / 2;
        $imageRetinaHeight = $imageSource->getHeight() / 2;
        $imageURL = $imageSource->getURL();
        $imageStyle = "";
        if (!$this->Enabled) {
            $imageStyle = "opacity: 0.5;";
        }
        $imageElement = '<img class="d-block" width="' . $imageRetinaWidth . '" height="' . $imageRetinaHeight . '" alt="' . $imageSource->getTitle() . '" src="' . $imageURL . '" style="' . $imageStyle . '" loading="lazy">';

        if (!$this->Enabled) {
            $imageHTML = DBHTMLText::create()->setValue('
                <div class="position-relative item-disabled">
                    ' . $imageElement . '
                    <div class="position-absolute m-2" style="top: 0; left: 0;">
                        <span class="badge badge-pill badge-warning p-2">disabled</span>
                    </div>
                </div>
                <style>
                    /* .table tbody tr.ss-gridfield-item:has(.item-disabled) { */
                    .table tbody tr:has(.item-disabled),
                    .table tbody tr.even:has(.item-disabled) {
                        background-color: #eee;
                    }
                    .table tbody tr:has(.item-disabled):hover,
                    .table tbody tr.even:has(.item-disabled):hover {
                        background-color: #e0e4e7;
                    }
                </style>
            ');
        } else {
            $imageHTML = DBHTMLText::create()->setValue($imageElement);
        }

        return $imageHTML;
    }

    /**
     * Return parent object
     *
     * @return ModuleImageshow
     */
    public function Parent()
    {
        if ($this->ModuleGallery()) {
            return $this->ModuleGallery();
        }

        return null;
    }

    /**
     * Generate image upload directory based on config
     *
     * @return string
     */
//     public function generateUploadDirectory()
//     {
//         $filter = URLSegmentFilter::create();
//         $uploadPath = '';
//         $configuredDirectory = $this->config()->get('image_directory_name');
//         $configuredSubDirectory = $this->config()->get('image_directory_sub_struct');
//         $parentPage = $this->Parent()->Parent()->getOwnerPage();
//
//         if (!empty($configuredDirectory)) {
//             $normalizedDirectory = $filter->filter($configuredDirectory);
//             $uploadPath = $normalizedDirectory . '/';
//         }
//
//         if ($configuredSubDirectory == 'class/parent' && $parentPage && $parentPage->exists()) {
//             $className = basename(str_replace('\\', '/', $parentPage->ClassName));
//             $classSegment = $filter->filter($className);
//             $titleSegment = $filter->filter($parentPage->Title);
//             $uploadPath .=  $classSegment . '/' . $titleSegment . '/';
//         } elseif ($configuredSubDirectory == 'parent' && $parentPage && $parentPage->exists()) {
//             $titleSegment = $filter->filter($parentPage->Title);
//             $uploadPath .=  $titleSegment . '/';
//         } elseif ($configuredSubDirectory == 'class/element' && !empty($this->Parent()->Title)) {
//             $className = basename(str_replace('\\', '/', $this->ClassName));
//             $classSegment = $filter->filter($className);
//             $titleSegment = $filter->filter($this->Parent()->Title);
//             $uploadPath .=  $classSegment . '/' . $titleSegment . '/';
//         } elseif ($configuredSubDirectory == 'element' && !empty($this->Parent()->Title)) {
//             $titleSegment = $filter->filter($this->Parent()->Title);
//             $uploadPath .=  $titleSegment . '/';
//         } elseif ($configuredSubDirectory == 'parent/element' && $parentPage && $parentPage->exists() && !empty($this->Parent()->Title)) {
//             $parentPageSegment = $filter->filter($parentPage->Title);
//             $titleSegment = $filter->filter($this->Parent()->Title);
//             $uploadPath .=  $parentPageSegment . '/' . $titleSegment . '/';
//         }
//
//         return $uploadPath;
//     }

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
     * Set starting sort number (before writing to the database)
     */
    public function onBeforeWrite() {
        parent::onBeforeWrite();

        if (!$this->Sort) {
            $this->Sort = ObjectGalleryImage::get()->max('Sort') + 1;
        }
    }

}
