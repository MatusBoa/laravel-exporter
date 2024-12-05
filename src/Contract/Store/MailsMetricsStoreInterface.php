<?php

namespace Matusboa\LaravelExporter\Contract\Store;

interface MailsMetricsStoreInterface
{
    public function incrementSending(): void;

    /**
     * @return int
     */
    public function getSendingCount(): int;

    public function incrementSent(): void;

    /**
     * @return int
     */
    public function getSentCount(): int;

    public function clear(): void;
}
