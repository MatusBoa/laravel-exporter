<?php

namespace Matusboa\LaravelExporter\Listener\Mails;

use Matusboa\LaravelExporter\Contract\Store\MailsMetricsStoreInterface;

class MailSendingListener
{
    /**
     * @param \Matusboa\LaravelExporter\Contract\Store\MailsMetricsStoreInterface $mailsMetricsStore
     */
    public function __construct(
        protected MailsMetricsStoreInterface $mailsMetricsStore,
    ) {
    }

    public function handle(): void
    {
        $this->mailsMetricsStore->incrementSending();
    }
}
