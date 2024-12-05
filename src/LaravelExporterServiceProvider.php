<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Matusboa\LaravelExporter\Store\CacheMetricsStore;
use Matusboa\LaravelExporter\Store\MailsMetricsStore;
use Matusboa\LaravelExporter\Store\QueueMetricsStore;
use Matusboa\LaravelExporter\Store\GenericMetricsStore;
use Matusboa\LaravelExporter\Registry\CollectorRegistry;
use Matusboa\LaravelExporter\Renderer\CollectorRenderer;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;
use Matusboa\LaravelExporter\Contract\CollectorRendererInterface;
use Matusboa\LaravelExporter\Contract\Store\CacheMetricsStoreInterface;
use Matusboa\LaravelExporter\Contract\Store\MailsMetricsStoreInterface;
use Matusboa\LaravelExporter\Contract\Store\QueueMetricsStoreInterface;
use Matusboa\LaravelExporter\Contract\Store\GenericMetricsStoreInterface;

class LaravelExporterServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->app->bind(
            GenericMetricsStoreInterface::class,
            static fn (Application $app): GenericMetricsStoreInterface => new GenericMetricsStore(
                $app['cache']->store(
                    $app['config']->get('laravel_exporter.driver', null),
                ),
            )
        );

        $this->app->scoped(CollectorRegistryInterface::class, CollectorRegistry::class);
        $this->app->bind(CollectorRendererInterface::class, CollectorRenderer::class);

        $this->app->bind(QueueMetricsStoreInterface::class, QueueMetricsStore::class);
        $this->app->bind(MailsMetricsStoreInterface::class, MailsMetricsStore::class);
        $this->app->bind(CacheMetricsStoreInterface::class, CacheMetricsStore::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel_exporter.php' => $this->app->configPath('laravel_exporter.php'),
            ], 'laravel-exporter-config');

            $this->publishes([
                __DIR__ . '/../stubs/LaravelExportServiceProvider.stub' => $this->app->basePath('Providers/LaravelExporterServiceProvider.php'),
            ], 'laravel-exporter-provider');
        }
    }
}
