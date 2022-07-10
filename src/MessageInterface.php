<?php

namespace Mailery\Messenger;

use Mailery\Campaign\Entity\Recipient;
use Mailery\Sender\Entity\Sender;
use Mailery\Template\Entity\Template;

interface MessageInterface
{

    /**
     * @param Recipient $recipient
     * @return self
     */
    public function withRecipient(Recipient $recipient): self;

    /**
     * @param Sender $sender
     * @return self
     */
    public function withSender(Sender $sender): self;

    /**
     * @param string $subject
     * @return self
     */
    public function withSubject(string $subject): self;

    /**
     * @param Template $template
     * @return self
     */
    public function withTemplate(Template $template): self;

    /**
     * @return mixed
     */
    public function getRawMessage(): mixed;

}
