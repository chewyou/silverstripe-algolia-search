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

        if ($siteConfig->searchAPIKey && $siteConfig->applicationID && $siteConfig->indexName) {
            $js_config = [
                'apiKeyValue'        => $siteConfig->searchAPIKey,
                'applicationIDValue' => $siteConfig->applicationID,
                'indexNameValue'     => $siteConfig->indexName
            ];
        } else {
            $js_config = [
                'apiKeyValue'        => null,
                'applicationIDValue' => null,
                'indexNameValue'     => null
            ];
        }

        Requirements::javascriptTemplate($theme.$siteConfig->searchConfigLocation, $js_config);
    }
}
