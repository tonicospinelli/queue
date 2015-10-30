<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.08.28
 *
 */

namespace Queue;

use Queue\Driver\MessageInterface;
use Queue\Entity\InterfaceExchange;

interface ProducerInterface
{
    /**
     * @param MessageInterface $message
     * @return void
     */
    public function publish(MessageInterface $message);

    /**
     * @return \Queue\Entity\AbstractExchange
     */
    public function exchange();

}
