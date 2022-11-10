<?php

namespace Mailery\Messenger\Transport;

use Psr\Container\ContainerInterface;
use Pheanstalk\Contract\PheanstalkInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Component\Messenger\Bridge\Beanstalkd\Transport\BeanstalkdTransport;
use Symfony\Component\Messenger\Bridge\Beanstalkd\Transport\Connection as BeanstalkdConnection;

class BeanstalkdTransportFactory
{

    /**
     * @param array $options
     */
    public function __construct(
        private array $options
    ) {}

    /**
     * @param ContainerInterface $container
     * @return TransportInterface
     */
    public function __invoke(ContainerInterface $container): TransportInterface
    {
        return new BeanstalkdTransport(
            new BeanstalkdConnection(
                $this->options,
                $container->get(PheanstalkInterface::class)
            )
        );
    }

}
