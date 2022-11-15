<?php

namespace Mailery\Messenger;

interface LoggerInterface extends \Psr\Log\LoggerInterface
{

    /**
     * @param bool $final
     * @return void
     */
    public function flush(bool $final = false): void;

}
