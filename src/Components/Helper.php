<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components;

class Helper
{
    /** @param float[]|int[]|string[] $items */
    public static function toArrayInt(array $items): array
    {
        $arr = [];

        foreach ($items as $item) {
            $arr[] = (int)$item;
        }

        return $arr;
    }
}
