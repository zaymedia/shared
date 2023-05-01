<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Helpers\OpenApi;

use Attribute;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class ResponseSuccessful extends OA\Response
{
    public function __construct()
    {
        parent::__construct(
            response: 200,
            description: 'Successful operation',
        );
    }
}
