<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Helpers\CursorPagination;

final class CursorPaginationResult
{
    public function __construct(
        public readonly ?int $count,
        public readonly array $items,
        public readonly ?string $cursor,
    ) {
    }
}
