<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Adapter;

use Illuminate\Contracts\Cache\Repository;
use Prometheus\Storage\InMemory;

class StorageAdapter extends InMemory
{
    /**
     * @param \Illuminate\Contracts\Cache\Repository $repository
     */
    public function __construct(
        private readonly Repository $repository,
    ) {
    }
}