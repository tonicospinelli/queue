<?php

namespace Queue\Resources;

interface Durable
{

    public function isDurable();

    public function setDurable($confirm = true);
}
