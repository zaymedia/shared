<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;
use ZayMedia\Shared\Http\Response\JsonErrorResponse;

final class ThrowableHandler implements MiddlewareInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), [
                'code' => $exception->getCode(),
                'exception' => $exception,
                'url' => (string)$request->getUri(),
            ]);

            return new JsonErrorResponse(
                code: (int)$exception->getCode(),
                message: $this->translator->trans($exception->getMessage(), [], 'exceptions')
            );
        }
    }
}
