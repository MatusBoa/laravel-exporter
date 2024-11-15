<?php

declare(strict_types = 1);

return [
    'collectors' => [
        ...\Matusboa\LaravelExporter\Registry\CollectorRegistry::getDefaultCollectors(),
    ],
];