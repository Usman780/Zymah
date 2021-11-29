<?php

namespace GoDaddy\WordPress\MWC\Common\Traits;

use GoDaddy\WordPress\MWC\Common\Extensions\AbstractExtension;

/**
 * A trait for singletons.
 *
 * @since x.y.z
 */
trait IsSingletonTrait
{
    /** @var AbstractExtension */
    private static $instance;

    /**
     * Determines if the current instance is loaded.
     *
     * @since x.y.z
     *
     * @return bool
     */
    public static function isLoaded() : bool
    {
        return (bool) self::$instance;
    }

    /**
     * Gets the singleton instance.
     *
     * @since x.y.z
     *
     * @return AbstractExtension
     */
    public static function getInstance()
    {
        if (! self::isLoaded()) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Resets the singleton instance.
     *
     * @since x.y.z
     */
    public static function reset()
    {
        self::$instance = null;
    }
}
