<?php

namespace GoDaddy\WordPress\MWC\Common\Helpers;

use ReflectionClass;
use stdClass;

class ObjectHelper
{
    /**
     * Casts item as array if it is a valid object.
     *
     * @since x.y.z
     *
     * @param mixed $item
     *
     * @return array
     */
    public static function toArray($item) : array
    {
        return is_object($item) || $item instanceof stdClass ? (array)$item : $item;
    }
}
