<?php

namespace Chewyou\Algolia\Service;

use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Dev\Debug;

require_once(__DIR__ . '/../vendor/algoliasearch-client-php-master/algoliasearch.php');

class AlgoliaIndexer
{
    private $item;
    private $apiKey;
    private $applicationID;
    private $indexName;
    private $valuesToIndex;
    private $blockArray;

    public function __construct($item, $valuesToIndex, $blockArray)
    {
        $siteConfig = SiteConfig::current_site_config();

        $this->item = $item;
        $this->apiKey = $siteConfig->adminAPIKey;
        $this->applicationID = $siteConfig->applicationID;
        $this->indexName = $siteConfig->indexName;
        $this->valuesToIndex = $valuesToIndex;
        $this->blockArray = $blockArray;
    }

    public function indexData()
    {
        $item = $this->item;
        $valuesToIndex = $this->valuesToIndex;
        $blockArray = $this->blockArray;

        $client = new \AlgoliaSearch\Client($this->applicationID, $this->apiKey);
        $searchIndex = $client->initIndex($this->indexName);

        // Index Unique Identifier
        $toIndex = ['objectID' => md5($item->ID. "_" . $item->ClassName)];

        // Index values entered in CMS
        foreach ($valuesToIndex as $value) {
            // Strip html
            $refinedValue = str_replace("\n", " ", strip_tags($item->$value));
            $toIndex['object'.$value] = $refinedValue;
        }

        // Index Tags
        $tagNames = [];
        foreach ($item->TagNames() as $tagName) {
            array_push($tagNames, $tagName->Title);
        }

        $toIndex['objectTagNames'] = $tagNames;
        $toIndex['objectSearchable'] = $item->Searchable;
        $toIndex['objectURL'] = $item->AbsoluteLink();

        $toIndex['objectContentBlocks'] = $blockArray;

        $searchIndex->addObject($toIndex);
    }

    public function deleteData()
    {
        $item = $this->item;

        $client = new \AlgoliaSearch\Client($this->applicationID, $this->apiKey);
        $searchIndex = $client->initIndex($this->indexName);

        $searchIndex->deleteObject(md5($item->ID. "_" . $item->ClassName));
    }
}