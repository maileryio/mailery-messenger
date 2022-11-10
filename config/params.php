<?php

use Mailery\Messenger\Transport\BeanstalkdTransportFactory;
use Symfony\Component\Messenger\Command\ConsumeMessagesCommand;
use Symfony\Component\Messenger\Command\FailedMessagesShowCommand;
use Symfony\Component\Messenger\Middleware\SendMessageMiddleware;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Middleware\FailedMessageProcessingMiddleware;
use Symfony\Component\Messenger\Retry\MultiplierRetryStrategy;
use Yiisoft\Definitions\DynamicReference;
use Yiisoft\Definitions\Reference;

return [
    'maileryio/mailery-messenger' => [
        'middlewares' => [
            SendMessageMiddleware::class,
            HandleMessageMiddleware::class,
            FailedMessageProcessingMiddleware::class,
        ],
        'handlers' => [],
        'senders' => [],
        'recievers' => [
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
