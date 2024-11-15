<?php

declare(strict_types = 1);

namespace Matusboa\LaravelExporter\Contract;

interface CollectorRegistryInterface
{
    /**
     * @return array
     */
    public function registeredCollectors(): array;
}