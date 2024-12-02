<?php

declare(strict_types = 1);

namespace Matusboa\LaravelExporter\Collector;

use Illuminate\Support\Facades\Queue;
use Matusboa\LaravelExporter\Contract\CollectorInterface;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;

class QueueCollector implements CollectorInterface
{
    /**
     * @inheritDoc
     */
    public function register(CollectorRegistryInterface $registry): void
    {
        $this->registerQueueSizeCollectors($registry);
    }

    /**
     * @param \Matusboa\LaravelExporter\Contract\CollectorRegistryInterface $registry
     */
    protected function registerQueueSizeCollectors(CollectorRegistryInterface $registry): void
    {
        $queues = \array_map(
            static fn (string|\BackedEnum $queue) => $queue instanceof \BackedEnum ? $queue->value : $queue,
            config('laravel_exporter.queues', []),
        );

        $gauge = $registry->registerGauge(
            'queue_size',
            'Number of jobs in the queue',
            ['queue'],
        );

        foreach ($queues as $queue) {
            $gauge->set(Queue::size($queue), [$queue]);
        }
    }
}