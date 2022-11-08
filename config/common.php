<?php

use Mailery\Messenger\Buses\BusesLocator;
use Mailery\Messenger\Buses\BusesLocatorInterface;
use Mailery\Messenger\Middleware\MiddlewareIteratorAggregate;
use Mailery\Messenger\Transports\TransportsLocator;
use Mailery\Messenger\Transports\TransportsLocatorInterface;
use Mailery\Messenger\Logger as MessengerLogger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Command\ConsumeMessagesCommand;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\RoutableMessageBus;
use Symfony\Component\Messenger\Transport\Sender\SendersLocator;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;
use Yiisoft\Definitions\DynamicReference;
use Yiisoft\Definitions\Reference;

return [
    MessengerLogger::class => [
        'class' => MessengerLogger::class,
        '__construct()' => [
            'category' => 'messenger',
            'innerLogger' => Reference::to(LoggerInterface::class),
        ],
    ],

    MessageBusInterface::class => [
        'class' => MessageBus::class,
        '__construct()' => [
            DynamicReference::to(static function (ContainerInterface $container, MessengerLogger $logger) use($params) {
                return (new MiddlewareIteratorAggregate($container, $logger))
                    ->withMiddlewares($params['maileryio/mailery-messenger']['middlewares']);
            }),
        ],
    ],

    SendersLocatorInterface::class => static function (TransportsLocatorInterface $locator) use($params) {
        return new SendersLocator($params['maileryio/mailery-messenger']['senders'], $locator);
    },

    TransportsLocatorInterface::class => [
        'class' => TransportsLocator::class,
        '__construct()' => [
            $params['maileryio/mailery-messenger']['transports'],
        ],
    ],

    BusesLocatorInterface::class => [
        'class' => BusesLocator::class,
        '__construct()' => [
            $params['maileryio/mailery-messenger']['buses'],
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
            'routableBus' => DynamicReference::to([
                'class' => RoutableMessageBus::class,
                '__construct()' => [
                    Reference::to(BusesLocatorInterface::class),
                    Reference::to(MessageBusInterface::class),
                ],
            ]),
            'receiverLocator' => Reference::to(TransportsLocatorInterface::class),
            'logger' => Reference::to(MessengerLogger::class),
        ],
    ],
];
