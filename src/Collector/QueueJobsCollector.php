<?php

namespace Matusboa\LaravelExporter\Collector;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobQueued;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Contracts\Events\Dispatcher;
use Matusboa\LaravelExporter\Enum\JobMetricTypeEnum;
use Matusboa\LaravelExporter\Contract\CollectorInterface;
use Matusboa\LaravelExporter\Listener\Queue\JobFailedListener;
use Matusboa\LaravelExporter\Listener\Queue\JobQueuedListener;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;
use Matusboa\LaravelExporter\Listener\Queue\JobProcessedListener;
use Matusboa\LaravelExporter\Listener\Queue\JobProcessingListener;
use Matusboa\LaravelExporter\Concern\ConfiguresAfterResolvingTrait;
use Matusboa\LaravelExporter\Contract\BootstrapableCollectorInterface;
use Matusboa\LaravelExporter\Contract\Store\QueueMetricsStoreInterface;
use Matusboa\LaravelExporter\Contract\CollectorWithRenderCallbackInterface;

class QueueJobsCollector implements CollectorInterface, CollectorWithRenderCallbackInterface, BootstrapableCollectorInterface
{
    use ConfiguresAfterResolvingTrait;

    /**
     * @param \Matusboa\LaravelExporter\Contract\CollectorRegistryInterface $registry
     * @param \Matusboa\LaravelExporter\Contract\Store\QueueMetricsStoreInterface $queueMetricsStore
     */
    public function __construct(
        protected CollectorRegistryInterface $registry,
        protected QueueMetricsStoreInterface $queueMetricsStore,
    ) {
    }

    public function register(): void
    {
        $this->registerJobQueuedGauges();
        $this->registerJobProcessingGauges();
        $this->registerJobProcessedGauges();
        $this->registerJobFailedGauges();
    }

    public function bootstrap(): void
    {
        $this->afterResolving(
            Dispatcher::class,
            static function (Dispatcher $dispatcher): void {
                $dispatcher->listen(JobQueued::class, [
                    JobQueuedListener::class, 'handle',
                ]);

                $dispatcher->listen(JobProcessing::class, [
                    JobProcessingListener::class, 'handle',
                ]);

                $dispatcher->listen(JobProcessed::class, [
                    JobProcessedListener::class, 'handle',
                ]);

                $dispatcher->listen(JobFailed::class, [
                    JobFailedListener::class, 'handle',
                ]);
            }
        );
    }

    public function onRender(): void
    {
        $this->queueMetricsStore->clear();
    }

    protected function registerJobQueuedGauges(): void
    {
        $gauge = $this->registry->registerGauge(
            'queue_job_queued',
            'Number of queued jobs',
            ['queue'],
        );

        foreach ($this->queueMetricsStore->getQueues(JobMetricTypeEnum::QUEUED) as $queue) {
            $gauge->set($this->queueMetricsStore->getJobsCount($queue, JobMetricTypeEnum::QUEUED), [$queue]);
        }
    }

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
}
