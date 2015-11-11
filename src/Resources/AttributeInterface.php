<?php

namespace Queue\Resources;

interface AttributeInterface
{
    /**
     * @return array
     */
    public function getAttributes();

    /**
     * @param string $name
     * @param string $defaultValue
     * @return string
     */
    public function getAttribute($name, $defaultValue = null);

    /**
     * @param string $name
     * @return bool
     */
    public function hasAttribute($name);
}
