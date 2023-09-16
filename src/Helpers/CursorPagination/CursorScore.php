<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Helpers\CursorPagination;

final class CursorScore
{
    public function __construct(
        public readonly int $start,
        public readonly int $offset,
    ) {}
}
