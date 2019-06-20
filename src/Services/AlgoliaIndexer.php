<?php

namespace Chewyou\Algolia\Services;

use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Dev\Debug;
use Algolia\AlgoliaSearch\SearchClient;

class AlgoliaIndexer
{
    /**
     * @var DataObject
     */
    private $item;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $applicationID;

    /**
     * @var string
     */
    private $indexName;

    /**
     * @var array
     */
    private $valuesToIndex;

    /**
     * @var array
     */
    private $blockArray;

    /**
     * @param SilverStripe\ORM\DataObject $item
     * @param string[] $valuesToIndex
     * @param string[] $blocksArray
     */
    public function __construct($item = null, $valuesToIndex = [], $blockArray = [])
    {
        $siteConfig = SiteConfig::current_site_config();

        $this->item = $item;
        $this->apiKey = $siteConfig->adminAPIKey;
        $this->applicationID = $siteConfig->applicationID;
        $this->indexName = $siteConfig->indexName;
        $this->valuesToIndex = $valuesToIndex;
        $this->blockArray = $blockArray;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return ($this->applicationID && $this->apiKey);
    }

    /**
     * @return SearchClient
     */
    public function getClient()
    {
        return SearchClient::create($this->applicationID, $this->apiKey);
    }

    /**
     * @return boolean
     */
    public function indexData()
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $item = $this->item;
        $valuesToIndex = $this->valuesToIndex;
        $blockArray = $this->blockArray;

        $client = $this->getClient();
        $searchIndex = $client->initIndex($this->indexName);

        // Index Unique Identifier
        $toIndex = [
            'objectID' => md5($item->ID. "_" . $item->ClassName)
        ];

        // Index values entered in CMS
        if ($valuesToIndex) {
            foreach ($valuesToIndex as $value) {
                // Strip html
                $refinedValue = str_replace("\n", " ", strip_tags($item->$value));
                $toIndex['object'.$value] = $refinedValue;
            }
        }

        // Index Tags
        $tagNames = [];

        if ($item->hasMethod('TagNames')) {
            foreach ($item->TagNames() as $tagName) {
                array_push($tagNames, $tagName->Title);
            }
        }

        $toIndex['objectTagNames'] = $tagNames;
        $toIndex['objectSearchable'] = $item->Searchable;
        $toIndex['objectClassName'] = $item->ClassName;
        $toIndex['objectURL'] = $item->AbsoluteLink();
        $toIndex['objctContentBlocks'] = $blockArray;

        $searchIndex->saveObject($toIndex);

        return true;
    }

    /**
     * @return boolean
     */
    public function deleteData()
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $item = $this->item;

        $client = new \AlgoliaSearch\Client($this->applicationID, $this->apiKey);
        $searchIndex = $client->initIndex($this->indexName);

        $searchIndex->deleteObject(md5($item->ID. "_" . $item->ClassName));

        return true;
    }
}
