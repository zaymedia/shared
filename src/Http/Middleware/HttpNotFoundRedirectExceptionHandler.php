<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Response;

final class HttpNotFoundRedirectExceptionHandler implements MiddlewareInterface
{
    public function __construct(
        private readonly string $location,
        private readonly int $code = 302
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (HttpNotFoundException) {
            return (new Response())
                ->withStatus($this->code)
                ->withHeader('Location', $this->location);
        }
    }
}
