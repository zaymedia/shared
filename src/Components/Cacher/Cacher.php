<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\Cacher;

interface Cacher
{
    public function get(string $key): ?string;

    public function set(string $key, string $value, ?int $ttl = null): bool;

    public function delete(string $key): void;

    public function expire(string $key, int $ttl): void;

    public function mGet(array $keys): array;

    public function zAdd(string $key, float $score, float|int|string $value): void;

    public function zRangeByScore(
        string $key,
        int $start,
        int $end,
        ?int $offset = null,
        ?int $count = null
    ): array;

    public function zRevRangeByScore(
        string $key,
        int $start,
        int $end,
        ?int $offset = null,
        ?int $count = null
    ): array;

    public function increase(string $key, int $value): void;

    public function decrease(string $key, int $value): void;
}
