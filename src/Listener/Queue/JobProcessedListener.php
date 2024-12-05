<?php

namespace Matusboa\LaravelExporter\Listener\Queue;

use Illuminate\Queue\Events\JobProcessed;
use Matusboa\LaravelExporter\Enum\JobMetricTypeEnum;
use Matusboa\LaravelExporter\Contract\Store\QueueMetricsStoreInterface;

class JobProcessedListener
{
    /**
     * @param \Matusboa\LaravelExporter\Contract\Store\QueueMetricsStoreInterface $queueMetricsStore
     */
    public function __construct(
        protected QueueMetricsStoreInterface $queueMetricsStore,
    ) {
    }

    /**
     * @param \Illuminate\Queue\Events\JobProcessed $event
     */
    public function handle(JobProcessed $event): void
    {
        $this->queueMetricsStore->incrementJobsCount($event->job->getQueue(), JobMetricTypeEnum::PROCESSED);
    }
}
