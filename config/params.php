<?php

use Symfony\Component\Messenger\Command\ConsumeMessagesCommand;
use Symfony\Component\Messenger\Middleware\SendMessageMiddleware;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

return [
    'maileryio/mailery-messenger' => [
        'middlewares' => [
            SendMessageMiddleware::class,
            HandleMessageMiddleware::class,
        ],
        'handlers' => [],
        'senders' => [],
        'transports' => [],
    ],

    'yiisoft/yii-console' => [
        'commands' => [
            'messenger/consume' => ConsumeMessagesCommand::class,
        ],
    ],
];
