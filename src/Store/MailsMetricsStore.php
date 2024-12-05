<?php

namespace Matusboa\LaravelExporter\Store;

use Matusboa\LaravelExporter\Contract\Store\MailsMetricsStoreInterface;
use Matusboa\LaravelExporter\Contract\Store\GenericMetricsStoreInterface;

class MailsMetricsStore implements MailsMetricsStoreInterface
{
    protected const string CACHE_PREFIX = 'LARAVEL_EXPORTER';
    protected const string CACHE_SUFFIX = 'MAILS_METRICS_STORE';

    /**
     * @param \Matusboa\LaravelExporter\Contract\Store\GenericMetricsStoreInterface $genericMetricsStore
     */
    public function __construct(
        protected GenericMetricsStoreInterface $genericMetricsStore,
    ) {
    }

    public function incrementSending(): void
    {
        $this->genericMetricsStore->getRepository()->increment(
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
        return (int) $this->genericMetricsStore->getRepository()->get(
            $this->getCacheKey('sending'),
            0
        );
    }

    public function incrementSent(): void
    {
        $this->genericMetricsStore->getRepository()->increment(
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
        return (int) $this->genericMetricsStore->getRepository()->get(
            $this->getCacheKey('sent'),
            0
        );
    }

    public function clear(): void
    {
        $this->genericMetricsStore->getRepository()->deleteMultiple([
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
