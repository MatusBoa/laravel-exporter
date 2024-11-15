<?php

declare(strict_types = 1);

namespace Matusboa\LaravelExporter;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Matusboa\LaravelExporter\Registry\CollectorRegistry;
use Matusboa\LaravelExporter\Collector\TestingCollector;
use Matusboa\LaravelExporter\Contract\CollectorInterface;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;

final class LaravelExporterServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->app->bind(CollectorRegistryInterface::class, CollectorRegistry::class);
    }

    public function boot()
    {
        $this->app->when(CollectorRegistryInterface::class)
            ->needs(CollectorInterface::class)
            ->give(TestingCollector::class);
    }

    /**
     * @inheritDoc
     *
     * @return array<array-key, class-string>
     */
    public function provides(): array
    {
        return [
            CollectorRegistryInterface::class,
        ];
    }
}