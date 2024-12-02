<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Listener\Queue;

use Illuminate\Queue\Events\JobQueued;
use Illuminate\Queue\Events\JobProcessing;
use Matusboa\LaravelExporter\Enum\JobMetricTypeEnum;
use Matusboa\LaravelExporter\Contract\QueueMetricsStoreInterface;

class JobQueuedListener
{
    /**
     * @param \Matusboa\LaravelExporter\Contract\QueueMetricsStoreInterface $queueMetricsStore
     */
    public function __construct(
        protected readonly QueueMetricsStoreInterface $queueMetricsStore,
    ) {
    }

    /**
     * @param \Illuminate\Queue\Events\JobQueued $event
     */
    public function handle(JobQueued $event): void
    {
        $this->queueMetricsStore->incrementJobsCount($event->job->getQueue(), JobMetricTypeEnum::QUEUED);
    }
}
