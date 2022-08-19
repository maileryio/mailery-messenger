<?php

namespace Mailery\Messenger\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class MiddlewareIteratorAggregate implements \IteratorAggregate
{

    /**
     * @var array
     */
    private array $middlewares = [];

    /**
     * @param ContainerInterface $container
     */
    public function __construct(
        private ContainerInterface $container,
        private LoggerInterface $logger,
    ) {}

    /**
     * @param array $middlewares
     * @return self
     */
    public function withMiddlewares(array $middlewares): self
    {
        $new = clone $this;
        $new->middlewares = $middlewares;

        return $new;
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayObject(array_map(
            function ($id) {
                $middleware = $this->container->get($id);

                if (method_exists($middleware, 'setLogger')) {
                    $middleware->setLogger($this->logger);
                }
                return $middleware;
            },
            $this->middlewares
        ));
    }

}