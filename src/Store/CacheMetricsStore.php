<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Store;

use Matusboa\LaravelExporter\Contract\Store\CacheMetricsStoreInterface;
use Matusboa\LaravelExporter\Contract\Store\GenericMetricsStoreInterface;

final class CacheMetricsStore implements CacheMetricsStoreInterface
{
    private const string CACHE_PREFIX = 'LARAVEL_EXPORTER';
    private const string CACHE_SUFFIX = 'QUEUE_METRICS_STORE';

    public function __construct(
        protected GenericMetricsStoreInterface $genericMetricsStore,
    ) {
    }

    public function incrementHits(): void
    {
        $this->genericMetricsStore->getRepository()->increment(
            $this->getCacheKey('hits'),
        );
    }

    public function getHitsCount(): int
    {
        return (int) $this->genericMetricsStore->getRepository()->get(
            $this->getCacheKey('hits'),
            0,
        );
    }

    public function incrementMisses(): void
    {
        $this->genericMetricsStore->getRepository()->increment(
            $this->getCacheKey('misses'),
        );
    }

    public function getMissesCount(): int
    {
        return (int) $this->genericMetricsStore->getRepository()->get(
            $this->getCacheKey('misses'),
            0,
        );
    }

    public function clear(): void
    {
        $this->genericMetricsStore->getRepository()->deleteMultiple([
            $this->getCacheKey('hits'),
            $this->getCacheKey('misses'),
        ]);
    }


    /**
     * @param string $key
     *
     * @return string
     */
    private function getCacheKey(string $key): string
    {
        return \implode('_', \array_filter([
            self::CACHE_PREFIX,
            $key,
            $type->value,
            self::CACHE_SUFFIX,
        ]));
    }
}
