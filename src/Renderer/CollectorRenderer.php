<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Renderer;

use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;
use Matusboa\LaravelExporter\Contract\CollectorRendererInterface;
use Prometheus\RenderTextFormat;

final class CollectorRenderer implements CollectorRendererInterface
{
    /**
     * @param \Matusboa\LaravelExporter\Contract\CollectorRegistryInterface $collectorRegistry
     */
    public function __construct(
        private readonly CollectorRegistryInterface $collectorRegistry,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $renderer = new RenderTextFormat();

        return $renderer->render(
            $this->collectorRegistry->getPrometheusRegistry()->getMetricFamilySamples(),
        );

    }
}