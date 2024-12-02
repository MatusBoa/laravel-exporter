<?php

declare(strict_types = 1);

namespace Matusboa\LaravelExporter\Collector;

use Illuminate\Support\Facades\Queue;
use Matusboa\LaravelExporter\Contract\CollectorInterface;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;
use Matusboa\LaravelExporter\Contract\CollectorWithRenderCallbackInterface;
use Matusboa\LaravelExporter\Contract\QueueMetricsStoreInterface;
use Matusboa\LaravelExporter\Enum\JobMetricTypeEnum;
use Prometheus\Gauge;

class QueueCollector implements CollectorInterface, CollectorWithRenderCallbackInterface
{

    /**
     * @param \Matusboa\LaravelExporter\Contract\CollectorRegistryInterface $registry
     * @param \Matusboa\LaravelExporter\Contract\QueueMetricsStoreInterface $queueMetricsStore
     */
    public function __construct(
        private readonly CollectorRegistryInterface $registry,
        private readonly QueueMetricsStoreInterface $queueMetricsStore,
    ) {
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $this->registerQueueSizeGauges();

        $this->registerJobProcessingGauges();
        $this->registerJobProcessedGauges();
        $this->registerJobFailedGauges();
    }

    /**
     * @return void
     */
    protected function registerJobProcessingGauges(): void
    {
        $gauge = $this->registry->registerGauge(
            'queue_job_processing',
            'Number of jobs being processed',
            ['queue'],
        );

        foreach ($this->queueMetricsStore->getQueues(JobMetricTypeEnum::PROCESSING) as $queue) {
            $gauge->set($this->queueMetricsStore->getJobsCount($queue, JobMetricTypeEnum::PROCESSING), [$queue]);
        }
    }

    /**
     * @return void
     */
    protected function registerJobProcessedGauges(): void
    {
        $gauge = $this->registry->registerGauge(
            'queue_job_processed',
            'Number of jobs that were processed',
            ['queue'],
        );

        foreach ($this->queueMetricsStore->getQueues(JobMetricTypeEnum::PROCESSED) as $queue) {
            $gauge->set($this->queueMetricsStore->getJobsCount($queue, JobMetricTypeEnum::PROCESSED), [$queue]);
        }
    }

    /**
     * @return void
     */
    protected function registerJobFailedGauges(): void
    {
        $gauge = $this->registry->registerGauge(
            'queue_job_failed',
            'Number of jobs that failed',
            ['queue'],
        );

        foreach ($this->queueMetricsStore->getQueues(JobMetricTypeEnum::FAILED) as $queue) {
            $gauge->set($this->queueMetricsStore->getJobsCount($queue, JobMetricTypeEnum::FAILED), [$queue]);
        }
    }

    /**
     * @return void
     */
    protected function registerQueueSizeGauges(): void
    {
        $queues = \array_map(
            static fn (string|\BackedEnum $queue) => $queue instanceof \BackedEnum ? $queue->value : $queue,
            config('laravel_exporter.queues', []),
        );

        $gauge = $this->registry->registerGauge(
            'queue_size',
            'Number of jobs in the queue',
            ['queue'],
        );

        foreach ($queues as $queue) {
            $gauge->set(Queue::size($queue), [$queue]);
        }
    }

    /**
     * @return void
     */
    public function onRender(): void
    {
        $this->queueMetricsStore->clear();
    }
}