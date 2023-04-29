<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\Queue;

use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ implements Queue
{
    private ?AMQPStreamConnection $connection = null;
    private ?AMQPChannel $channel = null;

    public function __construct(
        string $host,
        int $port,
        string $user,
        string $password
    ) {
        try {
            $this->connection = new AMQPStreamConnection(
                host: $host,
                port: $port,
                user: $user,
                password: $password
            );

            $this->channel = $this->connection->channel();
        } catch (Exception) {
        }
    }

    public function publish(string $queue, array|string $message): void
    {
        if (null === $this->channel) {
            return;
        }

        if (\is_array($message)) {
            $message = json_encode($message);
        }

        $this->declareQueue($queue);

        $this->channel->basic_publish(
            msg: new AMQPMessage($message),
            routing_key: $queue
        );
    }

    public function consume(string $queue, callable $callback): void
    {
        if (null === $this->channel) {
            return;
        }

        $this->declareQueue($queue);

        $this->channel->basic_consume(
            queue: $queue,
            no_ack: true,
            callback: $callback
        );

        /** @psalm-suppress InternalProperty */
        while (\count($this->channel->callbacks)) {
            $this->channel->wait();
        }

        try {
            $this->channel->close();
            $this->connection?->close();
        } catch (Exception) {
        }
    }

    private function declareQueue(string $queue): void
    {
        $this->channel?->queue_declare(
            queue: $queue,
            durable: true,
            auto_delete: false
        );
    }
}
