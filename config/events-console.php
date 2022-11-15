<?php

declare(strict_types=1);

use Mailery\Messenger\EventListener\FlushLoggerListener;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Messenger\Event\WorkerRunningEvent;
use Symfony\Component\Messenger\EventListener\AddErrorDetailsStampListener;
use Symfony\Component\Messenger\EventListener\SendFailedMessageForRetryListener;
use Symfony\Component\Messenger\EventListener\SendFailedMessageToFailureTransportListener;

return [
    WorkerMessageFailedEvent::class => [
        [AddErrorDetailsStampListener::class, 'onMessageFailed'],
        [SendFailedMessageForRetryListener::class, 'onMessageFailed'],
        [SendFailedMessageToFailureTransportListener::class, 'onMessageFailed'],
    ],

    WorkerRunningEvent::class => [
        [FlushLoggerListener::class, 'flushLogger'],
    ],
];
