<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Http\Middleware;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class IpAddress extends \RKA\Middleware\IpAddress
{
    public function __construct(bool $checkProxyHeaders = false, ?array $trustedProxies = null, ?string $attributeName = null, array $headersToInspect = [])
    {
        parent::__construct($checkProxyHeaders, $trustedProxies, $attributeName, $headersToInspect);
    }
}
