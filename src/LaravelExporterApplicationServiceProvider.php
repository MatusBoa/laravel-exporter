<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;

class LaravelExporterApplicationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->booting(function (Application $app): void {
            $app->make(CollectorRegistryInterface::class)->registerCollectorClasses(
                $this->collectors(),
            );
        });
    }

    /**
     * @return list<class-string<\Matusboa\LaravelExporter\Contract\CollectorInterface>>
     */
    public function collectors(): array
    {
        return [];
    }
}
