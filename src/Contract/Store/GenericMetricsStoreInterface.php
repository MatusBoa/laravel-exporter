<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Contract\Store;

use Illuminate\Contracts\Cache\Repository;

interface GenericMetricsStoreInterface
{
    /**
     * @return \Illuminate\Contracts\Cache\Repository
     */
    public function getRepository(): Repository;
}
