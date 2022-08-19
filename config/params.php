<?php

use Symfony\Component\Messenger\Command\ConsumeMessagesCommand;
use Symfony\Component\Messenger\Middleware\SendMessageMiddleware;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Bridge\Beanstalkd\Transport\BeanstalkdTransport;

return [
    'maileryio/mailery-messenger' => [
        'middlewares' => [
            SendMessageMiddleware::class,
            HandleMessageMiddleware::class,
        ],
        'handlers' => [],
        'senders' => [],
        'receivers' => [
            'async' => BeanstalkdTransport::class,
        ],
    ],

    'yiisoft/yii-console' => [
        'commands' => [
            'messenger/consume' => ConsumeMessagesCommand::class,
        ],
    ],
];
