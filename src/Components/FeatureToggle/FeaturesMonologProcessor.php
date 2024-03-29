<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\FeatureToggle;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

final class FeaturesMonologProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly FeaturesContext $context
    ) {}

    public function __invoke(LogRecord $record): LogRecord
    {
        $record->extra['features'] = $this->context->getAllEnabled();

        return $record;
        //        return array_merge_recursive($record, [
        //            'extra' => [
        //                'features' => $this->context->getAllEnabled(),
        //            ],
        //        ]);
    }
}
