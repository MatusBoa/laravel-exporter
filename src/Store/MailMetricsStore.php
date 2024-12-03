<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Store;

use Illuminate\Contracts\Cache\Repository;
use Matusboa\LaravelExporter\Contract\Store\MailsMetricsStoreInterface;

class MailMetricsStore implements MailsMetricsStoreInterface
{
    protected const string CACHE_PREFIX = 'LARAVEL_EXPORTER';
    protected const string CACHE_SUFFIX = 'MAILS_METRICS_STORE';

    /**
     * @param \Illuminate\Contracts\Cache\Repository $repository
     */
    public function __construct(
        protected Repository $repository,
    ) {
    }

    public function incrementSending(): void
    {
        $this->repository->increment(
            $this->getCacheKey('sending'),
        );
    }

    /**
     * @inheritDoc
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getSendingCount(): int
    {
        return (int) $this->repository->get(
            $this->getCacheKey('sending'),
            0
        );
    }

    public function incrementSent(): void
    {
        $this->repository->increment(
            $this->getCacheKey('sent'),
        );
    }

    /**
     * @inheritDoc
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getSentCount(): int
    {
        return (int) $this->repository->get(
            $this->getCacheKey('sent'),
            0
        );
    }

    public function clear(): void
    {
        $this->repository->deleteMultiple([
            $this->getCacheKey('sending'),
            $this->getCacheKey('sent'),
        ]);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getCacheKey(string $key): string
    {
        return \implode('_', \array_filter([
            self::CACHE_PREFIX,
            $key,
            self::CACHE_SUFFIX,
        ]));
    }
}
