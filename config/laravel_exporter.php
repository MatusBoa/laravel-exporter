<?php

return [
    /*
     * Storage driver to use for storing metrics.
     * Any cache driver supported by Laravel can be used.
     */
    'storage_driver' => \env('EXPORTER_CACHE_DRIVER', \env('CACHE_DRIVER', 'file')),

    /**
     * Default namespace to use for metrics.
     */
    'namespace' => \env('EXPORTER_NAMESPACE', \Illuminate\Support\Str::slug((string) \env('APP_NAME', 'laravel'), '_')),
];
