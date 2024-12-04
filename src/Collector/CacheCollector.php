<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Collector;

use Illuminate\Container\Container;
use Illuminate\Cache\Events\CacheHit;
use Illuminate\Cache\Events\CacheMissed;
use Illuminate\Contracts\Events\Dispatcher;
use Matusboa\LaravelExporter\Store\CacheMetricsStore;
use Matusboa\LaravelExporter\Contract\CollectorInterface;
use Matusboa\LaravelExporter\Listener\Cache\CacheHitListener;
use Matusboa\LaravelExporter\Listener\Cache\CacheMissedListener;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;
use Matusboa\LaravelExporter\Contract\BootstrapableCollectorInterface;
use Matusboa\LaravelExporter\Contract\CollectorWithRenderCallbackInterface;

final class CacheCollector implements CollectorInterface, CollectorWithRenderCallbackInterface, BootstrapableCollectorInterface
{
    /**
     * @param \Matusboa\LaravelExporter\Contract\CollectorRegistryInterface $collectorRegistry
     * @param \Matusboa\LaravelExporter\Store\CacheMetricsStore $cacheMetricsStore
     */
    public function __construct(
        protected CollectorRegistryInterface $collectorRegistry,
        protected CacheMetricsStore $cacheMetricsStore,
    ) {
    }

    public function bootstrap(): void
    {
        Container::getInstance()->afterResolving(
            Dispatcher::class,
            static function (Dispatcher $dispatcher): void {
                $dispatcher->listen(CacheHit::class, [
                    CacheHitListener::class, 'handle',
                ]);

                $dispatcher->listen(CacheMissed::class, [
                    CacheMissedListener::class, 'handle',
                ]);
            },
        );
    }

    public function register(): void
    {
        $this->collectorRegistry->registerGauge(
            'cache_hits',
            'Cache hits',
        )->set($this->cacheMetricsStore->getHitsCount());

        $this->collectorRegistry->registerGauge(
            'cache_misses',
            'Cache misses',
        )->set($this->cacheMetricsStore->getMissesCount());
    }

    public function onRender(): void
    {
        $this->cacheMetricsStore->clear();
    }
}
