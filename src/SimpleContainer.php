<?php

namespace Mailery\Messenger;

use Psr\Container\ContainerInterface;

class SimpleContainer implements ContainerInterface
{

    /**
     * @param array $definitions
     */
    public function __construct(
        private array $definitions
    ) {}

    /**
     * @inheritdoc
     */
    public function get(string $id)
    {
        if (($definition = $this->definitions[$id] ?? null) === null) {
            throw new \RuntimeException(sprintf('Invalid definitions configuration: definition "%s" is not in the locator.', $id));
        }

        return $definition;
    }

    /**
     * @inheritdoc
     */
    public function has(string $id): bool
    {
        return isset($this->definitions[$id]);
    }

}
