<?php

namespace minimalic\Fundamental\Modules;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldPageCount;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\View\Parsers\URLSegmentFilter;
use DNADesign\Elemental\Models\BaseElement;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use minimalic\Fundamental\Objects\ObjectTeaserItem;

class ModuleTeaser extends BaseElement
{
    private static $icon = 'font-icon-block-layout-10';

    private static $singular_name = 'Teaser Block';

    private static $plural_name = 'Teaser Blocks';

    private static $description = 'Block with teaser items';

    private static $table_name = 'ModuleTeaser';

    /**
     * Main Directory for uploaded Images and Icons, empty String for none
     *
     * @string
     */
    private static $image_directory_name = 'images';

    /**
     * Subdirectory for uploaded Images and Icons. Available options:
     * 'parent', 'class/parent', 'element', 'class/element', 'parent/element', '' (empty: disabled)
     *
     * @string
     */
    private static $image_directory_sub_struct = 'parent/element';

    private static $db = [
        'FullWidth' => 'Boolean',
        'ShowItemGaps' => 'Boolean',
    ];

    private static $has_many = [
        'Items' => ObjectTeaserItem::class,
    ];

    private static $owns = [
        'Items',
    ];

    private static $defaults = [
        'FullWidth' => false,
        'ShowItemGaps' => true,
    ];

    private static array $cascade_deletes = [
        'Items',
    ];

    private static array $cascade_duplicates = [
        'Items',
    ];

    public function populateDefaults()
    {
        $this->ShowItemGaps = true;
        $this->LightboxEnabled = true;
        $this->ShowLightboxTitle = true;
        parent::populateDefaults();
    }

    private static $inline_editable = false;

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(['Items', 'ShowItemGaps']);

        $itemsGridFieldConfig = GridFieldConfig_RecordEditor::create();
        $itemsGridFieldConfig->addComponent(GridFieldOrderableRows::create());
        $itemsGridFieldConfig->removeComponentsByType([GridFieldPageCount::class, GridFieldToolbarHeader::class, GridFieldFilterHeader::class]);
        $itemsGridField = GridField::create('Items', _t(__CLASS__ . '.Items', 'Teaser Blocks'), $this->Items());
        $itemsGridField->setConfig($itemsGridFieldConfig);

        $fields->addFieldsToTab('Root.Main', [
            $itemsGridField,
        ]);

        $fieldFullWidth = CheckboxField::create('FullWidth', _t(__CLASS__ . '.FullWidth', 'Display at Full Page Width'));

        $fieldShowItemGaps = CheckboxField::create('ShowItemGaps', _t(__CLASS__ . '.ShowItemGaps', 'Display a gap between items'))->addExtraClass('d-block');

        $fields->addFieldsToTab('Root.Settings', [
            $fieldFullWidth,
            $fieldShowItemGaps,
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
     * Retrieves items that are enabled,
     * while ensuring the current user has permission to view the image.
     *
     * @return DataList
     */
    public function FilteredItems()
    {
        $items = $this->Items()->filter(['Enabled' => true]);

        return $items;
    }

    public function getSummary(): string
    {
        return _t(__CLASS__ . '.Summary', 'Displays teaser item with icon/image and buttons.');
    }

    public function getType()
    {
        return _t(__CLASS__ . '.Type', 'Teaser');
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
    }

}
