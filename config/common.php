<?php

use Mailery\Messenger\Middleware\MiddlewareIteratorAggregate;
use Mailery\Messenger\Transports\TransportsLocator;
use Mailery\Messenger\Transports\TransportsLocatorInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Command\ConsumeMessagesCommand;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\Sender\SendersLocator;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;
use Yiisoft\Definitions\DynamicReference;
use Yiisoft\Injector\Injector;

return [
    MessageBusInterface::class => [
        'class' => MessageBus::class,
        '__construct()' => [
            DynamicReference::to(static function (Injector $injector) use($params) {
                return ($injector->make(MiddlewareIteratorAggregate::class))
                    ->withMiddlewares($params['maileryio/mailery-messenger']['middlewares']);
            }),
        ],
    ],

    SendersLocatorInterface::class => static function (ContainerInterface $container) use($params) {
        return new SendersLocator(
            $params['maileryio/mailery-messenger']['senders'],
            $container->get(TransportsLocatorInterface::class)
        );
    },

    TransportsLocatorInterface::class => [
        'class' => TransportsLocator::class,
        '__construct()' => [
            $params['maileryio/mailery-messenger']['transports']
        ],
    ],

    HandlersLocatorInterface::class => static function (ContainerInterface $container) use($params) {
        return new HandlersLocator(array_map(
            function (array $handlers) use($container) {
                return array_map(
                    function (string $handler) use($container) {
                        return $container->get($handler);
                    },
                    $handlers
                );
            },
            $params['maileryio/mailery-messenger']['handlers']
        ));
    },

    ConsumeMessagesCommand::class => [
        'class' => ConsumeMessagesCommand::class,
        '__construct()' => [
            'receiverLocator' => DynamicReference::to(TransportsLocatorInterface::class),
        ],
    ],
];
