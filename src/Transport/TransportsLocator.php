<?php

namespace Mailery\Messenger\Transport;

use Psr\Container\ContainerInterface;

class TransportsLocator implements TransportsLocatorInterface
{

    /**
     * @param array $transports
     * @param ContainerInterface $container
     */
    public function __construct(
        private array $transports
    ) {}

    /**
     * @inheritdoc
     */
    public function get(string $id)
    {
        if (($transport = $this->transports[$id] ?? null) === null) {
            throw new \RuntimeException(sprintf('Invalid transports configuration: transport "%s" is not in the transports locator.', $id));
        }

        return $transport;
    }

    /**
     * @inheritdoc
     */
    public function has(string $id): bool
    {
        return isset($this->transports[$id]);
    }

}
