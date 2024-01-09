<?php

declare(strict_types=1);

namespace Atto\Hydrator;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Hydrator\Attribute\HydrationStrategy;
use Atto\Hydrator\Attribute\HydrationStrategyType;
use Atto\Hydrator\Template\ArrayReference;
use Atto\Hydrator\Template\Closure;
use Atto\Hydrator\Template\Hydrate;
use Atto\Hydrator\Template\HydratorClass;
use ReflectionClass;

final class Builder
{
    public function build(string $class): object
    {
        assert(class_exists($class));

        $refl = new ReflectionClass($class);

        $extractCode = new Closure($class);
        $hydrateCode = new Closure($class);
        $hydratorClass = new HydratorClass(
            $class . 'Hydrator',
            $class,
        );

        foreach ($refl->getProperties() as $property) {
            $type = $property->getType();

            if (!($type instanceof \ReflectionNamedType)) {
                throw new \Exception('cannot handle multiple type hints');
            }

            if ($type === null) {
                throw new \Exception('must have a type hint');
            }

            $typeName = $type->getName();
            $propertyName = $property->getName();
            $serialisationStrategy = null;

            if ($typeName === 'array') {
                $serialisationStrategy = $this->getSerialisationStrategy($property);
                $hydrationStrategy = $this->getHydrationStrategy($property) ??
                    $this->typeNameToHydrationStrategy($this->getSubtype($property)) ??
                    HydrationStrategyType::Primitive
                ;

                if (
                    //Avoid double encoding the json in a collection
                    ($hydrationStrategy === HydrationStrategyType::Json &&
                    $serialisationStrategy === SerializationStrategyType::Json) ||
                    //Merge cannot be achieved on a collection: it is nonsense
                    $hydrationStrategy === HydrationStrategyType::Merge
                ) {
                    $hydrationStrategy = HydrationStrategyType::Nest;
                }

                $hydrateCode->addPropertyAccessor($this->arrayHydrateCode(
                    $propertyName,
                    $serialisationStrategy,
                    $typeName,
                    $hydrationStrategy
                ));
                $extractCode->addPropertyAccessor($this->arrayExtractCode(
                    $propertyName,
                    $serialisationStrategy,
                    $typeName,
                    $hydrationStrategy
                ));
            } else {
                $hydrationStrategy = $this->getHydrationStrategy($property) ??
                    $this->typeNameToHydrationStrategy($typeName) ??
                    HydrationStrategyType::Primitive
                ;

                $hydrateCode->addPropertyAccessor(new Hydrate(
                    $propertyName,
                    $propertyName,
                    $this->hydrateFor($typeName, new ArrayReference($propertyName), $hydrationStrategy, $propertyName)
                ));
                $extractCode->addPropertyAccessor(new Template\Extract(
                    $propertyName,
                    $propertyName,
                    $this->extractFor($typeName, new Template\ObjectReference($propertyName), $hydrationStrategy, $propertyName)
                ));
            }

            if ($hydrationStrategy->requiresSubHydrator()) {
                $hydratorClass->addSubHydrator('\\' . $typeName);
            }
            $hydratorClass->addProperty($propertyName);
        }

        $hydratorClass->addHydrateMethod($hydrateCode);
        $hydratorClass->addExtractMethod($extractCode);

        return $hydratorClass;
    }

    private function getHydrationStrategy(\ReflectionProperty $property): ?HydrationStrategyType
    {
        $attribute = current($property->getAttributes(HydrationStrategy::class)) ?: null;

        return $attribute?->newInstance()->type;
    }

    private function getSerialisationStrategy(\ReflectionProperty $property): SerializationStrategyType
    {
        $reflectionAttribute = current($property->getAttributes(SerializationStrategy::class)) ?: null;
        if ($reflectionAttribute !== null) {
            return $reflectionAttribute->newInstance()->type;
        }

        return SerializationStrategyType::Json;
    }

    private function getSubtype(\ReflectionProperty $property): ?string
    {
        $reflectionAttribute = current($property->getAttributes(Subtype::class)) ?: null;
        if ($reflectionAttribute === null) {
            return null;
        }

        return $reflectionAttribute->newInstance()->type;
    }

