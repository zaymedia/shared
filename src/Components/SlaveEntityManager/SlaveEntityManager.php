<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\SlaveEntityManager;

use Doctrine\ORM\EntityManager;

/** @psalm-suppress InvalidExtendClass */
class SlaveEntityManager extends EntityManager implements SlaveEntityManagerInterface
{
}
