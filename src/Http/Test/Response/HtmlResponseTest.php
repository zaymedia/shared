<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Http\Test\Response;

use PHPUnit\Framework\TestCase;
use ZayMedia\Shared\Http\Response\HtmlResponse;

/**
 * @internal
 */
final class HtmlResponseTest extends TestCase
{
    public function testDefault(): void
    {
        $response = new HtmlResponse($html = '<html lang="en"></html>');

        self::assertEquals('text/html', $response->getHeaderLine('Content-Type'));
        self::assertEquals($html, $response->getBody()->getContents());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testWithCode(): void
    {
        $response = new HtmlResponse($html = '<html lang="en"></html>', 201);

        self::assertEquals('text/html', $response->getHeaderLine('Content-Type'));
        self::assertEquals($html, $response->getBody()->getContents());
        self::assertEquals(201, $response->getStatusCode());
    }
}
