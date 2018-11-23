<?php

use Chewyou\Algolia\Service\AlgoliaIndexer;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\Map;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\CheckboxSetField;
use Chewyou\Algolia\DataObject\TagName;
use SilverStripe\Dev\Debug;

class PageExtension extends DataExtension
{
    private static $enable_indexer = true;

    private static $db = [
        'Searchable' => 'Boolean(1)',
        'IndexContentBlocks' => 'Boolean(1)'
    ];

    private static $many_many = [
        'TagNames' => TagName::class
    ];

    public function enable_indexer()
    {
        return $this->owner->stat('enable_indexer') ? true : false;
    }

    public function updateCMSFields(FieldList $fields)
    {
        if ($this->owner->enable_indexer()) {
            $fields->addFieldsToTab('Root.Search Settings', [
                TabSet::create('Search Settings',
                    Tab::create('Options',
                        OptionsetField::create('Searchable', 'Show in Search?')
                            ->setSource([true => 'Yes', false => 'No']),
                        OptionsetField::create('IndexContentBlocks', 'Index Content Blocks?')
                            ->setSource([true => 'Yes', false => 'No']),
                        LiteralField::create('LastUpdated', 'Last indexed: ' . $this->owner->LastEdited . '')
                    ),
                    Tab::create('Tags',
                        CheckboxSetField::create('TagNames', 'Tags', TagName::get()->map('ID', 'Title'))
                    )
                )
            ]);
        }
    }

    public function onAfterWrite()
    {
        if ($this->owner->enable_indexer()) {

            if ($this->owner->Searchable == 1) {
                $siteConfig = SiteConfig::current_site_config();

                // Should be adjusted based on what needs to be indexed in the siteconfig
                $indexValues = str_replace(' ', '', $siteConfig->indexValues);
                $valuesToIndex = explode(',', $indexValues);

                $blockArray = [];
                if ($this->owner->IndexContentBlocks == 1) {
                    $blocks = $this->owner->ElementalArea()->Elements();
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

                $indexer = new AlgoliaIndexer($this->owner, $valuesToIndex, $blockArray);
                $indexer->indexData();
            } else {
                $indexer = new AlgoliaIndexer($this->owner, null, null);
                $indexer->deleteData();
            }

        }

        parent::onAfterWrite();
    }

    public function onBeforeDelete()
    {
        if ($this->owner->enable_indexer()) {
            $indexer = new AlgoliaIndexer($this->owner, null, null);
            $indexer->deleteData();
        }

        parent::onBeforeDelete();
    }
}