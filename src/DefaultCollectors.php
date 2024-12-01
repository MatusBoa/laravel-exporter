<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter;

use Matusboa\LaravelExporter\Collector\QueueCollector;

class DefaultCollectors
{
    /**
     * @return array<array-key, class-string<\Matusboa\LaravelExporter\Contract\Collector\CollectorInterface>>
     */
    public static function queueCollectors(): array
    {
        return [
            QueueCollector::class,
        ];
    }
}