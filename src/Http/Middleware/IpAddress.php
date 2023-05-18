<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class IpAddress extends \RKA\Middleware\IpAddress
{
    private const ATTRIBUTE = 'ip_address';

    public function __construct(bool $checkProxyHeaders = false, ?array $trustedProxies = null, ?string $attributeName = null, array $headersToInspect = [])
    {
        parent::__construct($checkProxyHeaders, $trustedProxies, $attributeName, $headersToInspect);
    }

    public static function get(ServerRequestInterface $request): ?string
    {
        return (string)$request->getAttribute(self::ATTRIBUTE);
    }
}
