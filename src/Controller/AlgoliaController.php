<?php

namespace Chewyou\Algolia\Controller;

use SilverStripe\ORM\DataExtension;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\View\ThemeResourceLoader;
use SilverStripe\View\Requirements;

class AlgoliaController extends DataExtension
{
    public function onAfterInit()
    {
        $siteConfig = SiteConfig::current_site_config();

        $theme = ThemeResourceLoader::inst()->getPath('simple');

        $js_config = [
            'apiKeyValue'        => $siteConfig->searchAPIKey,
            'applicationIDValue' => $siteConfig->applicationID,
            'indexNameValue'     => $siteConfig->indexName
        ];

//        Requirements::javascriptTemplate($theme.'/src/js/components/algolia-search/search-config.js', $js_config);
    }
}
