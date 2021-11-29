<?php

namespace GoDaddy\WordPress\MWC\Common\Traits;

use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use ReflectionClass;
use ReflectionProperty;

/**
 * A trait that allows a given class/object to convert its state to an array.
 *
 * @since x.y.z
 */
trait CanConvertToArrayTrait
{
    /** @var bool Convert Private Properties to Array Output */
    protected $toArrayIncludePrivate = false;

    /** @var bool Convert Protected Properties to Array Output */
    protected $toArrayIncludeProtected = true;

    /** @var bool Convert Public Properties to Array Output */
    protected $toArrayIncludePublic = true;

    /**
     * Converts all class data properties to an array.
     *
     * @since x.y.z
     *
     * @return array
     */
    public function toArray() : array
    {
        $array = [];

        foreach ((new ReflectionClass(static::class))->getProperties() as $property) {
            if ($this->toArrayShouldPropertyBeAccessible($property)) {
                $property->setAccessible(true);

                $array[$property->getName()] = $property->getValue($this);
            }
        }

        return ArrayHelper::except($array, [
            'toArrayIncludePrivate',
            'toArrayIncludeProtected',
            'toArrayIncludePublic',
        ]);
    }

    /**
     * Checks if the property is accessible for {@see toArray()} conversion.
     *
     * @since x.y.z
     *
     * @param ReflectionProperty $property
     * @return bool
     */
    private function toArrayShouldPropertyBeAccessible(ReflectionProperty $property) : bool
    {
        if ($this->toArrayIncludePublic && $property->isPublic()) {
            return true;
        }

        if ($this->toArrayIncludeProtected && $property->isProtected()) {
            return true;
        }

        if ($this->toArrayIncludePrivate && $property->isPrivate()) {
            return true;
        }

        return false;
    }
}
