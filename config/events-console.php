<?php

declare(strict_types=1);

use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Messenger\EventListener\AddErrorDetailsStampListener;
use Symfony\Component\Messenger\EventListener\SendFailedMessageForRetryListener;
use Symfony\Component\Messenger\EventListener\SendFailedMessageToFailureTransportListener;

return [
    WorkerMessageFailedEvent::class => [
        [AddErrorDetailsStampListener::class, 'onMessageFailed'],
        [SendFailedMessageForRetryListener::class, 'onMessageFailed'],
        [SendFailedMessageToFailureTransportListener::class, 'onMessageFailed'],
    ],
];
