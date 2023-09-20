<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\Queue;

use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class RabbitMQ implements Queue
{
    private ?AMQPStreamConnection $connection = null;
    private ?AMQPChannel $channel = null;

    private string $host;
    private int $port;
    private string $user;
    private string $password;

    private string $dlxExchange = 'dlx_exchange';

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
        bool $autoDelete = false,
        ?int $ttl = null
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

        $this->channel->exchange_declare($this->dlxExchange, 'direct');

        $this->declareQueue(
            queue: $queue,
            durable: $durable,
            autoDelete: $autoDelete,
            ttl: $ttl,
            dlxExchange: $this->dlxExchange
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
        bool $withAck = false,
        int $prefetchSize = 0,
        int $prefetchCount = 0,
        ?int $ttl = null
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
                autoDelete: $autoDelete,
                ttl: $ttl,
                dlxExchange: $this->dlxExchange
            );

            try {
                if ($withAck) {
                    $this->channel->basic_qos($prefetchSize, $prefetchCount, false);
                }

                $this->channel->basic_consume(
                    queue: $queue,
                    no_ack: !$withAck,
                    callback: $callback,
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

    private function declareQueue(
        string $queue,
        bool $durable,
        bool $autoDelete,
        ?int $ttl = null,
        ?string $dlxExchange = null
    ): void {
        $arguments = [];

        if (null !== $ttl) {
            $arguments['x-message-ttl'] = $ttl * 1000;
        }

        if (null !== $dlxExchange) {
            $arguments['x-dead-letter-exchange'] = $dlxExchange;
        }

        $this->channel?->queue_declare(
            queue: $queue,
            durable: $durable,
            auto_delete: $autoDelete,
            arguments: new AMQPTable($arguments)
        );

        if (null !== $dlxExchange) {
            $this->channel?->queue_bind($queue, $dlxExchange);
        }
    }

    private function sleep(): void
    {
        sleep(30);
    }
}
