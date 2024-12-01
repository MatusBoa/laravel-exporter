<?php

declare(strict_types = 1);

namespace Matusboa\LaravelExporter\Contract;

use Matusboa\LaravelExporter\Contract\Collector\GaugeCollectorInterface;

interface CollectorRegistryInterface
{
    /**
     * @return array<array-key, class-string<\Matusboa\LaravelExporter\Contract\Collector\CollectorInterface>>
     */
    public function getCollectors(): array;

    /**
     * @param \Matusboa\LaravelExporter\Contract\Collector\GaugeCollectorInterface $collector
     */
    public function registerGaugeCollector(GaugeCollectorInterface $collector): void;

    /**
     * @param array<array-key, class-string<\Matusboa\LaravelExporter\Contract\Collector\CollectorInterface>> $collectors
     */
    public function registerCollectors(array $collectors): void;
}