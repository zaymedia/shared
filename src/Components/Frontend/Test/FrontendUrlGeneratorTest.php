<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\Frontend\Test;

use PHPUnit\Framework\TestCase;
use ZayMedia\Shared\Components\Frontend\FrontendUrlGenerator;

/**
 * @covers \ZayMedia\Shared\Components\Frontend\FrontendUrlGenerator
 *
 * @internal
 */
final class FrontendUrlGeneratorTest extends TestCase
{
    public function testEmpty(): void
    {
        $generator = new FrontendUrlGenerator('http://test');

        self::assertEquals('http://test', $generator->generate(''));
    }

    public function testPath(): void
    {
        $generator = new FrontendUrlGenerator('http://test');

        self::assertEquals('http://test/path', $generator->generate('path'));
    }

    public function testWithParams(): void
    {
        $generator = new FrontendUrlGenerator('http://test');

        self::assertEquals('http://test/path?a=1&b=2', $generator->generate('path', [
            'a' => '1',
            'b' => 2,
        ]));
    }
}
