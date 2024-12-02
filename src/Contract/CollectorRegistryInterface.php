<?php

declare(strict_types = 1);

namespace Matusboa\LaravelExporter\Contract;

use Matusboa\LaravelExporter\Contract\Collector\GaugeCollectorInterface;
use Prometheus\Gauge;
use Prometheus\RegistryInterface;

interface CollectorRegistryInterface
{
    /**
     * @return array<array-key, class-string<\Matusboa\LaravelExporter\Contract\CollectorInterface>>
     */
    public function getCollectors(): array;

    /**
     * @return array<array-key, class-string<\Matusboa\LaravelExporter\Contract\CollectorInterface&\Matusboa\LaravelExporter\Contract\CollectorWithRenderCallbackInterface>>
     */
    public function getCollectorsWithOnRenderCallback(): array;

    /**
     * @param array<array-key, class-string<\Matusboa\LaravelExporter\Contract\CollectorInterface>> $collectors
     */
    public function registerCollectorClasses(array $collectors): void;

    /**
     * @param non-empty-string $name
     * @param non-empty-string $helpText
     * @param array<array-key, non-empty-string> $labels
     *
     * @return \Prometheus\Gauge
     */
    public function registerGauge(
        string $name,
        string $helpText,
        array $labels = [],
    ): Gauge;

    /**
     * @return \Prometheus\RegistryInterface
     */
    public function getPrometheusRegistry(): RegistryInterface;
}