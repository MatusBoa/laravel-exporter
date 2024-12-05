<?php

namespace Matusboa\LaravelExporter\Enum;

enum JobMetricTypeEnum: string
{
    case QUEUED = 'queued';
    case PROCESSING = 'processing';
    case PROCESSED = 'processed';
    case FAILED = 'failed';
}
