<?php

use Matusboa\LaravelExporter\DefaultCollectors;

return [
    /*
     * Cache driver to use for storing metrics.
     * All drivers supported by Laravel can be used.
     */
    'driver' => \env('EXPORTER_CACHE_DRIVER', \env('CACHE_DRIVER', 'file')),

    /**
     * List of queues to monitor.
     */
    'queues_to_monitor' => [
        'default',
    ],
];