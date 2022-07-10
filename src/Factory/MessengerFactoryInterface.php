<?php

namespace Mailery\Messenger\Factory;

use Mailery\Messenger\MessengerInterface;

interface MessengerFactoryInterface
{

    /**
     * @param object $channel
     * @return self
     */
    public function withChannel(object $channel): self;

    /**
     * @return MessengerInterface
     */
    public function create(): MessengerInterface;

}
