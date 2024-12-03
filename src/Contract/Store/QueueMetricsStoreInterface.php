<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Contract\Store;

use Matusboa\LaravelExporter\Enum\JobMetricTypeEnum;

interface QueueMetricsStoreInterface
{
    /**
     * @param string $queue
     * @param \Matusboa\LaravelExporter\Enum\JobMetricTypeEnum $type
     *
     * @return int<0, max>
     */
    public function getJobsCount(
        string $queue,
        JobMetricTypeEnum $type,
    ): int;

    /**
     * @param string $queue
     * @param \Matusboa\LaravelExporter\Enum\JobMetricTypeEnum $type
     * @param int<0, max> $count
     */
    public function setJobsCount(
        string $queue,
        JobMetricTypeEnum $type,
        int $count,
    ): void;

    /**
     * @param string $queue
     * @param \Matusboa\LaravelExporter\Enum\JobMetricTypeEnum $type
     */
    public function incrementJobsCount(string $queue, JobMetricTypeEnum $type): void;

    /**
     * @param string $queue
     * @param \Matusboa\LaravelExporter\Enum\JobMetricTypeEnum $type
     */
    public function decrementJobsCount(string $queue, JobMetricTypeEnum $type): void;

    /**
     * Returns all queues that were stored in the store.
     *
     * @param \Matusboa\LaravelExporter\Enum\JobMetricTypeEnum $type
     *
     * @return array<array-key, string>
     */
    public function getQueues(JobMetricTypeEnum $type): array;

    public function clear(): void;
}
