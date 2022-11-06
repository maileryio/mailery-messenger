<?php

namespace Mailery\Messenger\Buses;

use Psr\Container\ContainerInterface;

class BusesLocator implements BusesLocatorInterface
{

    /**
     * @param array $buses
     * @param ContainerInterface $container
     */
    public function __construct(
        private array $buses
    ) {}

    /**
     * @inheritdoc
     */
    public function get(string $id)
    {
        if (($transport = $this->buses[$id] ?? null) === null) {
            throw new \RuntimeException(sprintf('Invalid buses configuration: bus "%s" is not in the buses locator.', $id));
        }

        return $transport;
    }

    /**
     * @inheritdoc
     */
    public function has(string $id): bool
    {
        return isset($this->buses[$id]);
    }

}
