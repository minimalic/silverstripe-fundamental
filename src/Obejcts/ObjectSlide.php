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
use minimalic\Fundamental\Modules\ModuleSlideshow;

class ObjectSlide extends DataObject
{

    private static $singular_name = 'Slide';

    private static $plural_name = 'Slides';

    private static $description = 'Slide for a slideshow';

    private static $table_name = 'ModularObject_Slide';

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

    private static $has_many = [
        'Links' => Link::class . '.Owner',
    ];

    private static $owns = [
        'Image',
        'Links',
    ];

    private static array $cascade_deletes = [
        'Links',
    ];

    private static array $cascade_duplicates = [
        'Links',
    ];

    private static $summary_fields = [
        // 'Enabled.Nice' => 'Enabled',
        'GridFieldThumbnail' => 'Image',
        // 'Image.CMSThumbnail' => 'Image',
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

        $fields->removeByName(['Sort', 'ModuleSlideshowID', 'Links']);

        $fieldEnabled = CheckboxField::create('Enabled', _t(__CLASS__ . '.Enabled', 'Enable slide'));

        $fieldImage = UploadField::create('Image');
        if ($this->Parent()) {
            $imageUploadPath = $this->Parent()->generateUploadDirectory();
            if (!empty($imageUploadPath) && $imageUploadPath != '/') {
                $fieldImage->setFolderName($imageUploadPath);
            }
        }

        $fieldLinks = MultiLinkField::create('Links', _t(__CLASS__ . '.Links', 'Links'));

        $fields->addFieldsToTab('Root.Main', [
            $fieldEnabled,
            $fieldImage,
        ], 'Title');

        $fields->addFieldsToTab('Root.Main', [
            $fieldLinks,
        ]);

        return $fields;
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
        $imageElement = '<div class="p-4"></div>';
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

        if (!$this->Enabled) {
            $imageHTML = DBHTMLText::create()->setValue('
                <div class="position-relative item-disabled">
                    ' . $imageElement . '
                    <div class="position-absolute m-2" style="top: 0; left: 0;">
                        <span class="badge badge-pill badge-warning p-2">disabled</span>
                    </div>
                </div>
            ');
        } else {
            $imageHTML = DBHTMLText::create()->setValue($imageElement);
        }

        return $imageHTML;
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
