<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Listener;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Contracts\Events\Dispatcher;
use Matusboa\LaravelExporter\Listener\Mails\MailSentListener;
use Matusboa\LaravelExporter\Listener\Mails\MailSendingListener;

class MailsCollectorSubscriber
{
    /**
     * @param \Illuminate\Contracts\Events\Dispatcher $dispatcher
     */
    public function subscribe(Dispatcher $dispatcher): void
    {
        $dispatcher->listen(MessageSending::class, [
            MailSendingListener::class, 'handle',
        ]);

        $dispatcher->listen(MessageSent::class, [
            MailSentListener::class, 'handle',
        ]);
    }
}
