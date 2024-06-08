<?php

namespace minimalic\Fundamental\Modules;

use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldPageCount;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\View\Parsers\URLSegmentFilter;
use DNADesign\Elemental\Models\BaseElement;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use Colymba\BulkUpload\BulkUploader;
use minimalic\Fundamental\Objects\ObjectGalleryImage;

class ModuleGallery extends BaseElement
{
    private static $icon = 'font-icon-thumbnails';

    private static $singular_name = 'Gallery Block';

    private static $plural_name = 'Gallery Blocks';

    private static $description = 'Block with image gallery';

    private static $table_name = 'ModuleGallery';

    /**
     * Main Directory for uploaded Images, empty String for none
     *
     * @string
     */
    private static $image_directory_name = 'images';

    /**
     * Subdirectory for uploaded Images. Available options:
     * 'parent', 'class/parent', 'element', 'class/element', 'parent/element', '' (empty: disabled)
     *
     * @string
     */
    private static $image_directory_sub_struct = 'parent/element';

    private static $db = [
        'FullWidth' => 'Boolean',
        'ShowThumbnailGaps' => 'Boolean',
        'ShowThumbnailTitle' => 'Boolean',
        'ShowZoomTitle' => 'Boolean',
    ];

    private static $has_many = [
        'Images' => ObjectGalleryImage::class,
    ];

    private static $owns = [
        'Images',
    ];

    private static $defaults = [
        'FullWidth' => false,
        'ShowThumbnailGaps' => true,
        'ShowThumbnailTitle' => false,
        'ShowZoomTitle' => false,
    ];

    public function populateDefaults()
    {
        $this->ShowThumbnailGaps = true;
        parent::populateDefaults();
    }

    private static $inline_editable = false;

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(['Images']);

        $gridFieldImagesConfig = GridFieldConfig_RecordEditor::create();
        $gridFieldImagesConfig->addComponent(GridFieldOrderableRows::create());
        $gridFieldImagesConfig->removeComponentsByType([GridFieldPageCount::class, GridFieldToolbarHeader::class, GridFieldFilterHeader::class]);
        if (class_exists(BulkUploader::class)) {
            $gridFieldImagesConfig->removeComponentsByType([GridFieldAddNewButton::class]);
            $gridFieldImagesConfig->addComponent(new BulkUploader());
            $gridFieldImagesConfig->getComponentByType(BulkUploader::class)
                ->setUfSetup('setFolderName', $this->generateUploadDirectory());
        }
        $gridFieldImages = GridField::create('Images', 'Images', $this->Images());
        $gridFieldImages->setConfig($gridFieldImagesConfig);

        $fields->addFieldsToTab('Root.Main', [
            $gridFieldImages,
            // $fieldGImages,
        ]);

        $fieldFullWidth = CheckboxField::create('FullWidth', _t(__CLASS__ . '.FullWidth', 'Display at Full Page Width'));

        $fieldShowThumbnailGaps = CheckboxField::create('ShowThumbnailGaps', _t(__CLASS__ . '.ShowThumbnailGaps', 'Display a Gaps between thumbnails'));

        $fieldShowThumbnailTitle = CheckboxField::create('ShowThumbnailTitle', _t(__CLASS__ . '.ShowThumbnailTitle', 'Display image title at thumbnail'));

        $fieldShowZoomTitle = CheckboxField::create('ShowZoomTitle', _t(__CLASS__ . '.ShowZoomTitle', 'Display image title on zoom'));

        $fields->addFieldsToTab('Root.Settings', [
            $fieldFullWidth,
            $fieldShowThumbnailGaps,
            $fieldShowThumbnailTitle,
            $fieldShowZoomTitle,
        ]);

        return $fields;
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
        $parentPage = $this->Parent()->getOwnerPage();

        // Make base path
        if (!empty($configuredDirectory)) {
            $normalizedDirectory = $filter->filter($configuredDirectory);
            $uploadPath = $normalizedDirectory . '/';
        }

        // Make detailed path
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
        } elseif ($configuredSubDirectory == 'parent/element' && $parentPage && $parentPage->exists() && !empty($this->Title)) {
            $parentPageSegment = $filter->filter($parentPage->Title);
            $titleSegment = $filter->filter($this->Title);
            $uploadPath .=  $parentPageSegment . '/' . $titleSegment . '/';
        }

        return $uploadPath;
    }

    /**
     * Retrieves images that are enabled and have an associated image file,
     * while ensuring the current user has permission to view the image.
     *
     * @return DataList
     */
    public function FilteredImages()
    {
        $images = $this->Images()->filter(['Enabled' => true])
            ->filterByCallback(function ($image) {
                return $image->canViewImage();
            });

        return $images;
    }

    public function getSummary(): string
    {
        return _t(__CLASS__ . '.Summary', 'Displays images as gallery.');
    }

    public function getType()
    {
        return _t(__CLASS__ . '.Type', 'Gallery');
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
    }

}
