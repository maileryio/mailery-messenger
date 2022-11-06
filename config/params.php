<?php

use Symfony\Component\Messenger\Command\ConsumeMessagesCommand;
use Symfony\Component\Messenger\Command\FailedMessagesShowCommand;
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
        'buses' => [],
    ],

    'yiisoft/yii-console' => [
        'commands' => [
            'messenger/consume' => ConsumeMessagesCommand::class,
//            'messenger/failed/show' => FailedMessagesShowCommand::class,
        ],
    ],
];


// 1. сделать свой логгер для мессенджера
// 2. прикрутить свой ивент диспетчер
// 3. сделать отправку в фейлед трубу
