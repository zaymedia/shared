<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Helpers\CursorPagination;

use Doctrine\DBAL\Query\QueryBuilder;
use Exception;

final class CursorPagination
{
    private const SALT = 'zAS0p5NUHYuGhzID7PIT';

    /** @param array<string, string> $orderingBy */
    public static function generateResult(
        QueryBuilder $query,
        ?string $cursor,
        int $count,
        bool $isSortDescending,
        array $orderingBy,
        string $field,
        ?int $offset = null
    ): CursorPaginationResult {
        foreach ($orderingBy as $sort => $order) {
            $query->addOrderBy($sort, $order);
        }

        $totalCount = (null === $cursor) ? self::totalCount(clone $query, $field) : null;

        if (null !== $offset) {
            $query->setFirstResult($offset);
        } else {
            $cursorData = self::decode($cursor);

            if (null !== $cursorData) {
                if (isset($cursorData[$field])) {
                    $id = (int)$cursorData[$field];

                    if ($isSortDescending) {
                        $query->andWhere($field . ' < ' . $id);
                    } else {
                        $query->andWhere($field . ' > ' . $id);
                    }
                }
            }
        }

        try {
            $rows = $query
                ->setMaxResults($count + 1)
                ->executeQuery()
                ->fetchAllAssociative();
        } catch (Exception) {
            $rows = [];
        }

        $items = [];

        foreach ($rows as $row) {
            if (\count($items) >= $count) {
                break;
            }

            $items[] = $row;
        }

        $cursor = (\count($rows) > $count) ? self::getNextCursor($items, $isSortDescending, $field) : null;

        return new CursorPaginationResult(
            count: $totalCount,
            items: $items,
            cursor: $cursor
        );
    }

    public static function generateEmptyResult(): CursorPaginationResult
    {
        return new CursorPaginationResult(0, [], null);
    }

    public static function encodeScore(int $start, int $offset): ?string
    {
        $value = json_encode([
            'start' => $start,
            'offset' => $offset,
        ]);

        try {
            return base64_encode(base64_encode($value) . self::SALT);
        } catch (Exception) {
            return null;
        }
    }

    public static function decodeScore(?string $value): ?CursorScore
    {
        if (null === $value) {
            return null;
        }

        try {
            $value = substr(
                string: base64_decode($value, true),
                offset: 0,
                length: -1 * \strlen(self::SALT)
            );

            $value = base64_decode($value, true);

            /** @var array{start: int|null, offset: int|null} $arr */
            $arr = (array)json_decode($value, true);

            if (!isset($arr['start']) || !isset($arr['offset'])) {
                return null;
            }

            return new CursorScore(
                start: $arr['start'],
                offset: $arr['offset']
            );
        } catch (Exception) {
            return null;
        }
    }

    private static function totalCount(QueryBuilder $query, string $field): ?int
    {
        try {
            $result = $query
                ->select('COUNT(' . $field . ') AS count')
                ->setFirstResult(0)
                ->fetchAssociative();

            return (int)($result['count'] ?? 0);
        } catch (Exception) {
        }

        return null;
    }

    private static function getNextCursor(array $items, bool $isSortDescending, string $field): ?string
    {
        $id = null;

        $fieldAfterDot = self::getAfterDot($field);

        /** @var array|float|int|string $item */
        foreach ($items as $item) {
            if (!isset($item[$fieldAfterDot]) || !is_numeric($item[$fieldAfterDot])) {
                continue;
            }

            $value = (int)$item[$fieldAfterDot];

            if (null === $id) {
                $id = $value;
            }

            if ($isSortDescending) {
                if ($value < $id) {
                    $id = $value;
                }
            } else {
                if ($value > $id) {
                    $id = $value;
                }
            }
        }

        return self::encode($id, $field);
    }

    private static function encode(?int $value, string $field): ?string
    {
        if (null === $value) {
            return null;
        }

        $value = json_encode([
            $field => $value,
        ]);

        try {
            return base64_encode(base64_encode($value) . self::SALT);
        } catch (Exception) {
            return null;
        }
    }

    private static function decode(?string $value): ?array
    {
        if (null === $value) {
            return null;
        }

        try {
            $value = substr(
                string: base64_decode($value, true),
                offset: 0,
                length: -1 * \strlen(self::SALT)
            );

            $value = base64_decode($value, true);

            return (array)json_decode($value, true);
        } catch (Exception) {
            return null;
        }
    }

    private static function getAfterDot(string $string): string
    {
        $parts = explode('.', $string);
        return end($parts);
    }
}
