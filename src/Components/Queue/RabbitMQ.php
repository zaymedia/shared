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

    private string $host;
    private int $port;
    private string $user;
    private string $password;

    public function __construct(
        string $host,
        int $port,
        string $user,
        string $password
    ) {
        $this->host     = $host;
        $this->port     = $port;
        $this->user     = $user;
        $this->password = $password;
    }

    public function publish(
        string $queue,
        array|string $message,
        bool $durable = true,
        bool $autoDelete = false
    ): void {
        if (!$this->isConnected()) {
            $this->connect();
        }

        if (null === $this->channel) {
            return;
        }

        if (\is_array($message)) {
            $message = json_encode($message);
        }

        $this->declareQueue(
            queue: $queue,
            durable: $durable,
            autoDelete: $autoDelete
        );

        $this->channel->basic_publish(
            msg: new AMQPMessage($message),
            routing_key: $queue
        );
    }

    public function consume(
        string $queue,
        callable $callback,
        bool $durable = true,
        bool $autoDelete = false,
        bool $noAck = true
    ): void {
        while (true) {
            if (!$this->isConnected()) {
                $this->connect();
            }

            if (null === $this->channel) {
                $this->resetConnection();
                $this->sleep();
                continue;
            }

            $this->declareQueue(
                queue: $queue,
                durable: $durable,
                autoDelete: $autoDelete
            );

            try {
                $this->channel->basic_consume(
                    queue: $queue,
                    no_ack: $noAck,
                    callback: $callback
                );

                while ($this->channel->is_open()) {
                    $this->channel->wait();
                }
            } catch (Exception) {
            }

            $this->resetConnection();
        }
    }

    private function connect(): void
    {
        $this->resetConnection();

        try {
            $this->connection = new AMQPStreamConnection(
                host: $this->host,
                port: $this->port,
                user: $this->user,
                password: $this->password
            );

            $this->channel = $this->connection->channel();
        } catch (Exception) {
        }
    }

    private function resetConnection(): void
    {
        try {
            $this->channel?->close();
            $this->connection?->close();
        } catch (Exception) {
        }

        $this->channel = null;
        $this->connection = null;
    }

    private function isConnected(): bool
    {
        return $this->connection?->isConnected() ?? false;
    }

    private function declareQueue(string $queue, bool $durable, bool $autoDelete): void
    {
        $this->channel?->queue_declare(
            queue: $queue,
            durable: $durable,
            auto_delete: $autoDelete,
        );
    }

    private function sleep(): void
    {
        sleep(30);
    }
}
