<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Helpers\OpenApi;

use Attribute;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class SecuritySchemeApiKeyAuth extends OA\SecurityScheme
{
    public function __construct()
    {
        parent::__construct(
            securityScheme: 'apiKeyAuth',
            type: 'apiKey',
            name: 'apiKey',
            in: 'header',
        );
    }
}
