<?php

namespace Mailery\Messenger;

interface MessengerInterface
{

    /**
     * @param MessageInterface $message
     * @return void
     */
    public function send(MessageInterface $message): void;

}