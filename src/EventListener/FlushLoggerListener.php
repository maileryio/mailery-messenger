<?php

namespace Mailery\Messenger\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\WorkerRunningEvent;
use Mailery\Messenger\LoggerInterface;

class FlushLoggerListener implements EventSubscriberInterface
{

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        private LoggerInterface $logger
    ) {}

    /**
     * @param WorkerRunningEvent $event
     * @return void
     */
    public function flushLogger(WorkerRunningEvent $event): void
    {
        $this->logger->flush(true);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            WorkerRunningEvent::class => ['flushLogger', -1024],
        ];
    }

}
