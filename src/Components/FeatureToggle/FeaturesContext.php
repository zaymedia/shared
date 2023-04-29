<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\FeatureToggle;

interface FeaturesContext
{
    public function getAllEnabled(): array;
}
