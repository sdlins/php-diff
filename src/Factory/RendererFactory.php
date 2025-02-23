<?php

declare(strict_types=1);

namespace Jfcherng\Diff\Factory;

use Jfcherng\Diff\Renderer\AbstractRenderer;
use Jfcherng\Diff\Renderer\RendererConstant;

final class RendererFactory
{
    /**
     * Instances of renderers.
     *
     * @var AbstractRenderer[]
     */
    private static $singletons = [];

    /**
     * The constructor.
     */
    private function __construct()
    {
    }

    /**
     * Get the singleton of a renderer.
     *
     * @param string $renderer    the renderer
     * @param mixed  ...$ctorArgs the constructor arguments
     *
     * @return AbstractRenderer
     */
    public static function getInstance(string $renderer, ...$ctorArgs): AbstractRenderer
    {
        if (!isset(self::$singletons[$renderer])) {
            self::$singletons[$renderer] = self::make($renderer, ...$ctorArgs);
        }

        return self::$singletons[$renderer];
    }

    /**
     * Make a new instance of a renderer.
     *
     * @param string $renderer    the renderer
     * @param mixed  ...$ctorArgs the constructor arguments
     *
     * @throws \InvalidArgumentException
     *
     * @return AbstractRenderer
     */
    public static function make(string $renderer, ...$ctorArgs): AbstractRenderer
    {
        $className = self::resolveRenderer($renderer);

        if (!isset($className)) {
            throw new \InvalidArgumentException("Renderer not found: {$renderer}");
        }

        return new $className(...$ctorArgs);
    }

    /**
     * Resolve the renderer name into a FQCN.
     *
     * @param string $renderer the renderer
     *
     * @return null|string
     */
    public static function resolveRenderer(string $renderer): ?string
    {
        static $cache = [];

        if (isset($cache[$renderer])) {
            return $cache[$renderer];
        }

        foreach (RendererConstant::RENDERER_TYPES as $type) {
            $className = RendererConstant::RENDERER_NAMESPACE . "\\{$type}\\{$renderer}";

            if (\class_exists($className)) {
                $result = $className;

                break;
            }
        }

        return isset($result) ? ($cache[$renderer] = $result) : null;
    }
}
