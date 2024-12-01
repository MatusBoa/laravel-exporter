<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Registry;

use Illuminate\Contracts\Cache\Repository;
use Matusboa\LaravelExporter\Adapter\StorageAdapter;
use Matusboa\LaravelExporter\Contract\Collector\GaugeCollectorInterface;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;

class CollectorRegistry implements CollectorRegistryInterface
{
    /**
     * @var \Prometheus\CollectorRegistry
     */
    protected \Prometheus\CollectorRegistry $registry;

    /**
     * @var array<array-key, class-string<\Matusboa\LaravelExporter\Contract\Collector\CollectorInterface>>
     */
    protected array $collectors = [];

    /**
     * @param \Illuminate\Contracts\Cache\Repository $repository
     */
    public function __construct(
        Repository $repository,
    ) {
        $this->registry = new \Prometheus\CollectorRegistry(
            new StorageAdapter($repository),
        );
    }

    /**
     * @inheritDoc
     */
    public function getCollectors(): array
    {
        return $this->collectors;
    }

    /**
     * @inheritDoc
     */
    public function registerGaugeCollector(GaugeCollectorInterface $collector): void
    {
        $collector->register($this->registry);
        $this->collectors[] = $collector;
    }

    /**
     * @inheritDoc
     */
    public function registerCollectors(array $collectors): void
    {
        foreach ($collectors as $collector) {
            $collectorInstance = app($collector);

            if ($collectorInstance instanceof GaugeCollectorInterface) {
                $this->registerGaugeCollector($collectorInstance);
            }
        }
    }

    public function getMetricFamilySamples(): array
    {
        return $this->registry->getMetricFamilySamples();
    }
}
