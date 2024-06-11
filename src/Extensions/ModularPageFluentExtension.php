<?php

namespace minimalic\Fundamental\Extensions;

use SilverStripe\Core\Extension;
use TractorCow\Fluent\Model\Locale;

class ModularPageFluentExtension extends Extension
{
    /**
     * Override default Fluent fallback
     *
     * @param string $query
     * @param string $table
     * @param string $field
     * @param Locale $locale
     */
    public function updateLocaliseSelect(&$query, $table, $field, Locale $locale)
    {
        if (class_exists(Locale::class)) {
            // disallow elemental data inheritance in the case that published localised page instance already exists
            if ($field == 'ElementalAreaID' && $this->owner->isPublishedInLocale()) {
                $query = '"' . $table . '_Localised_' . $locale->getLocale() . '"."' . $field . '"';
            }
        }
    }
}
