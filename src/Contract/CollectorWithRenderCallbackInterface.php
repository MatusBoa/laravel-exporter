<?php

declare(strict_types=1);

namespace Matusboa\LaravelExporter\Contract;

interface CollectorWithRenderCallbackInterface
{
    public function onRender(): void;
}
