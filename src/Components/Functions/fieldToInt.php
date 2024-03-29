<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\Functions;

/** @param string[] $data */
function fieldToInt(?array $data, string $field, int $default = 0): int
{
    $data = fieldToIntOrNull($data, $field);

    if ($data === null) {
        return $default;
    }

    return $data;
}

/** @param string[] $data */
function fieldToIntOrNull(?array $data, string $field): ?int
{
    if (empty($data)) {
        return null;
    }

    if (isset($data[$field])) {
        return toInt($data[$field]);
    }

    return null;
}

function toInt(null|int|string $value): int
{
    return (int)$value;
}
