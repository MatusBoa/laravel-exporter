<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Registry;

use Prometheus\Gauge;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Prometheus\RegistryInterface;
use Illuminate\Contracts\Cache\Repository;
use Matusboa\LaravelExporter\Adapter\StorageAdapter;
use Matusboa\LaravelExporter\Contract\CollectorInterface;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;
use Matusboa\LaravelExporter\Contract\CollectorWithRenderCallbackInterface;

class CollectorRegistry implements CollectorRegistryInterface
{
    /**
     * @var null|\Prometheus\CollectorRegistry
     */
    protected ?\Prometheus\CollectorRegistry $registry = null;

    /**
     * @var array<class-string<\Matusboa\LaravelExporter\Contract\CollectorInterface>, \Matusboa\LaravelExporter\Contract\CollectorInterface>
     */
    protected array $collectors = [];

    /**
     * @var array<array-key, class-string<\Matusboa\LaravelExporter\Contract\CollectorInterface>>
     */
    protected array $onRenderCollectors = [];

    /**
     * @param \Illuminate\Contracts\Cache\Repository $repository
     */
    public function __construct(
        protected Repository $repository,
    ) {
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
    public function registerCollectorClasses(array $collectors): void
    {
        foreach ($collectors as $collector) {
            $collectorInstance = \app($collector);

            if (! $collectorInstance instanceof CollectorInterface) {
                throw new \InvalidArgumentException(
                    \sprintf(
                        'Collector %s must implement %s',
                        $collector,
                        CollectorInterface::class,
                    ),
                );
            }

            $this->collectors[$collector] = $collectorInstance;

            if ($collectorInstance instanceof CollectorWithRenderCallbackInterface) {
                $this->onRenderCollectors[] = $collector;
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function registerGauge(
        string $name,
        string $helpText,
        array $labels = [],
    ): Gauge {
        return $this->getPrometheusRegistry()->getOrRegisterGauge(
            $this->getNamespace(),
            $this->getPrefixedName($name),
            $helpText,
            $labels,
        );
    }

    /**
     * @inheritDoc
     */
    public function getPrometheusRegistry(): RegistryInterface
    {
        return $this->registry ??= new \Prometheus\CollectorRegistry(
            new StorageAdapter($this->repository),
        );
    }

    /**
     * @inheritDoc
     */
    public function getCollectorsWithOnRenderCallback(): array
    {
        return Arr::only($this->collectors, $this->onRenderCollectors);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getPrefixedName(string $name): string
    {
        return 'laravel_exporter_' . $name;
    }

    /**
     * @return string
     */
    protected function getNamespace(): string
    {
        return Str::of(\config('laravel_exporter.default_namespace'))
            ->slug('_')
            ->lower()
            ->toString();
    }
}
