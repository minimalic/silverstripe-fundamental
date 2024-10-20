<?php

namespace minimalic\Fundamental\Objects;

use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\Assets\File;
use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\View\Parsers\URLSegmentFilter;
use SilverStripe\LinkField\Form\MultiLinkField;
use SilverStripe\LinkField\Models\Link;
use minimalic\Fundamental\Modules\ModuleTeaser;

class ObjectTeaserItem extends DataObject
{

    private static $singular_name = 'Teaser Item';

    private static $plural_name = 'Teaser Items';

    private static $description = 'Teaser Item';

    private static $table_name = 'ModularObject_TeaserItem';

    private static $db = [
        'Sort' => 'Int',
        'Title' => 'Varchar',
        'Content' => 'HTMLText',
        'Enabled' => 'Boolean',
    ];

    private static $has_one = [
        'Image' => Image::class,
        'Icon' => File::class,
        'ModuleTeaser' => ModuleTeaser::class,
    ];

    private static $has_many = [
        'Links' => Link::class . '.Owner',
    ];

    private static $owns = [
        'Image',
        'Icon',
        'Links',
    ];

    private static array $cascade_deletes = [
        'Image',
        'Icon',
        'Links',
    ];

    private static array $cascade_duplicates = [
        'Image',
        'Icon',
        'Links',
    ];

    private static $summary_fields = [
        'GridFieldThumbnail' => 'Image',
        'Icon',
        'Title',
        'Content',
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

        $fields->removeByName(['Sort', 'ModuleTeaserID', 'Links', 'Title', 'Content']);

        $fieldEnabled = CheckboxField::create('Enabled', _t(__CLASS__ . '.Enabled', 'Enable Item'));

        $fieldImage = UploadField::create('Image');
        $fieldImage->getValidator()->setAllowedExtensions(array('png', 'jpg', 'jpeg'));
        if ($this->Parent()) {
            $imageUploadPath = $this->Parent()->generateUploadDirectory();
            if (!empty($imageUploadPath) && $imageUploadPath != '/') {
                $fieldImage->setFolderName($imageUploadPath);
            }
        }

        $fields->addFieldsToTab('Root.Settings', [
            $fieldEnabled,
            $fieldImage,
        ]);

        $fieldIcon = UploadField::create('Icon');
        $fieldIcon->getValidator()->setAllowedExtensions(array('png', 'svg'));
        if ($this->Parent()) {
            $iconUploadPath = $this->Parent()->generateUploadDirectory();
            if (!empty($iconUploadPath) && $iconUploadPath != '/') {
                $fieldIcon->setFolderName($iconUploadPath);
            }
        }

        $fieldTitle = TextField::create('Title', 'Title');

        $fieldContent = HTMLEditorField::create('Content', 'Text content');
        $fieldContent->setRows(10);

        $fieldLinks = MultiLinkField::create('Links', _t(__CLASS__ . '.Links', 'Links'));

        $fields->addFieldsToTab('Root.Main', [
            $fieldIcon,
            $fieldTitle,
            $fieldContent,
            $fieldLinks,
        ]);

        return $fields;
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
        if ($this->ModuleTeaser()) {
            return $this->ModuleTeaser();
        }

        return null;
    }

    /**
     * Set starting sort number (before writing to the database)
     */
    public function onBeforeWrite() {
        parent::onBeforeWrite();

        if (!$this->Sort) {
            $this->Sort = ObjectTeaserItem::get()->max('Sort') + 1;
        }
    }

}
