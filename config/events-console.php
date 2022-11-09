<?php

declare(strict_types=1);

use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;

return [
    WorkerMessageHandledEvent::class => static function () {
        var_dump('WorkerMessageHandledEvent');exit;
    },
];
