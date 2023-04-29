<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\FeatureToggle;

interface FeatureFlag
{
    public function isEnabled(string $name): bool;
}
