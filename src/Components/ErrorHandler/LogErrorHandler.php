<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\ErrorHandler;

use Slim\Handlers\ErrorHandler;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class LogErrorHandler extends ErrorHandler
{
    protected function writeToErrorLog(): void
    {
        $this->logger->error($this->exception->getMessage(), [
            'exception' => $this->exception,
            'url' => (string)$this->request->getUri(),
        ]);
    }
}
