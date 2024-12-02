<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Contract;

interface CollectorInterface
{
    /**
     * @param \Matusboa\LaravelExporter\Contract\CollectorRegistryInterface $registry
     */
    public function register(CollectorRegistryInterface $registry): void;
}