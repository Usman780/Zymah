<?php

namespace GoDaddy\WordPress\MWC\Common\Traits;

use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use ReflectionClass;

trait CanBulkAssignPropertiesTrait
{
    /**
     * Sets all class properties that have setter methods using the given data.
     *
     * @since x.y.z
     *
     * @param array $data property values
     * @return self
     */
    public function setProperties(array $data): self
    {
        foreach ((new ReflectionClass(static::class))->getProperties() as $property) {
            if (! ArrayHelper::exists($data, $property->getName())) {
                continue;
            }

            if (method_exists($this, 'set'.ucfirst($property->getName()))) {
                $this->{'set'.ucfirst($property->getName())}(ArrayHelper::get($data, $property->getName()));
            }
        }

        return $this;
    }
}
