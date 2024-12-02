<?php

use Matusboa\LaravelExporter\DefaultCollectors;

return [
    /*
     * Cache driver to use for storing metrics.
     * All drivers supported by Laravel can be used.
     */
    'driver' => \env('EXPORTER_CACHE_DRIVER', \env('CACHE_DRIVER', 'file')),

    /**
     * Default namespace to use for metrics.
     */
    'namespace' => \env('EXPORTER_NAMESPACE', \Illuminate\Support\Str::slug((string) \env('APP_NAME', 'laravel'), '_')),

    /**
     * List of queues to monitor.
     */
    'queues' => [
        'default',
    ],
];