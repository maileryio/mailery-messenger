<?php

namespace Mailery\Messenger\Exception;

class MessengerException extends \RuntimeException
{

    /**
     * @var string
     */
    private ?string $userMessage;

    /**
     * @param string $message
     * @return self
     */
    public function setUserMessage(string $message): self
    {
        $this->userMessage = $message;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUserMessage(): ?string
    {
        return $this->userMessage;
    }

}
