<?php

namespace Mailery\Messenger\Factory;

use Mailery\Messenger\MessageInterface;
use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Recipient;

interface MessageFactoryInterface
{

    /**
     * @param Campaign $campaign
     * @return self
     */
    public function withCampaign(Campaign $campaign): self;

    /**
     * @param Recipient $recipient
     * @return self
     */
    public function withRecipient(Recipient $recipient): self;

    /**
     * @return MessageInterface
     */
    public function create(): MessageInterface;

}