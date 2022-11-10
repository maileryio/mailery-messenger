<?php

namespace Mailery\Messenger\Transport;

use Mailery\Messenger\Transport\TransportsLocatorInterface;

class FailureTransportsLocator implements FailureTransportsLocatorInterface
{

    /**
     * @param array $providedServices
     * @param TransportsLocatorInterface $transportsLocator
     */
    public function __construct(
        private array $providedServices,
        private TransportsLocatorInterface $transportsLocator
    ) {}

    /**
     * @param string $id
     * @return mixed
     */
    public function get(string $id): mixed
    {
        return $this->transportsLocator->get($id);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return $this->transportsLocator->has($id);
    }

    /**
     * @return array
     */
    public function getProvidedServices(): array
    {
        return $this->providedServices;
    }

}
