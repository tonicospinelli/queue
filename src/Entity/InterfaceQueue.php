<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.14
 *
 */

namespace Queue\Entity;


interface InterfaceQueue
{
    const QUEUE_DURABLE = true;
    const QUEUE_NOT_DURABLE = false;
    const QUEUE_AUTO_DELETE = true;
    const QUEUE_NOT_AUTO_DELETE = false;
} 