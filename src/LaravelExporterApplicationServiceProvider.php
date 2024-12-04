<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter;

use Illuminate\Support\ServiceProvider;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;

class LaravelExporterApplicationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->make(CollectorRegistryInterface::class)->registerCollectorClasses(
            $this->collectors(),
        );
    }

    /**
     * @return list<class-string<\Matusboa\LaravelExporter\Contract\CollectorInterface>>
     */
    public function collectors(): array
    {
        return [];
    }
}
