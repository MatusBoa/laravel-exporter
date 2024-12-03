<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter;

use Matusboa\LaravelExporter\Collector\MailsCollector;
use Matusboa\LaravelExporter\Collector\QueueJobsCollector;

class DefaultCollectors
{
    /**
     * @return array<array-key, class-string<\Matusboa\LaravelExporter\Contract\CollectorInterface>>
     */
    public static function all(): array
    {
        return [
            ...self::queueCollectors(),
            ...self::mailCollectors(),
        ];
    }

    /**
     * @return array<array-key, class-string<\Matusboa\LaravelExporter\Contract\CollectorInterface>>
     */
    public static function queueCollectors(): array
    {
        return [
            QueueJobsCollector::class,
        ];
    }

    /**
     * @return array<array-key, class-string<\Matusboa\LaravelExporter\Contract\CollectorInterface>>
     */
    public static function mailCollectors(): array
    {
        return [
            MailsCollector::class,
        ];
    }
}
