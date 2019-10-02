<?php

namespace Chewyou\Algolia\Model;

use SilverStripe\ORM\DataObject;
use SilverStripe\SiteConfig\SiteConfig;
use Page;

class TagName extends DataObject {
    private static $singular_name = 'Tag Name';

    private static $plural_name = 'Tag Names';

    private static $db = [
        'Title' => 'Varchar(100)'
    ];

    private static $has_one = [
        'SiteConfig' => SiteConfig::class
    ];

    private static $belongs_many_many = [
        'Page' => Page::class
    ];

    private static $table_name = 'AlgoliaTagName';

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->removeByName('SiteConfigID');
        $fields->removeByName('LinkTracking');
        $fields->removeByName('FileTracking');
        return $fields;
    }
}
