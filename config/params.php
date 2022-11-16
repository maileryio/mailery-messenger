<?php

use Mailery\Messenger\Middleware\ErrorCatcherMiddleware;
use Mailery\Messenger\Transport\BeanstalkdTransportFactory;
use Symfony\Component\Messenger\Command\ConsumeMessagesCommand;
use Symfony\Component\Messenger\Command\FailedMessagesShowCommand;
use Symfony\Component\Messenger\Middleware\SendMessageMiddleware;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Middleware\FailedMessageProcessingMiddleware;
use Symfony\Component\Messenger\Retry\MultiplierRetryStrategy;
use Symfony\Component\Messenger\Transport\Sync\SyncTransport;
use Yiisoft\Definitions\DynamicReference;
use Yiisoft\Definitions\Reference;

return [
    'maileryio/mailery-messenger' => [
        'middlewares' => [
            ErrorCatcherMiddleware::class,
            SendMessageMiddleware::class,
            HandleMessageMiddleware::class,
            FailedMessageProcessingMiddleware::class,
        ],
        'handlers' => [],
        'senders' => [],
        'recievers' => [
            'sync' => [
                'transport' => Reference::to(SyncTransport::class),
            ],
            'errored' => [
                'transport' => DynamicReference::to(new BeanstalkdTransportFactory([
                    'tube_name' => 'errored'
                ])),
                'retryStrategy' => Reference::to(MultiplierRetryStrategy::class),
            ],
        ],
    ],

    'yiisoft/yii-console' => [
        'commands' => [
            'messenger/consume' => ConsumeMessagesCommand::class,
            'messenger/failed/show' => FailedMessagesShowCommand::class,
        ],
    ],
];
