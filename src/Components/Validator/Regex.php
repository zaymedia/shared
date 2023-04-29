<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\Validator;

final class Regex
{
    public const FIRST_NAME = '/^[а-яёА-ЯЁa-zA-Z]+$/iu';
    public const LAST_NAME  = '/^[а-яёА-ЯЁa-zA-Z]+$/iu';

    public static function firstName(): string
    {
        return self::FIRST_NAME;
    }

    public static function lastName(): string
    {
        return self::LAST_NAME;
    }
}
