<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Listener\Queue;

use Illuminate\Queue\Events\JobFailed;
use Matusboa\LaravelExporter\Enum\JobMetricTypeEnum;
use Matusboa\LaravelExporter\Contract\QueueMetricsStoreInterface;

class JobFailedListener
{
    /**
     * @param \Matusboa\LaravelExporter\Contract\QueueMetricsStoreInterface $queueMetricsStore
     */
    public function __construct(
        protected readonly QueueMetricsStoreInterface $queueMetricsStore
    ) {
    }

    /**
     * @param \Illuminate\Queue\Events\JobFailed $event
     */
    public function handle(JobFailed $event): void
    {
        $this->queueMetricsStore->decrementJobsCount($event->job->getQueue(), JobMetricTypeEnum::PROCESSING);
        $this->queueMetricsStore->incrementJobsCount($event->job->getQueue(), JobMetricTypeEnum::FAILED);
    }
}
