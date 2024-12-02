<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Registry;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Str;
use Matusboa\LaravelExporter\Adapter\StorageAdapter;
use Matusboa\LaravelExporter\Contract\CollectorInterface;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;
use Matusboa\LaravelExporter\Contract\CollectorWithRenderCallbackInterface;
use Prometheus\Gauge;
use Prometheus\RegistryInterface;

class CollectorRegistry implements CollectorRegistryInterface
{
    /**
     * @var \Prometheus\CollectorRegistry
     */
    protected \Prometheus\CollectorRegistry $registry;

    /**
     * @var array<class-string<\Matusboa\LaravelExporter\Contract\CollectorInterface>, \Matusboa\LaravelExporter\Contract\CollectorInterface>
     */
    protected array $collectors = [];

    /**
     * @var array<class-string<\Matusboa\LaravelExporter\Contract\CollectorInterface>, \Matusboa\LaravelExporter\Contract\CollectorInterface>
     */
    protected array $onRenderCollectors = [];

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
    public function registerCollectorClasses(array $collectors): void
    {
        foreach ($collectors as $collector) {
            $collectorInstance = app($collector);

            if (! $collectorInstance instanceof CollectorInterface) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Collector %s must implement %s',
                        $collector,
                        CollectorInterface::class,
                    ),
                );
            }

            $collectorInstance->register();

            $this->collectors[$collector] = $collectorInstance;

            if ($collectorInstance instanceof CollectorWithRenderCallbackInterface) {
                $this->onRenderCollectors[$collector] = $collectorInstance;
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
        $gauge = $this->registry->getOrRegisterGauge(
            $this->getNamespace(),
            $this->getPrefixedName($name),
            $helpText,
            $labels,
        );

        return $gauge;
    }

    /**
     * @inheritDoc
     */
    public function getPrometheusRegistry(): RegistryInterface
    {
        return $this->registry;
    }

    /**
     * @inheritDoc
     */
    public function getCollectorsWithOnRenderCallback(): array
    {
        return $this->onRenderCollectors;
    }

    /**
     * @param string $name
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
        return Str::of(config('laravel_exporter.default_namespace'))
            ->slug('_')
            ->lower()
            ->toString();
    }
}
