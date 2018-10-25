<?php

namespace Chewyou\Algolia\Jobs;

use Chewyou\Algolia\Service\AlgoliaIndexer;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Config\Config;
use Symbiote\QueuedJobs\Services\AbstractQueuedJob;
use Symbiote\QueuedJobs\Services\QueuedJob;
use Symbiote\QueuedJobs\Services\QueuedJobService;
use SilverStripe\Dev\Debug;

if (!class_exists(AbstractQueuedJob::class)) {
    return;
}

class AlgoliaReIndex extends AbstractQueuedJob implements QueuedJob
{
    public function getTitle()
    {
        return "Algolia - Re-index Site";
    }

    public function getJobType()
    {
        return QueuedJob::IMMEDIATE;
    }

    public function setup()
    {
        parent::setup();
    }

    public function process()
    {
        //Have array of page classnames and object classnames to be indexed

        //Get array of values to index from siteconfig
        $siteConfig = SiteConfig::current_site_config();
        $indexValues = str_replace(' ', '', $siteConfig->indexValues);
        $valuesToIndex = explode(',', $indexValues);


        //clear out the current index in Algolia


        //for each classname (page/object) get the classname

                //index with values
                // $indexer = new AlgoliaIndexer($item, $valuesToIndex);
                // $indexer->indexData();


        echo "Site Re-Indexed";

        $this->isComplete = true;
        return;
    }

}
