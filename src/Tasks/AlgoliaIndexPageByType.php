<?php

namespace Chewyou\Algolia\Tasks;

use SilverStripe\Dev\BuildTask;
use SilverStripe\Dev\Debug;
use SilverStripe\SiteConfig\SiteConfig;

require_once(__DIR__ . '/../vendor/algoliasearch-client-php-master/algoliasearch.php');

class AlgoliaIndexPageByType extends BuildTask
{
    protected $title = 'Algolia: Index Page by Type';

    protected $description = 'Algolia: Index pages by ONE page type (ClassName)';

    protected $enabled = true;

    private $apiKey;
    private $applicationID;
    private $indexName;

    public function run($request)
    {
        $siteConfig = SiteConfig::current_site_config();

        if (isset($_GET['pagetype']) && isset($_GET['values'])) {
            $this->apiKey = $siteConfig->adminAPIKey;
            $this->applicationID = $siteConfig->applicationID;
            $this->indexName = $siteConfig->indexName;
            $pagetype = $_GET['pagetype'];
            $values = $_GET['values'];

            Debug::dump("Pagetype entered: \n" . $pagetype);
            Debug::dump("Values entered: \n" . $values);

            $this->indexTypeOf($pagetype, $values);

        } else {
            Debug::dump("In URL add: 
            \n?pagetype={pagetype}&values={Title,Subtitle,Content}
            \nID is added automatically.
            \nValues are comma separated, no spaces.
            \n\ne.g. ?pagetype=BlogPage&values=Title,Subtitle,Author,Content");
        }
    }

    public function indexTypeOf($pagetype, $values)
    {
        $client = new \AlgoliaSearch\Client($this->applicationID, $this->apiKey);
        $searchIndex = $client->initIndex($this->indexName);

        $items = $pagetype::get();
        $valuesToIndex = explode(',', $values);

        $count = 0;
        foreach ($items as $item) {

            $toIndex = ['objectID' => $item->ID];
            foreach ($valuesToIndex as $value) {
                // Strip html
                $refinedValue = str_replace("\n", " ", strip_tags($item->$value));

                $toIndex['object'.$value] = $refinedValue;
            }

            $searchIndex->addObject($toIndex);
            $count ++;
        }

        Debug::dump("Number of pages indexed: " . $count);
        Debug::dump("See index at https://www.algolia.com/apps/".$this->applicationID."/explorer/browse/".$this->indexName);
    }
}
