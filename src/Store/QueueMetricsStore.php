<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Store;

use Matusboa\LaravelExporter\Enum\JobMetricTypeEnum;
use Matusboa\LaravelExporter\Contract\Store\QueueMetricsStoreInterface;
use Matusboa\LaravelExporter\Contract\Store\GenericMetricsStoreInterface;

class QueueMetricsStore implements QueueMetricsStoreInterface
{
    protected const string CACHE_PREFIX = 'LARAVEL_EXPORTER';
    protected const string CACHE_SUFFIX = 'QUEUE_METRICS_STORE';

    protected const string CACHE_QUEUES = 'queues';

    /**
     * @param \Matusboa\LaravelExporter\Contract\Store\GenericMetricsStoreInterface $genericMetricsStore
     */
    public function __construct(
        protected GenericMetricsStoreInterface $genericMetricsStore,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getJobsCount(string $queue, JobMetricTypeEnum $type): int
    {
        return (int) $this->genericMetricsStore->getRepository()->get(
            $this->getCacheKey($queue, $type),
            0,
        );
    }

    /**
     * @inheritDoc
     */
    public function setJobsCount(string $queue, JobMetricTypeEnum $type, int $count): void
    {
        $this->genericMetricsStore->getRepository()->put(
            $this->getCacheKey($queue, $type),
            \max(0, $count),
        );

        $this->storeQueue($queue, $type);
    }

    /**
     * @inheritDoc
     */
    public function incrementJobsCount(string $queue, JobMetricTypeEnum $type): void
    {
        $this->setJobsCount(
            $queue,
            $type,
            $this->getJobsCount($queue, $type) + 1,
        );

        $this->storeQueue($queue, $type);
    }

    /**
     * @inheritDoc
     */
    public function decrementJobsCount(string $queue, JobMetricTypeEnum $type): void
    {
        $this->setJobsCount(
            $queue,
            $type,
            $this->getJobsCount($queue, $type) - 1,
        );

        $this->storeQueue($queue, $type);
    }

    /**
     * @inheritDoc
     */
    public function getQueues(JobMetricTypeEnum $type): array
    {
        return $this->genericMetricsStore->getRepository()->get(
            $this->getCacheKey(self::CACHE_QUEUES, $type),
            [],
        );
    }

    public function clear(): void
    {
        foreach (JobMetricTypeEnum::cases() as $type) {
            foreach ($this->getQueues($type) as $queue) {
                $this->setJobsCount($queue, $type, 0);
            }
        }
    }

    /**
     * @param string $queue
     * @param \Matusboa\LaravelExporter\Enum\JobMetricTypeEnum $type
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function storeQueue(string $queue, JobMetricTypeEnum $type): void
    {
        $this->genericMetricsStore->getRepository()->put(
            $this->getCacheKey(self::CACHE_QUEUES, $type),
            \array_unique([
                ...$this->genericMetricsStore->getRepository()->get(
                    $this->getCacheKey(self::CACHE_QUEUES, $type),
                    [],
                ),
                $queue,
            ]),
        );
    }

    /**
     * @param string $key
     * @param \Matusboa\LaravelExporter\Enum\JobMetricTypeEnum $type
     *
     * @return string
     */
    protected function getCacheKey(string $key, JobMetricTypeEnum $type): string
    {
        return \implode('_', \array_filter([
            self::CACHE_PREFIX,
            $key,
            $type->value,
            self::CACHE_SUFFIX,
        ]));
    }
}
