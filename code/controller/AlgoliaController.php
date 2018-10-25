<?php

class AlgoliaController extends DataExtension
{
    public function onAfterInit()
    {
        $siteConfig = SiteConfig::current_site_config();

        $theme = SSViewer::get_theme_folder();

        $js_config = [
            'apiKeyValue'        => $siteConfig->searchAPIKey,
            'applicationIDValue' => $siteConfig->applicationID,
            'indexNameValue'     => $siteConfig->indexName
        ];

        Requirements::javascriptTemplate($theme.'/src/js/components/algolia-search/search-config.js', $js_config);
    }
}
