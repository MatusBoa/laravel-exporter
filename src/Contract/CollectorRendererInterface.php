<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Contract;

interface CollectorRendererInterface
{
    /**
     * @return string
     */
    public function render(): string;
}