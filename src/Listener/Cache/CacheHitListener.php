<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Listener\Cache;

use Matusboa\LaravelExporter\Contract\Store\CacheMetricsStoreInterface;

final class CacheHitListener
{
    /**
     * @param \Matusboa\LaravelExporter\Contract\Store\CacheMetricsStoreInterface $cacheMetricsStore
     */
    public function __construct(
        protected CacheMetricsStoreInterface $cacheMetricsStore,
    ) {
    }

    public function handle(): void
    {
        $this->cacheMetricsStore->incrementHits();
    }
}
