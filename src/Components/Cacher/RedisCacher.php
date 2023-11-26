<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\Cacher;

use Redis;

class RedisCacher implements Cacher
{
    private string $host;
    private int $port;
    private string $password;

    private ?Redis $redis = null;

    public function __construct(
        string $host,
        int $port,
        string $password
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->password = $password;
    }

    public function get(string $key): ?string
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        $value = $this->redis?->get($key);

        if (!\is_string($value)) {
            return null;
        }

        return $value;
    }

    public function set(string $key, string $value, ?int $ttl = null): bool
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        return (bool)$this->redis?->set($key, $value, $ttl);
    }

    public function delete(string $key): void
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        $this->redis?->del($key);
    }

    public function expire(string $key, int $ttl): void
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        $this->redis?->expire($key, $ttl);
    }

    public function mGet(array $keys): array
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        $result = $this->redis?->mGet($keys);

        if (!\is_array($result)) {
            return [];
        }

        return $result;
    }

    public function zAdd(string $key, float $score, float|int|string $value): void
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        $this->redis?->zAdd($key, $score, $value);
    }

    public function zRangeByScore(
        string $key,
        int $start,
        int $end,
        ?int $offset = null,
        ?int $count = null
    ): array {
        if (!$this->isConnected()) {
            $this->connect();
        }

        $options = [];

        if (null !== $offset && null !== $count) {
            $options = [
                'limit' => [$offset, $count],
            ];
        }

        $result = $this->redis?->zRangeByScore(
            key: $key,
            start: (string)$start,
            end: (string)$end,
            options: $options
        );

        if (!\is_array($result)) {
            return [];
        }

        return $result;
    }

    public function zRevRangeByScore(
        string $key,
        int $start,
        int $end,
        ?int $offset = null,
        ?int $count = null
    ): array {
        if (!$this->isConnected()) {
            $this->connect();
        }

        $options = [];

        if (null !== $offset && null !== $count) {
            $options = [
                'limit' => [$offset, $count],
            ];
        }

        /** @var array|Redis $result */
        $result = $this->redis?->zRevRangeByScore(
            key: $key,
            start: (string)$start,
            end: (string)$end,
            options: $options
        );

        if (!\is_array($result)) {
            return [];
        }

        return $result;
    }

    public function increase(string $key, int $value): void
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        $this->redis?->incrBy($key, $value);
    }

    public function decrease(string $key, int $value): void
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        $this->redis?->decrBy($key, $value);
    }

    private function connect(): void
    {
        $this->redis = new Redis();
        $this->redis->connect($this->host, $this->port);
        $this->redis->auth($this->password);
    }

    private function isConnected(): bool
    {
        return null !== $this->redis;
    }
}
