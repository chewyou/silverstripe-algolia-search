<?php

require_once(__DIR__ . '/../vendor/algoliasearch-client-php-master/algoliasearch.php');

class AlgoliaIndexer
{
    private $item;
    private $apiKey;
    private $applicationID;
    private $indexName;
    private $valuesToIndex;

    public function __construct($item)
    {
        $siteConfig = SiteConfig::current_site_config();

        $this->item = $item;
        $this->apiKey = $siteConfig->adminAPIKey;
        $this->applicationID = $siteConfig->applicationID;
        $this->indexName = $siteConfig->indexName;
        $this->valuesToIndex = $siteConfig->valuesToIndex;
    }

    public function indexData()
    {
        $item = $this->item;

        $valuesToIndex = explode(',', $this->valuesToIndex);

        $client = new \AlgoliaSearch\Client($this->applicationID, $this->apiKey);
        $searchIndex = $client->initIndex($this->indexName);

        $toIndex = ['objectID' => $item->ID, 'objectSearchable' => $item->Searchable];
        foreach ($valuesToIndex as $value) {
            // Strip html
            $refinedValue = str_replace("\n", " ", strip_tags($item->$value));

            $toIndex['object' . $value] = $refinedValue;
        }

        $searchIndex->addObject($toIndex);
    }

    public function deleteData()
    {
        $item = $this->item;

        $client = new \AlgoliaSearch\Client($this->applicationID, $this->apiKey);
        $searchIndex = $client->initIndex($this->indexName);

        $searchIndex->deleteObject($item->ID);
    }
}