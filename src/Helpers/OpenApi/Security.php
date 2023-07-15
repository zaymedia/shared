<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Helpers\OpenApi;

final class Security
{
    public const BEARER_AUTH = [
        'bearerAuth' => '{}',
    ];

    public const API_KEY = [
        'apiKeyAuth' => '{}',
    ];
}
