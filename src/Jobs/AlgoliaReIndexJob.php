<?php

namespace Chewyou\Algolia\Jobs;

use Symbiote\QueuedJobs\Services\AbstractQueuedJob;
use Symbiote\QueuedJobs\Services\QueuedJob;
use Symbiote\QueuedJobs\Services\QueuedJobService;


if (!class_exists(AbstractQueuedJob::class)) {
    return;
}

class AlgoliaReIndexJob extends AbstractQueuedJob implements QueuedJob
{
    public function getTitle()
    {
        return "Algolia - Reindex Site";
    }

    public function getJobType()
    {
        return QueuedJob::QUEUED;
    }

    public function process()
    {
        $index = new AlgoliaReindexer();

        if ($index->run()) {
            $this->addMessage("Site Re-Indexed");
            $this->isComplete = true;
        }
    }
}
