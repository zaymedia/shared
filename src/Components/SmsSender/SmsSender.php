<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\SmsSender;

interface SmsSender
{
    public function send(string $number, string $text): void;
}
