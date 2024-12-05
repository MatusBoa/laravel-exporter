# Prometheus exporter for Laravel

## Installation

Install package using composer.

```bash
composer require matusboa/laravel-exporter
```

Next, you should publish config and service provider.

```bash
php artisan vendor:publish --provider="Matusboa\\LaravelExporter\\LaravelExporterServiceProvider"
```

This command will publish `laravel_exporter.php` config and service provider located in `app/Providers/LaravelExporterServiceProvider`.

## Configuration

### Storage driver

Behind the scenes, Laravel Exporter uses Laravel's cache drivers to store metrics. Any cache driver, supported by Laravel, may be used.

### Collectors

Collectors may be turned on/off in `LaravelExporterServiceProvider`, specifically in `collectors` method.

## Rendering metrics

Metrics can be rendered using `Matusboa\LaravelExporter\Contract\CollectorRendererInterface`.

For example:

```php
Route::get(
    '_metrics',
    static fn (\Matusboa\LaravelExporter\Contract\CollectorRendererInterface $collectorRenderer): Response => new Response(
        content: $collectorRenderer->render(),
        headers: [
            'Content-Type' => $collectorRenderer::mimeType(),
        ],
    ),
);
```

## Custom collectors

You can create your own collectors by implementing `Matusboa\LaravelExporter\Contract\CollectorInterface` and registering it in `LaravelExporterServiceProvider`.

Collectors may need to bootstrap logic (to handle events for example), this may be accomplished by implementing `Matusboa\LaravelExporter\Contract\BootstrapableCollectorInterface` interface.

Collectors may also need to do something after rendering their metrics, this can be accomplished by implementing `Matusboa\LaravelExporter\Contract\CollectorWithRenderCallbackInterface` interface.