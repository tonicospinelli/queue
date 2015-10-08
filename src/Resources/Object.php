<?php

namespace Queue\Resources;

/**
 * Class Tunnel is a Value Object.
 * @package Driver
 */
abstract class Object
{
    public function setData(array $attributes = array())
    {
        $camelcaseParser = function ($matches) {
            return strtoupper($matches[0][1]);
        };
        foreach ($attributes as $name => $value) {
            $name = preg_replace_callback("/_(.)/", $camelcaseParser, $name);
            $method = 'set' . ucfirst($name);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
}
