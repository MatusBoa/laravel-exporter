<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Collector;

use Matusboa\LaravelExporter\Contract\CollectorInterface;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;
use Matusboa\LaravelExporter\Contract\Store\MailsMetricsStoreInterface;
use Matusboa\LaravelExporter\Contract\CollectorWithRenderCallbackInterface;

class MailsCollector implements CollectorInterface, CollectorWithRenderCallbackInterface
{
    /**
     * @param \Matusboa\LaravelExporter\Contract\CollectorRegistryInterface $collectorRegistry
     * @param \Matusboa\LaravelExporter\Contract\Store\MailsMetricsStoreInterface $mailsMetricsStore
     */
    public function __construct(
        protected CollectorRegistryInterface $collectorRegistry,
        protected MailsMetricsStoreInterface $mailsMetricsStore,
    ) {
    }

    public function register(): void
    {
        $this->collectorRegistry->registerGauge(
            'mails_sending',
            'Mails that are being sent',
        )->set($this->mailsMetricsStore->getSendingCount());

        $this->collectorRegistry->registerGauge(
            'mails_sent',
            'Mails that were sent',
        )->set($this->mailsMetricsStore->getSentCount());
    }

    public function onRender(): void
    {
        $this->mailsMetricsStore->clear();
    }
}
