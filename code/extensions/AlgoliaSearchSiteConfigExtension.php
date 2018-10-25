<?php

class AlgoliaSearchSiteConfigExtension extends DataExtension
{
    private static $db = [
        'adminAPIKey' => 'Varchar(150)',
        'searchAPIKey' => 'Varchar(150)',
        'applicationID' => 'Varchar(50)',
        'indexName' => 'Varchar(100)',
        'valuesToIndex' => 'Varchar(100)',
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab('Root.Algolia Configuration', [
            TabSet::create('Algolia',
                Tab::create('API Configuration',
                    HeaderField::create('WidgetInstructions', 'API Configuration', 3),
                    TextField::create('adminAPIKey', 'Admin API Key'),
                    TextField::create('searchAPIKey', 'Search API Key'),
                    TextField::create('applicationID', 'Application ID'),
                    TextField::create('indexName', 'Index Name'),
                    LiteralField::create('algoliaLink', '<a href="https://www.algolia.com/users/sign_in" target="_blank">Algolia.com</a> (opens in new tab)' )
                ),
                Tab::create('Indexing Config',
                    HeaderField::create('WidgetInstructions', 'Indexing Config', 3),
                    LiteralField::create('valuesToIndexInfo', '<p>The below values should be comma separated, no spaces, capitalised.</p>
                                        <p><strong>ID</strong> is indexed automatically</p> 
                                        <p style="color: #FF0000;"><strong>Only change these if you know what you are doing. You have been warned</strong></p>'),
                    TextField::create('valuesToIndex', 'Values To Index')
                )
            ),
        ]);
    }
}
