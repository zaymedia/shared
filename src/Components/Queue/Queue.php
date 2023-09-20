<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\Queue;

interface Queue
{
    public function publish(
        string $queue,
        array|string $message,
        bool $durable = true,
        bool $autoDelete = false,
        ?int $ttl = null
    ): void;

    public function consume(
        string $queue,
        callable $callback,
        bool $durable = true,
        bool $autoDelete = false,
        bool $withAck = false,
        int $prefetchSize = 0,
        int $prefetchCount = 0,
        ?int $ttl = null
    ): void;
}
