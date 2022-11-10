<?php

namespace Mailery\Messenger\Transport;

use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceProviderInterface;

interface FailureTransportsLocatorInterface extends ServiceProviderInterface, ContainerInterface
{
}
