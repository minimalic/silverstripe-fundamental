<?php

namespace minimalic\Fundamental\Modules;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\CheckboxField;
// use SilverStripe\Forms\LabelField;
// use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\NumericField;
// use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FieldGroup;

use DNADesign\Elemental\Models\BaseElement;

class ModuleImage extends BaseElement
{
    private static $icon = 'font-icon-block-file';

    private static $singular_name = 'Image Block';

    private static $plural_name = 'Image Blocks';

    private static $description = 'Block with single image banner';

    private static $table_name = 'ModuleImage';
    // private static $table_name = 'm_EB_Image';

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

        $fields->removeByName(['Width', 'Height']);

        $fieldFullWidth = CheckboxField::create('FullWidth', _t(__CLASS__ . '.FullWidth', 'Display at Full Page Width'));

        $fieldAllowUpscale = CheckboxField::create('AllowUpscale', _t(__CLASS__ . '.AllowUpscale', 'Allow Image Upscale'));
        $fieldAllowUpscale->setDescription(_t(__CLASS__ . '.AllowUpscaleDescription', 'Upscale will only apply on the website, not on the image file itself.'));

        // $optionalFieldsInfoText = '<h1>Test L</h1><p>Test L</p><p></p>';
        // $optionalFieldsInfoField = LiteralField::create('OptinalFieldsInfo', $optionalFieldsInfoText);

        // $fieldWidth = TextField::create('Width', 'Width')
        //         ->setAttribute('type', 'number')
        //         ->setAttribute('pattern', '\\d{1,4}')
        //         ->setAttribute('step', '1')
        //         ->setAttribute('maxlength', '4');

        $fieldWidth = NumericField::create('Width', _t(__CLASS__ . '.Width', 'Width'));
        // $fieldWidth->setHTML5(true);
        $fieldWidth->setScale(0); // integer
        $fieldWidth->setMaxLength(5); // not working with HTML5 enabled

        $fieldHeight = NumericField::create('Height', _t(__CLASS__ . '.Height', 'Height'));
        // $fieldHeight->setHTML5(true);
        $fieldHeight->setScale(0); // integer
        $fieldHeight->setMaxLength(5); // not working with HTML5 enabled

        $fieldDimensions = FieldGroup::create(
            $fieldWidth,
            $fieldHeight
        )->setTitle(_t(__CLASS__ . '.Dimensions', 'Dimensions'))->setDescription(_t(__CLASS__ . '.DimensionsDescription', "Enter the dimensions in pixels. Set 0 for the image’s default size.<br>Image will be scaled and cropped to fit the new resolution. If only one value is set, the image will maintain its original aspect ratio.<br>Upscaling is disabled."));

        // $alternativeInfoText = 'Test';
        // $alternativeInfoTextField = LabelField::create('ImageDimensionsInfo', $alternativeInfoText);

        $fields->addFieldsToTab('Root.Settings', [
            $fieldFullWidth,
            $fieldAllowUpscale,
            // $optionalFieldsInfoField,
            // $fieldWidth,
            // $fieldHeight,
            $fieldDimensions,
            // $alternativeInfoTextField,
        ]);

        return $fields;
    }

    public function getSummary(): string
    {
        return _t(__CLASS__ . '.Summary', 'Displays an image as a banner.');
    }

    public function getType()
    {
        return _t(__CLASS__ . '.Type', 'Image');
    }

    public function onBeforeWrite() {
        parent::onBeforeWrite();

        if (isset($this->Width)) {
            $cleanWidth = preg_replace('/\D/', '', $this->Width);

            $this->Width = substr($cleanWidth, 0, 5);
        }

        if (isset($this->Height)) {
            $cleanHeight = preg_replace('/\D/', '', $this->Height);

            $this->Height = substr($cleanHeight, 0, 5);
        }
    }

}
