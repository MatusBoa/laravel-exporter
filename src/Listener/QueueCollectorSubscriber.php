<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Listener;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobQueued;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Contracts\Events\Dispatcher;
use Matusboa\LaravelExporter\Listener\Queue\JobFailedListener;
use Matusboa\LaravelExporter\Listener\Queue\JobQueuedListener;
use Matusboa\LaravelExporter\Listener\Queue\JobProcessedListener;
use Matusboa\LaravelExporter\Listener\Queue\JobProcessingListener;

class QueueCollectorSubscriber
{
    /**
     * @param \Illuminate\Contracts\Events\Dispatcher $dispatcher
     */
    public function subscribe(Dispatcher $dispatcher): void
    {
        $dispatcher->listen(JobQueued::class, [
            JobQueuedListener::class, 'handle',
        ]);

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
