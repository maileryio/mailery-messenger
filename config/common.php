<?php

use Mailery\Messenger\Middleware\MiddlewareIteratorAggregate;
use Mailery\Messenger\Receivers\ReceiversLocator;
use Psr\Container\ContainerInterface;
use Pheanstalk\Contract\PheanstalkInterface;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Bridge\Beanstalkd\Transport\Connection as BeanstalkdConnection;
use Symfony\Component\Messenger\Transport\Sender\SendersLocator;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;
use Symfony\Component\Messenger\Command\ConsumeMessagesCommand;
use Yiisoft\Definitions\Reference;
use Yiisoft\Definitions\DynamicReference;
use Yiisoft\Injector\Injector;

return [
    BeanstalkdConnection::class => [
        'class' => BeanstalkdConnection::class,
        '__construct()' => [
            'configuration' => [],
            'client' => Reference::to(PheanstalkInterface::class),
        ],
    ],

    MessageBusInterface::class => [
        'class' => MessageBus::class,
        '__construct()' => [
            'middlewareHandlers' => DynamicReference::to(static function (Injector $injector) use($params) {
                return ($injector->make(MiddlewareIteratorAggregate::class))
                    ->withMiddlewares($params['maileryio/mailery-messenger']['middlewares']);
            }),
        ],
    ],

    SendersLocatorInterface::class => static function (ContainerInterface $container) use($params) {
        return new SendersLocator(
            $params['maileryio/mailery-messenger']['senders'],
            $container
        );
    },

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
            'receiverLocator' => DynamicReference::to(static function (ContainerInterface $container) use($params) {
                return new ReceiversLocator(
                    $params['maileryio/mailery-messenger']['receivers'],
                    $container
                );
            }),
        ],
    ],
];
