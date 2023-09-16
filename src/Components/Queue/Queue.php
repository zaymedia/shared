<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\Queue;

interface Queue
{
    public function publish(
        string $queue,
        array|string $message,
        bool $durable = true,
        bool $autoDelete = false
    ): void;

    public function consume(
        string $queue,
        callable $callback,
        bool $durable = true,
        bool $autoDelete = false,
        bool $noAck = true,
        int $count = 100
    ): void;
}
