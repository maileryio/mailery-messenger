<?php

namespace Mailery\Messenger;

use Psr\Log\LoggerTrait;
use Psr\Log\LoggerInterface;

class Logger implements LoggerInterface
{

    use LoggerTrait;

    /**
     * @param string $category
     * @param LoggerInterface $innerLogger
     */
    public function __construct(
        private string $category,
        private LoggerInterface $innerLogger
    ) {}

    /**
     * @inheritdoc
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $context['category'] = $this->category;
        $this->innerLogger->log($level, $message, $context);
    }

}
