<?php

namespace minimalic\Fundamental\Modules;

use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\View\Parsers\URLSegmentFilter;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldPageCount;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use DNADesign\Elemental\Models\BaseElement;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use minimalic\Fundamental\Objects\ObjectSlide;

class ModuleSlideshow extends BaseElement
{
    private static $icon = 'font-icon-block-carousel';

    private static $singular_name = 'Slideshow Block';

    private static $plural_name = 'Slideshow Blocks';

    private static $description = 'Block with image Slideshow';

    private static $table_name = 'ModuleSlideshow';

    private static $db = [
        'FullWidth' => 'Boolean',
        'Autoplay' => 'Boolean',
        'AutoplayInterval' => 'Int',
        'ShowCaptions' => 'Boolean',
        'ShowControls' => 'Boolean',
        'ShowIndicators' => 'Boolean',
        'SlideCrossfade' => 'Boolean',
        // 'ShowAltTitle' => 'Boolean',
        'Width' => 'Int',
        'Height' => 'Int',
    ];

    private static $has_many = [
        'Slides' => ObjectSlide::class,
    ];

    private static $owns = [
    ];

    private static $defaults = [
        'FullWidth' => false,
        'Autoplay' => true,
        'AutoplayInterval' => 3000,
        'ShowCaptions' => true,
        'ShowControls' => true,
        'ShowIndicators' => false,
        'SlideCrossfade' => false,
        'Width' => 0,
        'Height' => 0,
    ];

    public function populateDefaults()
    {
        $this->Autoplay = true;
        $this->ShowCaptions = true;
        $this->ShowControls = true;
        parent::populateDefaults();
    }

    private static $inline_editable = false;

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(['Slides', 'Width', 'Height', 'Autoplay', 'AutoplayInterval', 'ShowCaptions', 'ShowControls', 'ShowIndicators', 'SlideCrossfade']);

        $gridFieldSlidesConfig = GridFieldConfig_RecordEditor::create();
        $gridFieldSlidesConfig->addComponent(GridFieldOrderableRows::create());
        $gridFieldSlidesConfig->removeComponentsByType([GridFieldPageCount::class, GridFieldToolbarHeader::class, GridFieldFilterHeader::class]);
        $gridFieldSlides = GridField::create('Slides', 'Slides', $this->Slides());
        $gridFieldSlides->setConfig($gridFieldSlidesConfig);

        $fields->addFieldsToTab('Root.Main', [
            $gridFieldSlides,
        ]);

        $fieldFullWidth = CheckboxField::create('FullWidth', _t(__CLASS__ . '.FullWidth', 'Display at Full Page Width'));

        $fieldAutoplay = CheckboxField::create('Autoplay', _t(__CLASS__ . '.Autoplay', 'Autoplay'));

        $fieldAutoplayInterval = NumericField::create('AutoplayInterval', _t(__CLASS__ . '.AutoplayInterval', 'Autoplay interval in milliseconds (min. 300)'));
        $fieldAutoplayInterval->setScale(0);

        $fieldSlideAutoplay = FieldGroup::create(
            $fieldAutoplay,
            $fieldAutoplayInterval
        )->setTitle(_t(__CLASS__ . '.SlideAutoplay', 'Slide Autoplay'));

        $fieldShowCaptions = CheckboxField::create('ShowCaptions', _t(__CLASS__ . '.ShowCaptions', 'Display captions'));

        $fieldShowControls = CheckboxField::create('ShowControls', _t(__CLASS__ . '.ShowControls', 'Display slide controls'));

        $fieldShowIndicators = CheckboxField::create('ShowIndicators', _t(__CLASS__ . '.ShowIndicators', 'Display slide indicators'));

        $fieldSlideCrossfade = CheckboxField::create('SlideCrossfade', _t(__CLASS__ . '.SlideCrossfade', 'Crossfade effect'));

        $fieldSlideOptions = FieldGroup::create(
            $fieldShowCaptions,
            $fieldShowControls,
            $fieldShowIndicators,
            $fieldSlideCrossfade
        )->setTitle(_t(__CLASS__ . '.SlideOptions', 'Slide Options'));

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
            $fieldSlideAutoplay,
            $fieldSlideOptions,
            $fieldDimensions,
        ]);

        return $fields;
    }

    public function getSummary(): string
    {
        return _t(__CLASS__ . '.Summary', 'Displays images as slideshow/carousel.');
    }

    public function getType()
    {
        return _t(__CLASS__ . '.Type', 'Slideshow');
    }

    /**
     * Return Slides only with Image and Enabled
     *
     * @return Slides
     */
    public function FilteredSlides()
    {
        $slides = $this->Slides()->filter(['Enabled' => true, 'ImageID:not' => 0]);

        return $slides;
    }

    /**
     * Ensure only numeric input for dimensions (before writing to the database)
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