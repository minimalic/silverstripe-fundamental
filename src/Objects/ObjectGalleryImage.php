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
        'GridFieldThumbnail' => 'Image',
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
     * Determines if the image can be viewed by the current user.
     *
     * @return bool True if the image can be viewed by the current user, false otherwise.
     */
    public function canViewImage() {
        $image = $this->Image();
        if (!$image || !$image->exists()) {
            return false;
        }
        return $image->canView();
    }

    /**
     * Generates High DPI Thumbnail for GridField preview.
     * Adds a "disabled" badge to disabled GridField items.
     * Adds a "not published" badge to items with draft image.
     *
     * @return DBHTMLText
     */
    public function GridFieldThumbnail()
    {
        $imageSource = $this->Image()->FitMax(300,300);
        $imageElement = '<div class="p-4"></div>';
        $badges = '';
        if ($imageSource) {
            $imageRetinaWidth = $imageSource->getWidth() / 2;
            $imageRetinaHeight = $imageSource->getHeight() / 2;
            $imageURL = $imageSource->getURL();
            $imageStyle = "";
            if (!$this->Enabled) {
                $imageStyle = "opacity: 0.5;";
            }
            $imageElement = '<img class="d-block" width="' . $imageRetinaWidth . '" height="' . $imageRetinaHeight . '" alt="' . $imageSource->getTitle() . '" src="' . $imageURL . '" style="' . $imageStyle . '" loading="lazy">';
        }

        if ($this->Image()->isOnDraftOnly() || $this->Image()->isModifiedOnDraft()) {
            $badges .= '<span class="badge badge-pill badge-info p-2 mb-2">not published</span><br>';
        }

        if (!$this->Enabled) {
            $badges .= '<span class="badge badge-pill badge-warning p-2 mb-2">disabled</span><br>';
        }

        $imageHTML = DBHTMLText::create()->setValue('
                <div class="position-relative overflow-hidden" style="min-width: 100px; min-height: 100px;">
                    ' . $imageElement . '
                    <div class="position-absolute m-2" style="top: 0; left: 0;">
                        ' . $badges . '
                    </div>
                </div>
            ');

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
