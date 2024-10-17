<?php

declare(strict_types=1);

namespace Atto\Hydrator;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Hydrator\Attribute\HydrationStrategy;
use Atto\Hydrator\Attribute\HydrationStrategyType;
use Atto\Hydrator\Template\Closure;
use Atto\Hydrator\Template\HydratorClass;
use ReflectionClass;

final class Builder
{
    public function build(string $class, string $hydratorNamespace = 'Generated', string $commonNamespace = ''): object
    {
        $class = '\\' . ltrim($class, '\\');
        assert(class_exists($class));

        $refl = new ReflectionClass($class);

        $hydratorClassName = ClassName::fromFullyQualifiedName($class . 'Hydrator')
            ->removeNamespacePrefix($commonNamespace)
            ->addNamespacePrefix($hydratorNamespace);

        $extractCode = new Closure($class);
        $hydrateCode = new Closure($class);
        $hydratorClass = new HydratorClass(
            $hydratorClassName,
            $class
        );

        foreach ($refl->getProperties() as $property) {
            $type = $property->getType();

            if ($type === null) {
                throw new \Exception('must have a type hint');
            }

            if (!($type instanceof \ReflectionNamedType)) {
                throw new \Exception('cannot handle multiple type hints');
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

                if ($hydrationStrategy === HydrationStrategyType::Json) {
                    throw new \Exception(
                        'Collections do no support the Json hydration strategy. ' .
                        'Use the Nest strategy with the Json serialisation strategy instead'
                    );
                }

                if ($hydrationStrategy === HydrationStrategyType::Merge) {
                    throw new \Exception(
                        'Collections do no support the Merge hydration strategy.'
                    );
                }

            } else {
                $hydrationStrategy = $this->getHydrationStrategy($property) ??
                    $this->typeNameToHydrationStrategy($typeName) ??
                    HydrationStrategyType::Primitive
                ;
            }

            $hydrateCode->addPropertyAccessor($this->hydrateFor(
                $typeName,
                $propertyName,
                $hydrationStrategy,
                $serialisationStrategy,
                (! $property->getType()->allowsNull() || $property->hasDefaultValue()),
            ));
            $extractCode->addPropertyAccessor($this->extractFor(
                $typeName,
                $propertyName,
                $hydrationStrategy,
                $serialisationStrategy,
                ! ($property->getType()->allowsNull() || $property->hasDefaultValue()),
            ));

            if ($hydrationStrategy->requiresSubHydrator()) {
                $className = ClassName::fromFullyQualifiedName($typeName);
                $hydratorName = $className->removeNamespacePrefix($commonNamespace)
                    ->addNamespacePrefix($hydratorNamespace);
                $hydratorClass->addSubHydrator($className, $hydratorName);
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

        if (in_array($typeName, ['float', 'int', 'string', 'bool', 'array'])) {
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
        string|\Stringable $propertyName,
        HydrationStrategyType $hydrationStrategy,
        ?SerializationStrategyType $serialisationStrategy,
        bool $needsChecks,
    ): \Stringable {
        return match ($hydrationStrategy) {
            HydrationStrategyType::Primitive => new Template\Hydrate\Primitive($propertyName, $serialisationStrategy, $needsChecks),
            HydrationStrategyType::Enum => new Template\Hydrate\Enum($propertyName, $serialisationStrategy, $typeName),
            HydrationStrategyType::DateTime => new Template\Hydrate\DateTime($propertyName, $serialisationStrategy, $typeName),
            HydrationStrategyType::String => new Template\Hydrate\StringWrapper($propertyName, $serialisationStrategy, $typeName),
            HydrationStrategyType::Nest => new Template\Hydrate\Nest($propertyName,$serialisationStrategy, $typeName),
            HydrationStrategyType::Json => new Template\Hydrate\Json($propertyName, $typeName),
            HydrationStrategyType::Merge => new Template\Hydrate\Merge($propertyName, $typeName),
            HydrationStrategyType::Passthrough => new Template\Hydrate\Passthrough($propertyName)
        };
    }

    private function extractFor(
        string $typeName,
        string|\Stringable $propertyName,
        HydrationStrategyType $hydrationStrategy,
        ?SerializationStrategyType $serialisationStrategy,
        bool $needsChecks,
    ): \Stringable {
        return match ($hydrationStrategy) {
            HydrationStrategyType::Primitive => new Template\Extract\Primitive(
                $propertyName,
                $serialisationStrategy,
                $needsChecks,
            ),
            HydrationStrategyType::Enum => new Template\Extract\Enum($propertyName, $serialisationStrategy),
            HydrationStrategyType::DateTime => new Template\Extract\DateTime($propertyName, $serialisationStrategy),
            HydrationStrategyType::String => new Template\Extract\StringWrapper($propertyName, $serialisationStrategy),
            HydrationStrategyType::Nest => new Template\Extract\Nest($propertyName, $typeName, $serialisationStrategy),
            HydrationStrategyType::Json => new Template\Extract\Json($propertyName, $typeName),
            HydrationStrategyType::Merge => new Template\Extract\Merge($propertyName, $typeName),
            HydrationStrategyType::Passthrough => new Template\Extract\Passthrough($propertyName)
        };
    }
}
