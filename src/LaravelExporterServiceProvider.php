<?php

declare(strict_types = 1);

namespace Matusboa\LaravelExporter;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Matusboa\LaravelExporter\Contract\CollectorRendererInterface;
use Matusboa\LaravelExporter\Contract\QueueMetricsStoreInterface;
use Matusboa\LaravelExporter\Listener\QueueCollectorSubscriber;
use Matusboa\LaravelExporter\Registry\CollectorRegistry;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;
use Matusboa\LaravelExporter\Renderer\CollectorRenderer;
use Matusboa\LaravelExporter\Store\QueueMetricsStore;

class LaravelExporterServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->app->scoped(
            CollectorRegistryInterface::class,
            static fn(Application $app): CollectorRegistryInterface => new CollectorRegistry(
                $app['cache']->store(
                    $app['config']->get('laravel_exporter.driver', null),
                ),
            ),
        );

        $this->app->bind(CollectorRendererInterface::class, CollectorRenderer::class);

        $this->app->bind(
            QueueMetricsStoreInterface::class,
            static fn (Application $app): QueueMetricsStoreInterface => new QueueMetricsStore(
                $app['cache']->store(
                    $app['config']->get('laravel_exporter.driver', null),
                ),
            ),
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/laravel_exporter.php' => $this->app->configPath('laravel_exporter.php'),
        ], 'laravel-exporter-config');

        Event::subscribe(QueueCollectorSubscriber::class);
    }
}