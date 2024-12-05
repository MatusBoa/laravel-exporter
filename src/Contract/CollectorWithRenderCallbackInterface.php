<?php

namespace Matusboa\LaravelExporter\Contract;

interface CollectorWithRenderCallbackInterface
{
    public function onRender(): void;
}
