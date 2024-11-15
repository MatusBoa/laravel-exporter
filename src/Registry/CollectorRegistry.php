<?php

declare(strict_types = 1);

namespace Matusboa\LaravelExporter\Registry;

use Matusboa\LaravelExporter\Contract\CollectorInterface;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;

class CollectorRegistry implements CollectorRegistryInterface
{
    private array $collectors = [];

    public function __construct(
        CollectorInterface ...$collectors,
    ) {
        $this->collectors = $collectors;
    }

    public function registeredCollectors(): array
    {
        return $this->collectors;
    }

}