<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\Serializer;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class Denormalizer
{
    public function __construct(
        private readonly DenormalizerInterface $denormalizer
    ) {}

    /**
     * @template T
     * @param class-string<T> $type
     * @return T
     * @throws ExceptionInterface
     */
    public function denormalizeQuery(mixed $data, string $type): object
    {
        /** @var T */
        return $this->denormalizer->denormalize($data, $type, null, [
            DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS   => true,
            AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES              => true,
            AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT      => true,
        ]);
    }

    /**
     * @template T
     * @param class-string<T> $type
     * @return T
     * @throws ExceptionInterface
     */
    public function denormalize(mixed $data, string $type): object
    {
        /** @var T */
        return $this->denormalizer->denormalize($data, $type, null, [
            DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS   => true,
            AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES              => true,
            AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT      => true,
        ]);
    }

    /**
     * @template T
     * @param class-string<T> $type
     * @return T
     * @throws ExceptionInterface
     */
    public function denormalizeWithExtraAttributes(mixed $data, string $type): object
    {
        /** @var T */
        return $this->denormalizer->denormalize($data, $type, null, [
            DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS   => true,
            AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES              => true,
        ]);
    }
}
