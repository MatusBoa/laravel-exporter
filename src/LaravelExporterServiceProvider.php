<?php

declare(strict_types = 1);

namespace Matusboa\LaravelExporter;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Matusboa\LaravelExporter\Contract\CollectorRendererInterface;
use Matusboa\LaravelExporter\Registry\CollectorRegistry;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;
use Matusboa\LaravelExporter\Renderer\CollectorRenderer;

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
                    $app['config']->get('prometheus_exporter.driver', null),
                ),
            ),
        );

        $this->app->bind(
            CollectorRendererInterface::class,
            CollectorRenderer::class,
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/prometheus_exporter.php' => $this->app->configPath('prometheus_exporter.php'),
        ], 'laravel-exporter-config');
    }
}