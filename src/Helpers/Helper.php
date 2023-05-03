<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Helpers;

class Helper
{
    /**
     * @param array{array} $items
     * @param float[]|int[]|string[] $ids
     */
    public static function sortItemsByIds(array $items, array $ids, string $key = 'id'): array
    {
        $result = [];

        foreach ($ids as $id) {
            foreach ($items as $item) {
                if (!isset($item[$key])) {
                    continue;
                }

                if ($item[$key] === $id) {
                    $result[] = $item;
                    break;
                }
            }
        }

        return $result;
    }

    public static function toArrayInt(array $items): array
    {
        $arr = [];

        foreach ($items as $item) {
            if (!is_numeric($item)) {
                continue;
            }

            $arr[] = (int)$item;
        }

        return $arr;
    }
}
