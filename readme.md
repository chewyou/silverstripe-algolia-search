# Algolia Search and Indexer
###### For Silverstripe 3 (no support)


## Core Setup

Add the following to all the page types you need indexed. Put on a base page to encompass all child pages.

    private static $enable_indexer = true;
      
    
## CMS Setup
Under Settings > Algolia Search Configuration, enter your Algolia account details

- Admin API Key
- Search API Key
- Application ID
- Index Name    
- Values to index    
    
and the Field Values you want indexed. ie. Title,Content,MenuTitle 
    
    
## Theme Setup    

Copy the directory */algolia-search* to your theme */src/js/components* directory

Refer to */theme/src/js/main.js* for inspiration (ES6 is used)

Note the *search-config.js* (especially) and *search-algolia.js* files shouldn't be touched. 

*search-action.js* however should be adjusted based on the values indexed and result layout 





##### To include the search and results template on a template page

    <% include AlgoliaSearchResults %>

This is a guide to go with the JS and should also be overridden with your own implementation 





### npm requirements

##### algoliasearch
     
     npm install algoliasearch --save
     
##### jquery

     npm install jquery --save
     
     
     
     
     
## Indexing existing pages

Run the dev task *Algolia: Index Page by Type* where:

- PageType is the classname of the page
- Values are comma separated, no spaces, case sensitive and are $db values (ie. &values=Title,Content,DatePublished)


    /dev/tasks/AlgoliaIndexPageByType&pagetype={PageType}&values={Title,Content,LastEdited}
