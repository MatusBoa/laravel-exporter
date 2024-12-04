<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Collector;

use Illuminate\Container\Container;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Contracts\Events\Dispatcher;
use Matusboa\LaravelExporter\Contract\CollectorInterface;
use Matusboa\LaravelExporter\Listener\Mails\MailSentListener;
use Matusboa\LaravelExporter\Listener\Mails\MailSendingListener;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;
use Matusboa\LaravelExporter\Contract\BootstrapableCollectorInterface;
use Matusboa\LaravelExporter\Contract\Store\MailsMetricsStoreInterface;
use Matusboa\LaravelExporter\Contract\CollectorWithRenderCallbackInterface;

class MailsCollector implements CollectorInterface, CollectorWithRenderCallbackInterface, BootstrapableCollectorInterface
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

    public function bootstrap(): void
    {
        Container::getInstance()->afterResolving(
            Dispatcher::class,
            static function (Dispatcher $dispatcher): void {
                $dispatcher->listen(MessageSending::class, [
                    MailSendingListener::class, 'handle',
                ]);

                $dispatcher->listen(MessageSent::class, [
                    MailSentListener::class, 'handle',
                ]);
            }
        );
    }

    public function onRender(): void
    {
        $this->mailsMetricsStore->clear();
    }
}
