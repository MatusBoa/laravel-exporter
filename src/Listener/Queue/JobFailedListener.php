<?php

namespace Matusboa\LaravelExporter\Listener\Queue;

use Illuminate\Queue\Events\JobFailed;
use Matusboa\LaravelExporter\Enum\JobMetricTypeEnum;
use Matusboa\LaravelExporter\Contract\Store\QueueMetricsStoreInterface;

class JobFailedListener
{
    /**
     * @param \Matusboa\LaravelExporter\Contract\Store\QueueMetricsStoreInterface $queueMetricsStore
     */
    public function __construct(
        protected QueueMetricsStoreInterface $queueMetricsStore
    ) {
    }

    /**
     * @param \Illuminate\Queue\Events\JobFailed $event
     */
    public function handle(JobFailed $event): void
    {
        $this->queueMetricsStore->incrementJobsCount($event->job->getQueue(), JobMetricTypeEnum::FAILED);
    }
}
