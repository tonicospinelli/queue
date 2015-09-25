<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.14
 *
 */

namespace Queue\Entity;


abstract class AbstractBindExchange extends AbstractBind
{
    /**
     * @return AbstractExchange
     */
    abstract function getTargetExchange();
} 