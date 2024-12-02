<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Listener\Queue;

use Illuminate\Queue\Events\JobProcessed;
use Matusboa\LaravelExporter\Collector\QueueCollector;
use Matusboa\LaravelExporter\Contract\QueueMetricsStoreInterface;
use Matusboa\LaravelExporter\Enum\JobMetricTypeEnum;

final class JobFailedListener
{
    /**
     * @param \Matusboa\LaravelExporter\Contract\QueueMetricsStoreInterface $queueMetricsStore
     */
    public function __construct(
        private readonly QueueMetricsStoreInterface $queueMetricsStore
    ) {
    }

    /**
     * @param \Illuminate\Queue\Events\JobProcessed $event
     */
    public function handle(JobProcessed $event): void
    {
        $this->queueMetricsStore->decrementJobsCount($event->job->getQueue(), JobMetricTypeEnum::PROCESSING);
        $this->queueMetricsStore->incrementJobsCount($event->job->getQueue(), JobMetricTypeEnum::FAILED);
    }
}