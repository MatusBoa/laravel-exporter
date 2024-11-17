<?php

declare(strict_types = 1);

namespace Matusboa\LaravelExporter\Registry;

use Illuminate\Support\Facades\Redis;
use Matusboa\LaravelExporter\Collector\QueueCollector;
use Matusboa\LaravelExporter\Contract\CollectorInterface;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;

class CollectorRegistry implements CollectorRegistryInterface
{
    protected \Prometheus\CollectorRegistry $registry;

    public function __construct(
        protected array $collectors,
        protected string $redisConnection,
    ) {
        dd(Redis::connection($this->redisConnection)->client());
        //$this->registry = \Prometheus\CollectorRegistry::getDefault();
    }

    public function registeredCollectors(): array
    {
        return $this->collectors;
    }

    /**
     * @return array<array-key, class-string<\Matusboa\LaravelExporter\Contract\CollectorInterface>>
     */
    public static function getDefaultCollectors(): array
    {
        return [
            QueueCollector::class,
        ];
    }
}