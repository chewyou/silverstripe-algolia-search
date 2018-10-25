<?php

namespace Chewyou\Algolia\Tasks;

use SilverStripe\Dev\BuildTask;
use SilverStripe\Dev\Debug;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Control\HTTPRequest;


class AlgoliaReIndexTask extends BuildTask
{
    public function run($request)
    {
        Debug::dump("Success");
    }
}
