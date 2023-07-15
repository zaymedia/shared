<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ZayMedia\Shared\Http\Exception\UnauthorizedHttpException;

final class AuthenticateByKey implements MiddlewareInterface
{
    private const ATTRIBUTE = 'apiKey';
    private string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public static function findApiKey(ServerRequestInterface $request): ?string
    {
        /** @var string|null $value */
        $value = $request->getAttribute(self::ATTRIBUTE);

        if (\is_string($value)) {
            return $value;
        }

        return null;
    }

    public static function getApiKey(ServerRequestInterface $request): string
    {
        $apiKey = self::findapiKey($request);

        if ($apiKey === null) {
            throw new UnauthorizedHttpException($request);
        }

        return $apiKey;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$request->hasHeader('apiKey')) {
            return $handler->handle($request);
        }

        $apiKey = $request->getHeaderLine('apiKey');

        if (!$this->validateApiKey($apiKey)) {
            throw new UnauthorizedHttpException($request);
        }

        return $handler->handle($request->withAttribute(self::ATTRIBUTE, $apiKey));
    }

    private function validateApiKey(string $key): bool
    {
        return $key === $this->key;
    }
}
