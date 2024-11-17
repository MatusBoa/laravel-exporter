<?php

declare(strict_types = 1);

return [
    'redis_connection' => 'default',
    'collectors' => [
        ...\Matusboa\LaravelExporter\Registry\CollectorRegistry::getDefaultCollectors(),
    ],
];