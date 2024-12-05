<?php

namespace Matusboa\LaravelExporter\Listener\Queue;

use Illuminate\Queue\Events\JobProcessing;
use Matusboa\LaravelExporter\Enum\JobMetricTypeEnum;
use Matusboa\LaravelExporter\Contract\Store\QueueMetricsStoreInterface;

class JobProcessingListener
{
    /**
     * @param \Matusboa\LaravelExporter\Contract\Store\QueueMetricsStoreInterface $queueMetricsStore
     */
    public function __construct(
        protected QueueMetricsStoreInterface $queueMetricsStore,
    ) {
    }

    /**
     * @param \Illuminate\Queue\Events\JobProcessing $event
     */
    public function handle(JobProcessing $event): void
    {
        $this->queueMetricsStore->incrementJobsCount($event->job->getQueue(), JobMetricTypeEnum::PROCESSING);
    }
}
