<?php

namespace Queue\Component\Configuration\Definition;


use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeParentInterface;

abstract class Section implements NodeParentInterface
{
    /**
     * @var string
     */
    protected $name;

    public function __construct($name, ArrayNodeDefinition $parent = null)
    {
        $this->name = $name;
        $this->defineNodeStructure($parent);
    }

    abstract public function defineNodeStructure(ArrayNodeDefinition $parent);
}