    private function typeNameToHydrationStrategy(?string $typeName): ?HydrationStrategyType
    {
        if ($typeName === null) {
            return null;
        }

        if (in_array($typeName, ['int', 'string', 'bool', 'array'])) {
            return HydrationStrategyType::Primitive;
        }

        $reflectedType = new \ReflectionClass($typeName);

        if ($reflectedType->isSubclassOf(\DateTimeInterface::class)) {
            return HydrationStrategyType::DateTime;
        }

        if ($reflectedType->isEnum()) {
            return HydrationStrategyType::Enum;
        }

        return HydrationStrategyType::Merge;
    }

    private function hydrateFor(
        string $typeName,
        string|\Stringable $propertyReference,
        HydrationStrategyType $hydrationStrategy,
        string $propertyName = '',
    ): string|\Stringable {
        return match ($hydrationStrategy) {
            HydrationStrategyType::Primitive => new Template\Hydrate\Primitive($propertyReference),
            HydrationStrategyType::Enum => new Template\Hydrate\Enum($propertyReference, $typeName),
            HydrationStrategyType::DateTime => new Template\Hydrate\DateTime($propertyReference, $typeName),
            HydrationStrategyType::String => new Template\Hydrate\StringWrapper($propertyReference, $typeName),
            HydrationStrategyType::Nest => new Template\Hydrate\Nest($propertyReference, $typeName, $propertyName),
            HydrationStrategyType::Json => new Template\Hydrate\Json($propertyReference, $typeName)
        };
    }

    private function extractFor(
        string $typeName,
        string|\Stringable $propertyReference,
        HydrationStrategyType $hydrationStrategy,
        string $propertyName = '',
    ): string|\Stringable {
        return match ($hydrationStrategy) {
            HydrationStrategyType::Primitive => new Template\Extract\Primitive($propertyReference),
            HydrationStrategyType::Enum => new Template\Extract\Enum($propertyReference),
            HydrationStrategyType::DateTime => new Template\Extract\DateTime($propertyReference),
            HydrationStrategyType::String => new Template\Extract\StringWrapper($propertyReference),
            HydrationStrategyType::Nest => new Template\Extract\Nest($propertyReference, $typeName, $propertyName),
            HydrationStrategyType::Json => new Template\Extract\Json($propertyReference, $typeName)
        };
    }

    private function deserialiseFor(
        SerializationStrategyType $serialisationStrategy,
        string|\Stringable $arrayReference,
        \Stringable|string $hydrateFor
    ) {
        return match ($serialisationStrategy) {
            SerializationStrategyType::Json => new Template\Deserialisation\Json($arrayReference, $hydrateFor),
            SerializationStrategyType::CommaDelimited => new Template\Deserialisation\CommaDelimited($arrayReference, $hydrateFor),
        };
    }

    private function serialiseFor(
        SerializationStrategyType $serialisationStrategy,
        string|\Stringable $arrayReference,
        \Stringable|string $extractFor
    ) {
        return match ($serialisationStrategy) {
            SerializationStrategyType::Json => new Template\Serialisation\Json($arrayReference, $extractFor),
            SerializationStrategyType::CommaDelimited => new Template\Serialisation\CommaDelimited($arrayReference, $extractFor),
        };
    }

    private function arrayHydrateCode(
        string $propertyName,
        SerializationStrategyType $serialisationStrategy,
        string $typeName,
        HydrationStrategyType $hydrationStrategy
    ): Hydrate {
        return new Hydrate(
            $propertyName,
            $propertyName,
            $this->deserialiseFor(
                $serialisationStrategy,
                new ArrayReference($propertyName),
                $this->hydrateFor($typeName, '$value', $hydrationStrategy)
            )
        );
    }

    private function arrayExtractCode(
        string $propertyName,
        SerializationStrategyType $serialisationStrategy,
        string $typeName,
        HydrationStrategyType $hydrationStrategy
    ): Template\Extract {
        return new Template\Extract(
            $propertyName,
            $propertyName,
            $this->serialiseFor(
                $serialisationStrategy,
                new Template\ObjectReference($propertyName),
                $this->extractFor($typeName, '$value', $hydrationStrategy)
            )
        );
    }
}