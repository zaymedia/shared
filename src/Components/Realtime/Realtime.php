<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\Realtime;

interface Realtime
{
    public function publish(string $channel, array $data): void;

    public function generateConnectionToken(string $userId, int $exp): ?string;

    public function generateSubscriptionToken(string $userId, string $channel, int $exp): ?string;
}
