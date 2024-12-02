<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Contract;

interface CollectorInterface
{
    public function register(): void;
}