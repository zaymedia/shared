<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\Realtime;

use Exception;
use phpcent\Client;

final class Centrifugo implements Realtime
{
    private string $host;
    private string $apiKey;
    private string $secret;

    private ?Client $client = null;

    public function __construct(string $host, string $apiKey, string $secret)
    {
        $this->host = $host;
        $this->apiKey = $apiKey;
        $this->secret = $secret;
    }

    public function publish(string $channel, array $data): void
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        try {
            $this->client?->publish($channel, $data);
        } catch (Exception) {
            // todo: logger
        }
    }

    public function generateConnectionToken(string $userId, int $exp = 0): ?string
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        try {
            return $this->client?->generateConnectionToken($userId, $exp);
        } catch (Exception) {
            // todo: logger
        }

        return null;
    }

    public function generateSubscriptionToken(string $userId, string $channel, int $exp = 0): ?string
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        try {
            return $this->client?->generateSubscriptionToken($userId, $channel, $exp);
        } catch (Exception) {
            // todo: logger
        }

        return null;
    }

    private function connect(): void
    {
        $this->client = new Client($this->host . '/api');
        $this->client->setApiKey($this->apiKey);
        $this->client->setSecret($this->secret);
        $this->client->setConnectTimeoutOption(5);
        $this->client->setTimeoutOption(1);
        $this->client->forceIpResolveV4();
    }

    private function isConnected(): bool
    {
        return null !== $this->client;
    }
}
