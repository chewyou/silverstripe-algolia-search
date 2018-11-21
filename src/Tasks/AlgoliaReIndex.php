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
use SilverStripe\CMS\Model\SiteTree;
use DNADesign\Elemental\Models\BaseElement;


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
        $siteConfig = SiteConfig::current_site_config();

        // Should be adjusted based on what needs to be indexed in the siteconfig
        $indexValues = str_replace(' ', '', $siteConfig->indexValues);
        $valuesToIndex = explode(',', $indexValues);

        $pages = SiteTree::get();

        foreach ($pages as $page) {

            $blocks = $page->ElementalArea()->Elements();
            $blockArray = [];
            foreach ($blocks as $block) {
                $blockItem['Title'] = $block->Title;
                $blockItem['Content'] = $block->Content;
                array_push($blockArray, $blockItem);
            }

            $indexer = new AlgoliaIndexer($page, $valuesToIndex, $page->IndexContentBlocks, $blockArray);
            $indexer->indexData();
        }

        echo "Site Re-Indexed";

        $this->isComplete = true;
        return;
    }

}
