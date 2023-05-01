<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Helpers\OpenApi;

use Attribute;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER | Attribute::IS_REPEATABLE)]
class ParameterCount extends OA\Parameter
{
    public function __construct()
    {
        parent::__construct(
            name: 'count',
            description: 'Кол-во которое необходимо получить',
            in: 'query',
            required: false,
            schema: new OA\Schema(
                type: 'integer',
                format: 'int64'
            ),
            example: 100
        );
    }
}
