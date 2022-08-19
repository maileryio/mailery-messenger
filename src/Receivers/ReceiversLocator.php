<?php

namespace Mailery\Messenger\Receivers;

use Psr\Container\ContainerInterface;

class ReceiversLocator implements ReceiversLocatorInterface
{

    /**
     * @param array $receiversMap
     * @param ContainerInterface $container
     */
    public function __construct(
        private array $receiversMap,
        private ContainerInterface $container
    ) {}

    /**
     * @inheritdoc
     */
    public function get(string $id)
    {
        $receiverAlias = $this->receiversMap[$id] ?? null;

        if (!$this->container->has($receiverAlias)) {
            throw new \RuntimeException(sprintf('Invalid receivers configuration: receiver "%s" is not in the receivers locator.', $id));
        }

        return $this->container->get($receiverAlias);
    }

    /**
     * @inheritdoc
     */
    public function has(string $id): bool
    {
        return isset($this->receiversMap[$id]);
    }

}
