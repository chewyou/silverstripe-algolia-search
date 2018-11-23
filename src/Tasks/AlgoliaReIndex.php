<?php

namespace Chewyou\Algolia\Jobs;

use Chewyou\Algolia\Service\AlgoliaIndexer;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Config\Config;
use Symbiote\QueuedJobs\Services\AbstractQueuedJob;
use Symbiote\QueuedJobs\Services\QueuedJob;
use Symbiote\QueuedJobs\Services\QueuedJobService;
use SilverStripe\CMS\Model\SiteTree;
use DNADesign\Elemental\Models\BaseElement;
use Silverstripe\Versioned\Versioned;
use SilverStripe\ORM\DataObject;
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
        $siteConfig = SiteConfig::current_site_config();

        // Should be adjusted based on what needs to be indexed in the siteconfig
        $indexValues = str_replace(' ', '', $siteConfig->indexValues);
        $valuesToIndex = explode(',', $indexValues);

        $pages = Versioned::get_by_stage(SiteTree::class, 'Live');

        foreach ($pages as $page) {

            $blockArray = [];
            $blocks = $page->ElementalArea()->Elements();
            if (($blocks && $blocks->exists())) {
                foreach ($blocks as $block) {
                    $blockItem['Title'] = $block->Title;
                    // Strip HTML
                    $stripHTML = str_replace("\n", " ", strip_tags($block->Content));
                    $stripComponents = preg_replace('/[\[].*[\]]/U' , '', $stripHTML);
                    $blockItem['Content'] = $stripComponents;
                    array_push($blockArray, $blockItem);
                }
            }

            $indexer = new AlgoliaIndexer($page, $valuesToIndex, $blockArray);
            $indexer->indexData();
        }

        echo "Site Re-Indexed";

        $this->isComplete = true;
        return;
    }

}
