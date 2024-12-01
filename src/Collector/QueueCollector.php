<?php

declare(strict_types = 1);

namespace Matusboa\LaravelExporter\Collector;

use Illuminate\Support\Facades\Queue;
use Matusboa\LaravelExporter\Contract\Collector\GaugeCollectorInterface;
use Prometheus\CollectorRegistry;

class QueueCollector implements GaugeCollectorInterface
{
    /**
     * @inheritDoc
     */
    public function register(CollectorRegistry $registry): void
    {
        $registry->registerGauge(
            'test',
            'nevim',
            'cotu',
        )->set(Queue::size('index'));
    }
}