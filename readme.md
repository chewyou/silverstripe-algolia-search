# Algolia Search and Indexer

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/325225367e764d6ca68991bc98cac083)](https://www.codacy.com/manual/benspickett/silverstripe-algolia-search?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=chewyou/silverstripe-algolia-search&amp;utm_campaign=Badge_Grade)

## Requirements
-   Silverstripe 4+
-   NPM

## Installation

```text
composer require chewyou/silverstripe-algolia-search
```

You may need to add the repository to the composer.json file, as below:

```json
"repositories": 
    [
        {
            "type": "vcs",
            "url": "https://github.com/chewyou/silverstripe-algolia-search.git"
        }
    ],
```

To include the Algolia Search JS files:

```text
npm install algoliasearch jquery --save
```

## Core Setup

Add the following to all the page types you need indexed. Put on a base page to encompass all child pages.

```php
  private static $enable_indexer = true;
```

## CMS Setup

Under Settings > Algolia Search Configuration, enter your Algolia account details.

-   Admin API Key
-   Search API Key
-   Application ID
-   Index Name

## Theme Setup

Copy the directory _/algolia-search_ to your theme _/src/js/components_
directory. Line 32 of AlgoliaController.php looks for this file.

Refer to _/theme/src/js/main.js_ for inspiration (ES6 is used) Note the
_search-config.js_ (especially) and _search-algolia.js_ files shouldn't be touched.

_search-action.js_ however should be adjusted based on the values indexed and result layout

### To include the search and results template on a template page

```silverstripe
<% include AlgoliaSearchResults %>
```

This is an example/guide to go with the JS and should also be overridden with your own
implementation

## Indexing existing pages (two options)

### Option 1

Run the dev task _Algolia: Index Page by Type_ where:

-   PageType is the classname of the page
-   Values are comma separated, no spaces, case sensitive and are \$db values (ie. &values=Title,Content,LastEdited)

### Option 2

Or go through and save/publish all of the pages to index
/dev/tasks/AlgoliaIndexPageByType&pagetype={PageType}&values={Title,Content,LastEdited}

## To Do

_Update this project to only use ElementalBlocks_
