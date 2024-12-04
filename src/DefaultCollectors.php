<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter;

use Matusboa\LaravelExporter\Collector\InfoCollector;
use Matusboa\LaravelExporter\Collector\CacheCollector;
use Matusboa\LaravelExporter\Collector\MailsCollector;
use Matusboa\LaravelExporter\Collector\QueueJobsCollector;

class DefaultCollectors
{
    /**
     * @return list<class-string<\Matusboa\LaravelExporter\Contract\CollectorInterface>>
     */
    public static function all(): array
    {
        return [
            InfoCollector::class,
            QueueJobsCollector::class,
            MailsCollector::class,
            CacheCollector::class,
        ];
    }
}
