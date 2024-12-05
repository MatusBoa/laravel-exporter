<?php

namespace Matusboa\LaravelExporter\Listener\Cache;

use Matusboa\LaravelExporter\Contract\Store\CacheMetricsStoreInterface;

final class CacheMissedListener
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
        $this->cacheMetricsStore->incrementMissed();
    }
}
