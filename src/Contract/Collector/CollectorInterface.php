<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Contract\Collector;

use Prometheus\CollectorRegistry;

interface CollectorInterface
{
    /**
     * @param \Prometheus\CollectorRegistry $registry
     */
    public function register(CollectorRegistry $registry): void;
}