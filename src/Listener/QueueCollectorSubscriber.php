<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Listener;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Matusboa\LaravelExporter\Listener\Queue\JobFailedListener;
use Matusboa\LaravelExporter\Listener\Queue\JobProcessedListener;
use Matusboa\LaravelExporter\Listener\Queue\JobProcessingListener;

final class QueueCollectorSubscriber
{
    /**
     * @param \Illuminate\Contracts\Events\Dispatcher $dispatcher
     */
    public function subscribe(Dispatcher $dispatcher): void
    {
        $dispatcher->listen(JobProcessing::class, [
            JobProcessingListener::class, 'handle',
        ]);

        $dispatcher->listen(JobProcessed::class, [
            JobProcessedListener::class, 'handle',
        ]);

        $dispatcher->listen(JobFailed::class, [
            JobFailedListener::class, 'handle',
        ]);
    }
}