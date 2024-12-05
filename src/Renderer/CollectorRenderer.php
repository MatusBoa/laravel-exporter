<?php

namespace Matusboa\LaravelExporter\Renderer;

use Prometheus\RenderTextFormat;
use Matusboa\LaravelExporter\Contract\CollectorRegistryInterface;
use Matusboa\LaravelExporter\Contract\CollectorRendererInterface;

class CollectorRenderer implements CollectorRendererInterface
{
    /**
     * @param \Matusboa\LaravelExporter\Contract\CollectorRegistryInterface $collectorRegistry
     */
    public function __construct(
        protected CollectorRegistryInterface $collectorRegistry,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        foreach ($this->collectorRegistry->getCollectors() as $collector) {
            $collector->register();
        }

        $output = (new RenderTextFormat())->render(
            $this->collectorRegistry->getPrometheusRegistry()->getMetricFamilySamples(),
        );

        foreach ($this->collectorRegistry->getCollectorsWithOnRenderCallback() as $collector) {
            $collector->onRender();
        }

        return $output;
    }

    /**
     * @inheritDoc
     */
    public static function mimeType(): string
    {
        return RenderTextFormat::MIME_TYPE;
    }
}
