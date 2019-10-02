<?php

namespace Chewyou\Algolia\Tests;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\Tab;
use SilverStripe\SiteConfig\SiteConfig;

class PageExtensionTest extends SapphireTest {
    public function testUpdateCMSFields() {
        $config = SiteConfig::current_site_config();

        $this->assertInstanceOf(Tab::class,
            $config->getCMSFields()->fieldByName('SearchSettings')
        );
    }
}
