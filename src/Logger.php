<?php

namespace Mailery\Messenger;

use Psr\Log\LoggerTrait;
use Psr\Log\LoggerInterface as PsrLoggerInterface;
use Mailery\Messenger\LoggerInterface;

class Logger implements LoggerInterface
{

    use LoggerTrait;

    /**
     * @param string $category
     * @param PsrLoggerInterface $innerLogger
     */
    public function __construct(
        private string $category,
        private PsrLoggerInterface $innerLogger
    ) {}

    /**
     * @inheritdoc
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $context['category'] = $this->category;
        $this->innerLogger->log($level, $message, $context);
    }

    /**
     * @param bool $final
     * @return void
     */
    public function flush(bool $final = false): void
    {
        if (method_exists($this->innerLogger, 'flush')) {
            $this->innerLogger->flush($final);
        }
    }

}
