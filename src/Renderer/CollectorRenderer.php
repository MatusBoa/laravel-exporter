<?php

declare(strict_types=1);

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
        protected readonly CollectorRegistryInterface $collectorRegistry,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $renderer = new RenderTextFormat();

        $output = $renderer->render(
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
