<?php
namespace Matusboa\LaravelExporter\Enum;

enum JobMetricTypeEnum: string
{
    case PROCESSING = 'processing';
    case PROCESSED = 'processed';
    case FAILED = 'failed';
}
