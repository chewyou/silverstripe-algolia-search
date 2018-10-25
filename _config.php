<?php

use SilverStripe\Control\Director;

define('ALGOLIA_SEARCH', ltrim(Director::makeRelative(realpath(__DIR__)), DIRECTORY_SEPARATOR));
