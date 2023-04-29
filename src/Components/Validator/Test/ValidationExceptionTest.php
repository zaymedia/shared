<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\Validator\Test;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use ZayMedia\Shared\Components\Validator\ValidationException;

/**
 * @covers \ZayMedia\Shared\Components\Validator\ValidationException
 *
 * @internal
 */
final class ValidationExceptionTest extends TestCase
{
    public function testValid(): void
    {
        $exception = new ValidationException(
            $violations = new ConstraintViolationList()
        );

        self::assertEquals('Invalid input.', $exception->getMessage());
        self::assertEquals($violations, $exception->getViolations());
    }
}
