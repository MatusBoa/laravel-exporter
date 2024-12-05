<?php

namespace Matusboa\LaravelExporter\Contract;

interface CollectorRendererInterface
{
    /**
     * @return string
     */
    public function render(): string;

    /**
     * @return string
     */
    public static function mimeType(): string;
}
