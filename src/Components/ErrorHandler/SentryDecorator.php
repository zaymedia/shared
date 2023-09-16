<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Throwable;
use ZayMedia\Shared\Components\Sentry;

final class SentryDecorator implements ErrorHandlerInterface
{
    public function __construct(
        private readonly ErrorHandlerInterface $next,
        private readonly Sentry $sentry
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        $this->sentry->capture($exception);

        return ($this->next)(
            $request,
            $exception,
            $displayErrorDetails,
            $logErrors,
            $logErrorDetails
        );
    }
}
