<?php

declare(strict_types = 1);

namespace Matusboa\LaravelExporter;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Matusboa\LaravelExporter\Registry\CollectorRegistry;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;

final class LaravelExporterServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->app->bind(
            CollectorRegistryInterface::class,
            static fn(Application $app): CollectorRegistryInterface => new CollectorRegistry(
                $app['config']->get('prometheus_exporter.collectors', []),
                $app['config']->get('database.redis', []),
                $app['config']->get('prometheus_exporter.redis_connection', 'default'),
            ));
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/prometheus_exporter.php' => $this->app->configPath('prometheus_exporter.php'),
        ]);
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