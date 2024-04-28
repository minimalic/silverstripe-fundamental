<?php

namespace minimalic\Fundamental\Modules;

use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\View\Parsers\URLSegmentFilter;

use DNADesign\Elemental\Models\BaseElement;

class ModuleImage extends BaseElement
{
    private static $icon = 'font-icon-block-file';

    private static $singular_name = 'Image Block';
    private static $plural_name = 'Image Blocks';
    private static $description = 'Block with single image banner';
    private static $table_name = 'ModuleImage';

    /**
     * Main Directory for uploaded Image. Empty String for none.
     *
     * @String
     */
    private static $image_directory_name = 'images';

    /**
     * Subdirectory for uploaded Image. Available options:
     * 'parent', 'class/parent', 'element', 'class/element', '' (empty: disabled)
     *
     * @String
     */
    private static $image_directory_sub_struct = 'class/parent';

    private static $db = [
        'FullWidth' => 'Boolean',
        'AllowUpscale' => 'Boolean',
        'Width' => 'Int',
        'Height' => 'Int',
    ];

    private static $has_one = [
        'Image' => Image::class
    ];

    private static $owns = [
        'Image',
    ];

    private static $defaults = [
        'FullWidth' => false,
        'AllowUpscale' => true,
        'Width' => 0,
        'Height' => 0,
    ];

    private static $inline_editable = false;

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(['Image', 'Width', 'Height']);

        $fieldImage = UploadField::create('Image');
        $imageUploadPath = $this->generateUploadDirectory();
        if (!empty($imageUploadPath) && $imageUploadPath != '/') {
            $fieldImage->setFolderName($imageUploadPath);
        }

        $fields->addFieldsToTab('Root.Main', [
            $fieldImage,
        ]);

        $fieldFullWidth = CheckboxField::create('FullWidth', _t(__CLASS__ . '.FullWidth', 'Display at Full Page Width'));

        $fieldAllowUpscale = CheckboxField::create('AllowUpscale', _t(__CLASS__ . '.AllowUpscale', 'Allow Image Upscale'));
        $fieldAllowUpscale->setDescription(_t(__CLASS__ . '.AllowUpscaleDescription', 'Upscale will only apply on the website, not on the image file itself.'));

        $fieldWidth = NumericField::create('Width', _t(__CLASS__ . '.Width', 'Width'));
        $fieldWidth->setScale(0); // enable integer
        $fieldWidth->setMaxLength(5); // not working with HTML5 enabled

        $fieldHeight = NumericField::create('Height', _t(__CLASS__ . '.Height', 'Height'));
        $fieldHeight->setScale(0); // enable integer
        $fieldHeight->setMaxLength(5); // not working with HTML5 enabled

        $fieldDimensions = FieldGroup::create(
            $fieldWidth,
            $fieldHeight
        )->setTitle(_t(__CLASS__ . '.Dimensions', 'Dimensions'))
         ->setDescription(_t(__CLASS__ . '.DimensionsDescription', "Enter the dimensions in pixels. Set 0 for the image’s default size.<br>Image will be scaled and cropped to fit the new resolution. If only one value is set, the image will maintain its original aspect ratio.<br>Upscaling is disabled."));

        $fields->addFieldsToTab('Root.Settings', [
            $fieldFullWidth,
            $fieldAllowUpscale,
            $fieldDimensions,
        ]);

        return $fields;
    }

    /**
     * Generates image upload directory based on config.
     *
     * @return String
     */
    public function generateUploadDirectory()
    {
        $filter = URLSegmentFilter::create();
        $uploadPath = '';
        $configuredDirectory = $this->config()->get('image_directory_name');
        $configuredSubDirectory = $this->config()->get('image_directory_sub_struct');
        $parentPage = $this->Parent()->getOwnerPage();

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
        } elseif ($configuredSubDirectory == 'class/element' && !empty($this->Title)) {
            $className = basename(str_replace('\\', '/', $this->ClassName));
            $classSegment = $filter->filter($className);
            $titleSegment = $filter->filter($this->Title);
            $uploadPath .=  $classSegment . '/' . $titleSegment . '/';
        } elseif ($configuredSubDirectory == 'element' && !empty($this->Title)) {
            $titleSegment = $filter->filter($this->Title);
            $uploadPath .=  $titleSegment . '/';
        }

        return $uploadPath;
    }

    /**
     * Resizes the image based on provided dimensions.
     *
     * @return Image
     */
    public function getResizedImage()
    {
        $imageSource = $this->Image();
        $width = $this->Width;
        $height = $this->Height;

        if ($width > 0 && $height > 0) {
            $imageOutput = $imageSource->FillMax($width, $height);
        } elseif ($width > 0) {
            $imageOutput = $imageSource->ScaleMaxWidth($width);
        } elseif ($height > 0) {
            $imageOutput = $imageSource->ScaleMaxWidth($height);
        } elseif ($imageSource->getWidth() > 3840 || $imageSource->getHeight() > 3840) {
            $imageOutput = $imageSource->FitMax(3840, 3840);
        } else {
            $imageOutput = $imageSource;
        }

        return $imageOutput;
    }

    public function getSummary(): string
    {
        return _t(__CLASS__ . '.Summary', 'Displays an image as a banner.');
    }

    public function getType()
    {
        return _t(__CLASS__ . '.Type', 'Image');
    }

    /**
     * Ensures only numeric input for dimensions before writing to the database.
     */
    public function onBeforeWrite() {
        parent::onBeforeWrite();

        if (isset($this->Width) && $this->Width != '') {
            $cleanWidth = preg_replace('/\D/', '', $this->Width);
            $this->Width = substr($cleanWidth, 0, 5);
        }

        if (isset($this->Height) && $this->Height != '') {
            $cleanHeight = preg_replace('/\D/', '', $this->Height);
            $this->Height = substr($cleanHeight, 0, 5);
        }
    }

}
