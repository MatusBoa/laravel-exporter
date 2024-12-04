<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Collector;

use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Matusboa\LaravelExporter\Contract\CollectorInterface;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;

final class InfoCollector implements CollectorInterface
{
    /**
     * @param \Matusboa\LaravelExporter\Contract\CollectorRegistryInterface $collectorRegistry
     */
    public function __construct(
        protected CollectorRegistryInterface $collectorRegistry,
    ) {
    }

    public function register(): void
    {
        $this->collectorRegistry->registerGauge(
            'laravel_info',
            'Information about Laravel environment',
            ['version'],
        )->set(
            Container::getInstance()->make(Application::class)->version(),
            ['version'],
        );
    }
}
