<?php

declare(strict_types=1);

namespace Jfcherng\Diff\Factory;

use Jfcherng\Diff\Renderer\Html\LineRenderer\AbstractLineRenderer;
use Jfcherng\Diff\Renderer\RendererConstant;

final class LineRendererFactory
{
    /**
     * Instances of line renderers.
     *
     * @var AbstractLineRenderer[]
     */
    private static $singletons = [];

    /**
     * The constructor.
     */
    private function __construct()
    {
    }

    /**
     * Get the singleton of a line renderer.
     *
     * @param string $type        the type
     * @param mixed  ...$ctorArgs the constructor arguments
     *
     * @return AbstractLineRenderer
     */
    public static function getInstance(string $type, ...$ctorArgs): AbstractLineRenderer
    {
        if (!isset(self::$singletons[$type])) {
            self::$singletons[$type] = self::make($type, ...$ctorArgs);
        }

        return self::$singletons[$type];
    }

    /**
     * Make a new instance of a line renderer.
     *
     * @param string $type        the type
     * @param mixed  ...$ctorArgs the constructor arguments
     *
     * @throws \InvalidArgumentException
     *
     * @return AbstractLineRenderer
     */
    public static function make(string $type, ...$ctorArgs): AbstractLineRenderer
    {
        $className = RendererConstant::RENDERER_NAMESPACE . '\\Html\\LineRenderer\\' . \ucfirst($type);

        if (!\class_exists($className)) {
            throw new \InvalidArgumentException("LineRenderer not found: {$type}");
        }

        return new $className(...$ctorArgs);
    }
}
