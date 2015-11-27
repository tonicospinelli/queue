<?php

namespace Queue\Resources;

/**
 * Class Value Object.
 * @package Driver
 */
abstract class Object implements AttributeInterface
{
    private $attributes;

    /**
     * Object constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = array())
    {
        $this->attributes = $attributes;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @param string $defaultValue
     * @return string
     */
    public function getAttribute($name, $defaultValue = null)
    {
        if ($this->hasAttribute($name)) {
            return $this->attributes[$name];
        }
        return $defaultValue;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasAttribute($name)
    {
        return isset($this->attributes[$name]);
    }
}
