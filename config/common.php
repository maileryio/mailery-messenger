<?php

use Mailery\Messenger\SimpleContainer;
use Mailery\Messenger\Middleware\MiddlewareIteratorAggregate;
use Mailery\Messenger\Retry\RetryStrategyLocator;
use Mailery\Messenger\Retry\RetryStrategyLocatorInterface;
use Mailery\Messenger\Transport\TransportsLocator;
use Mailery\Messenger\Transport\TransportsLocatorInterface;
use Mailery\Messenger\Transport\FailureTransportsLocator;
use Mailery\Messenger\Transport\FailureTransportsLocatorInterface;
use Mailery\Messenger\Logger as MessengerLogger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Command\ConsumeMessagesCommand;
use Symfony\Component\Messenger\Command\FailedMessagesShowCommand;
use Symfony\Component\Messenger\EventListener\SendFailedMessageForRetryListener;
use Symfony\Component\Messenger\EventListener\SendFailedMessageToFailureTransportListener;
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

    SendersLocatorInterface::class => [
        'class' => SendersLocator::class,
        '__construct()' => [
            $params['maileryio/mailery-messenger']['senders'],
            Reference::to(TransportsLocatorInterface::class),
        ],
    ],

    TransportsLocatorInterface::class => [
        'class' => TransportsLocator::class,
        '__construct()' => [
            array_map(
                static fn (array $reciever) => $reciever['transport'],
                $params['maileryio/mailery-messenger']['recievers']
            ),
        ],
    ],

    FailureTransportsLocatorInterface::class => [
        'class' => FailureTransportsLocator::class,
        '__construct()' => [
            'providedServices' => ['errored' => 'errored'],
            'transportsLocator' => Reference::to(TransportsLocatorInterface::class),
        ],
    ],

    RetryStrategyLocatorInterface::class => [
        'class' => RetryStrategyLocator::class,
        '__construct()' => [
            array_map(
                static fn (array $reciever) => $reciever['retryStrategy'],
                $params['maileryio/mailery-messenger']['recievers']
            ),
        ],
    ],

    SendFailedMessageForRetryListener::class => [
        'class' => SendFailedMessageForRetryListener::class,
        '__construct()' => [
            'sendersLocator' => Reference::to(TransportsLocatorInterface::class),
            'retryStrategyLocator' => Reference::to(RetryStrategyLocatorInterface::class),
            'logger' => Reference::to(MessengerLogger::class),
        ],
    ],

    SendFailedMessageToFailureTransportListener::class => [
        'class' => SendFailedMessageToFailureTransportListener::class,
        '__construct()' => [
            'failureSenders' => DynamicReference::to([
                'class' => SimpleContainer::class,
                '__construct()' => [
                    array_map(
                        static fn () => $params['maileryio/mailery-messenger']['recievers']['errored']['transport'],
                        $params['maileryio/mailery-messenger']['recievers']
                    ),
                ],
            ]),
            'logger' => Reference::to(MessengerLogger::class),
        ],
    ],

    ConsumeMessagesCommand::class => [
        'class' => ConsumeMessagesCommand::class,
        '__construct()' => [
            'routableBus' => DynamicReference::to([
                'class' => RoutableMessageBus::class,
                '__construct()' => [
                    'busLocator' => DynamicReference::to(static fn () => new SimpleContainer([])),
                    'fallbackBus' => Reference::to(MessageBusInterface::class),
                ],
            ]),
            'receiverLocator' => Reference::to(TransportsLocatorInterface::class),
            'logger' => Reference::to(MessengerLogger::class),
        ],
    ],

    FailedMessagesShowCommand::class => [
        'class' => FailedMessagesShowCommand::class,
        '__construct()' => [
            'globalFailureReceiverName' => 'errored',
            'failureTransports' => Reference::to(FailureTransportsLocatorInterface::class),
        ],
    ],
];
