<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\ReplicaEntityManager;

use Doctrine\ORM\EntityManager;

/** @psalm-suppress InvalidExtendClass */
class ReplicaEntityManager extends EntityManager implements ReplicaEntityManagerInterface
{
}
