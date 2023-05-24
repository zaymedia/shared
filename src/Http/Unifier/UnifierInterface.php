<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Http\Unifier;

interface UnifierInterface
{
    public function unifyOne(?int $userId, array $item): array;

    public function unify(?int $userId, array $items): array;
}
