<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\Serializer\Test;

use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use ZayMedia\Shared\Components\Serializer\Normalizer;

/**
 * @covers \ZayMedia\Shared\Components\Serializer\Normalizer
 *
 * @internal
 */
final class NormalizerTest extends TestCase
{
    public function testValid(): void
    {
        $object = new stdClass();

        $origin = $this->createMock(NormalizerInterface::class);
        $origin->expects(self::once())->method('normalize')
            ->with($object)
            ->willReturn(['name' => 'John']);

        $normalizer = new Normalizer($origin);

        $result = $normalizer->normalize($object);

        self::assertSame(['name' => 'John'], $result);
    }
}
