<?php

declare(strict_types=1);

namespace App\Serializer;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class Serializer implements SerializerInterface
{
    private ?SerializerInterface $serializer = null;

    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    public function getSerializer(): SerializerInterface
    {
        if (null !== $this->serializer) {
            return $this->serializer;
        }
        $this->serializer = $this->container->get('serializer');

        return $this->serializer;
    }

    public function serialize($data, string $format, array $context = []): string
    {
        try {
            return $this->getSerializer()->serialize($data, $format, $context);
        } catch (\TypeError $exception) {
            throw new PartialDenormalizationException(
                $data,
                [
                    'not_normalizable_value_exceptions' => $this->getNormalizableExceptionFromTypeError($data, $exception),
                ]
            );
        }
    }

    public function deserialize($data, string $type, string $format, array $context = []): mixed
    {
        try {
            $context[DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS] = $context[DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS] ?? true;
            $context[DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS] = $context[DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS] ?? true;

            return $this->getSerializer()->deserialize($data, $type, $format, $context);
        } catch (\TypeError $exception) {
            throw new PartialDenormalizationException(
                $data,
                [
                    'not_normalizable_value_exceptions' => $this->getNormalizableExceptionFromTypeError($data, $exception),
                ]
            );
        }
    }

    // This work of insanity is resolution to a problem of Symfony Serializer creating object by constructor despite having type mismatch
    private function getNormalizableExceptionFromTypeError(mixed $data, \TypeError $exception): NotNormalizableValueException
    {
        // Argument #{n}} (${name}}) must be of type {type}, {type}} given
        \preg_match('/\(\$(.+)\) must be of type ([a-zA-Z\?\\\-]+), ([a-zA-Z\?\\\-]+) given/', $exception->getMessage(), $matches);

        $path = $matches[1] ?? null;
        $expectedType = $matches[2] ?? 'unknown';
        $givenType = $matches[3] ?? 'unknown';

        $expectedTypes = [];

        if ('?' === $expectedType[0]) {
            $expectedTypes[] = 'null';
        }
        $expected = \ltrim($expectedType, '?');

        if (\class_exists($expected)) {
            $className = \explode('\\', $expected);
            $expected = \end($className);
        }
        $expectedTypes[] = $expected;

        return NotNormalizableValueException::createForUnexpectedDataType(
            \sprintf('Invalid property for %s must be of type %s, %s given', $path, \implode(' or ', $expectedTypes), $givenType),
            $data,
            $expectedTypes,
            $path,
            true,
            422,
            $exception
        );
    }
}
