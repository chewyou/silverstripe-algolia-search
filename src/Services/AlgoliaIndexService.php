<?php

namespace Chewyou\Algolia\Services;

use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\CMS\Model\SiteTree;
use DNADesign\Elemental\Models\BaseElement;
use Silverstripe\Versioned\Versioned;
use SilverStripe\ORM\DataObject;
use SilverStripe\Dev\Debug;
use SilverStripe\Core\Config\Config;

class AlgoliaIndexService
{
    public function run()
    {
        $siteConfig = SiteConfig::current_site_config();

        // Should be adjusted based on what needs to be indexed in the siteconfig
        $indexValues = str_replace(' ', '', $siteConfig->indexValues);
        $valuesToIndex = explode(',', $indexValues);

        $pages = Versioned::get_by_stage(SiteTree::class, 'Live');

        foreach ($pages as $page) {
            $blockArray = [];

            if ($page->hasMethod('ElementalArea')) {
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
            }

            $indexer = new AlgoliaIndexer($page, $valuesToIndex, $blockArray);
            $indexer->indexData();
        }

        return true;
    }
}
