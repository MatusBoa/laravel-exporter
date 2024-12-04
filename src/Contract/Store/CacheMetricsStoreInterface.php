<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Contract\Store;

interface CacheMetricsStoreInterface
{
    public function incrementHits(): void;

    public function getHitsCount(): int;

    public function incrementMissed(): void;

    public function getMissesCount(): int;

    public function clear(): void;
}
