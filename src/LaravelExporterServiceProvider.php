<?php

declare(strict_types = 1);

namespace Matusboa\LaravelExporter;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
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
        $this->app->bind(CollectorRegistryInterface::class, static function (Application $app): CollectorRegistryInterface {
            return new CollectorRegistry(
                $app['config']->get('prometheus_exporter.collectors', []),
            );
        });
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