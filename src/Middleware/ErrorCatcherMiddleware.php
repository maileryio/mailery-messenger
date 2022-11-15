<?php

namespace Mailery\Messenger\Middleware;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Yiisoft\ErrorHandler\Renderer\PlainTextRenderer;

class ErrorCatcherMiddleware implements MiddlewareInterface
{

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        private LoggerInterface $logger
    ) {}

    /**
     * @param Envelope $envelope
     * @param StackInterface $stack
     * @return Envelope
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        try {
            return $stack->next()->handle($envelope, $stack);
        } catch (\Throwable $t) {
            $this->error($t instanceof HandlerFailedException ? $t->getPrevious() : $t);

            throw $t;
        }

        return $envelope;
    }

    /**
     * @param \Throwable $t
     */
    private function error(\Throwable $t)
    {

        $this->logger->error((string) (new PlainTextRenderer())->renderVerbose($t));
    }

}
