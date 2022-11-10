<?php

namespace Mailery\Messenger\Retry;

use Psr\Container\ContainerInterface;

class RetryStrategyLocator implements RetryStrategyLocatorInterface
{

    /**
     * @param array $strategies
     * @param ContainerInterface $container
     */
    public function __construct(
        private array $strategies
    ) {}

    /**
     * @inheritdoc
     */
    public function get(string $id)
    {
        if (($strategy = $this->strategies[$id] ?? null) === null) {
            throw new \RuntimeException(sprintf('Invalid strategies configuration: transport "%s" is not in the strategy locator.', $id));
        }

        return $strategy;
    }

    /**
     * @inheritdoc
     */
    public function has(string $id): bool
    {
        return isset($this->strategies[$id]);
    }

}
