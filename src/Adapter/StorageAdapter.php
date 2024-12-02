<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Adapter;

use Prometheus\Gauge;
use Prometheus\Counter;
use Prometheus\Summary;
use Prometheus\Histogram;
use Prometheus\Storage\InMemory;
use Illuminate\Contracts\Cache\Repository;

class StorageAdapter extends InMemory
{
    protected const string CACHE_PREFIX = 'LARAVEL_EXPORTER';
    protected const string CACHE_SUFFIX = 'METRICS';

    /**
     * @var array<array-key, string>
     */
    protected array $types = [
        Gauge::TYPE,
        Counter::TYPE,
        Histogram::TYPE,
        Summary::TYPE,
    ];

    /**
     * @param \Illuminate\Contracts\Cache\Repository $repository
     */
    public function __construct(
        private readonly Repository $repository,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function updateGauge(array $data): void
    {
        $this->gauges = $this->repository->get(
            $this->getCacheKey(Gauge::TYPE),
            [],
        );

        parent::updateGauge($data);

        $this->repository->put(
            $this->getCacheKey(Gauge::TYPE),
            $this->gauges,
        );
    }

    /**
     * @inheritDoc
     */
    public function updateCounter(array $data): void
    {
        $this->counters = $this->repository->get(
            $this->getCacheKey(Counter::TYPE),
            [],
        );

        parent::updateCounter($data);

        $this->repository->put(
            $this->getCacheKey(Counter::TYPE),
            $this->counters,
        );
    }

    /**
     * @inheritDoc
     */
    public function updateHistogram(array $data): void
    {
        $this->histograms = $this->repository->get(
            $this->getCacheKey(Histogram::TYPE),
            [],
        );

        parent::updateHistogram($data);

        $this->repository->put(
            $this->getCacheKey(Histogram::TYPE),
            $this->histograms,
        );
    }

    /**
     * @inheritDoc
     */
    public function updateSummary(array $data): void
    {
        $this->summaries = $this->repository->get(
            $this->getCacheKey(Summary::TYPE),
            [],
        );

        parent::updateSummary($data);

        $this->repository->put(
            $this->getCacheKey(Summary::TYPE),
            $this->summaries,
        );
    }

    /**
     * @inheritDoc
     */
    protected function collectSummaries(): array
    {
        $this->summaries = $this->repository->get(
            $this->getCacheKey(Summary::TYPE),
            [],
        );

        return parent::collectSummaries();
    }

    /**
     * @inheritDoc
     */
    protected function collectHistograms(): array
    {
        $this->histograms = $this->repository->get(
            $this->getCacheKey(Histogram::TYPE),
            [],
        );

        return parent::collectHistograms();
    }

    /**
     * @inheritDoc
     */
    public function wipeStorage(): void
    {
        $this->repository->deleteMultiple(\array_map(
            $this->getCacheKey(...),
            $this->types,
        ));

        parent::wipeStorage();
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getCacheKey(string $key): string
    {
        return \implode('_', [
            self::CACHE_PREFIX,
            $key,
            self::CACHE_SUFFIX,
        ]);
    }
}
