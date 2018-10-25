<?php

class PageExtension extends DataExtension
{
    private static $enable_indexer = false;

    private static $db = [
        'Searchable' => 'Boolean'
    ];

    public function enable_indexer()
    {
        return $this->owner->stat('enable_indexer') ? true : false;
    }

    public function updateCMSFields(FieldList $fields)
    {
        if ($this->owner->enable_indexer()) {
            $fields->addFieldsToTab('Root.Search Settings', [
                OptionsetField::create('Searchable', 'Show in Search?')
                    ->setSource([true => 'Yes', false => 'No']),
                LiteralField::create('LastUpdated', 'Last indexed: ' . $this->owner->LastEdited . '')
            ]);
        }
    }

    public function onBeforeWrite()
    {
        if ($this->owner->enable_indexer()) {
            $indexer = new AlgoliaIndexer($this->owner);
            $indexer->indexData();
        }

        parent::onBeforeWrite();
    }

    public function onBeforeDelete()
    {
        if ($this->owner->enable_indexer()) {
            $indexer = new AlgoliaIndexer($this->owner);
            $indexer->deleteData();
        }

        parent::onBeforeDelete();
    }
}
