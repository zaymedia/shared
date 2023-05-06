<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\Queue;

interface Queue
{
    public function publish(
        string $queue,
        array|string $message,
        bool $durable,
        bool $autoDelete
    ): void;

    public function consume(
        string $queue,
        callable $callback,
        bool $durable,
        bool $autoDelete,
        bool $noAck
    ): void;
}
