<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Contract;

interface BootstrapableCollectorInterface
{
    public function bootstrap(): void;
}
