<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components;

use Doctrine\ORM\EntityManagerInterface;

class Flusher
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {}

    public function flush(): void
    {
        $this->em->flush();
    }
}
