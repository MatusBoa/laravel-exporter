<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Listener\Cache;

use Illuminate\Cache\Events\CacheMissed;

final class CacheMissedListener
{
    public function handle(CacheMissed $event): void
    {

    }
}
