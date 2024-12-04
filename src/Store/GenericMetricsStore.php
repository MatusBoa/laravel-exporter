<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Store;

use Illuminate\Contracts\Cache\Repository;
use Matusboa\LaravelExporter\Contract\Store\GenericMetricsStoreInterface;

final class GenericMetricsStore implements GenericMetricsStoreInterface
{
    /**
     * @param \Illuminate\Contracts\Cache\Repository $repository
     */
    public function __construct(
        protected Repository $repository,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getRepository(): Repository
    {
        return $this->repository;
    }
}
