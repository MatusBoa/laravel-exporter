<?php

declare(strict_types = 1);

namespace Matusboa\LaravelExporter\Registry;

use Matusboa\LaravelExporter\Collector\TestingCollector;
use Matusboa\LaravelExporter\Contract\CollectorInterface;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;

class CollectorRegistry implements CollectorRegistryInterface
{
    public function __construct(
        protected array $collectors = [],
    ) {}

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
            TestingCollector::class,
        ];
    }
}