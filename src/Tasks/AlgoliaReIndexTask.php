<?php

namespace Chewyou\Algolia\Tasks;

use SilverStripe\Dev\BuildTask;
use SilverStripe\Dev\Debug;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Control\HTTPRequest;
use Chewyou\Algolia\Services\AlgoliaIndexService;

class AlgoliaReIndexTask extends BuildTask {
    private static $segment = 'AlgoliaReIndexTask';

    public function run($request) {
        $service = new AlgoliaIndexService();

        return $service->run();
    }
}
