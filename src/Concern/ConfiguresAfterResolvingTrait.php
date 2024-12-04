<?php

namespace Matusboa\LaravelExporter\Concern;

use Illuminate\Container\Container;

trait ConfiguresAfterResolvingTrait
{
    /**
     * @param class-string|string $class
     * @param \Closure $callback
     */
    protected function afterResolving(string $class, \Closure $callback): void
    {
        $app = Container::getInstance();

        $app->afterResolving($class, $callback);

        if ($app->resolved($class)) {
            $callback($app->make($class));
        }
    }
}
