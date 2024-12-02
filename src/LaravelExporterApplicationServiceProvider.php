<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter;

use Illuminate\Support\ServiceProvider;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;

class LaravelExporterApplicationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        app(CollectorRegistryInterface::class)->registerCollectorClasses(
            $this->collectors(),
        );
    }

    /**
     * @return array<array-key, class-string<\Matusboa\LaravelExporter\Contract\Collector\CollectorInterface>>
     */
    public function collectors(): array
    {
        return [];
    }
}