<?php

namespace Chewyou\Algolia\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextAreaField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use Chewyou\Algolia\Model\TagName;

class AlgoliaSearchSiteConfigExtension extends DataExtension {
    private static $db = [
        'adminAPIKey' => 'Varchar(150)',
        'searchAPIKey' => 'Varchar(150)',
        'applicationID' => 'Varchar(50)',
        'indexName' => 'Varchar(100)',
        'indexValues' => 'Text',
        'searchConfigLocation' => 'Varchar(255)',
        'usingBlocks' => 'Boolean(0)'
    ];

    private static $has_many = [
        'TagNames' => TagName::class
    ];

    public function updateCMSFields(FieldList $fields) {
        $config = GridFieldConfig_RelationEditor::create();

        $fields->addFieldsToTab('Root.AlgoliaSearchConfiguration', [
            TabSet::create('Algolia Search Configuration',
                Tab::create('API Details',
                    TextField::create('adminAPIKey', 'Admin API Key'),
                    TextField::create('searchAPIKey', 'Search API Key'),
                    TextField::create('applicationID', 'Application ID'),
                    TextField::create('indexName', 'Index Name')
                ),
                Tab::create('Index Values',
                    TextAreaField::create('indexValues', 'Index Values')
                        ->setDescription('Comma separated database values please')
                ),
                Tab::create('Tags',
                    $gridField = GridField::create('TagNames', 'TagNames', $this->owner->TagNames())
                ),
                Tab::create('Configuration',
                    TextField::create('searchConfigLocation', 'Search Config JS File Location')
                        ->setDescription('eg: /src/js/components/algolia-search/search-config.js'),
                    OptionsetField::create('usingBlocks', 'Is this project using Elemental Blocks?')
                        ->setSource([true => 'Yes', false => 'No'])
                        ->setDescription('Part of the future endeavours. Does not do anything.... yet')
                )
            )
        ]);

        $gridField->setConfig($config);
    }
}
