<?php

namespace Mailery\Amazon\Ses\Factory;

use Mailery\Messenger\MessageInterface;
use Mailery\Messenger\Factory\MessageFactoryInterface;
use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Sender\Entity\Sender;
use Mailery\Template\Entity\Template;
use InvalidArgumentException;

class MessageFactory implements MessageFactoryInterface
{
    /**
     * @var string
     */
    private string $class;

    /**
     * @var Campaign
     */
    private Campaign $campaign;

    /**
     * @var Recipient
     */
    private Recipient $recipient;

    /**
     * @var Sender
     */
    private Sender $sender;

    /**
     * @var Template
     */
    private Template $template;

    /**
     * @param string $class
     * @throws InvalidArgumentException
     */
    public function __construct(string $class)
    {
        if (!is_subclass_of($class, MessageInterface::class)) {
            throw new InvalidArgumentException(sprintf(
                'Class "%s" does not implement "%s".',
                $class,
                MessageInterface::class,
            ));
        }

        $this->class = $class;
    }

    /**
     * @inheritdoc
     */
    public function withCampaign(Campaign $campaign): MessageFactoryInterface
    {
        $new = clone $this;
        $new->campaign = $campaign;
        $new->sender = $campaign->getSender();
        $new->template = $campaign->getTemplate();

        return $new;
    }

    /**
     * @inheritdoc
     */
    public function withRecipient(Recipient $recipient): MessageFactoryInterface
    {
        $new = clone $this;
        $new->recipient = $recipient;

        return $new;
    }

    /**
     * @inheritdoc
     */
    public function create(): MessageInterface
    {
        /** @var MessageInterface $message */
        $message = new $this->class();

        return $message
            ->withRecipient($this->recipient)
            ->withSender($this->sender)
            ->withTemplate($this->template)
            ->withSubject($this->campaign->getName())
        ;
    }

}