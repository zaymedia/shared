<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Http\Exception;

use Throwable;

final class AccessDeniedException extends \Symfony\Component\Finder\Exception\AccessDeniedException
{
    public function __construct(string $message = 'Access denied', int $code = 403, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
