<?php

declare(strict_types=1);

namespace Atto\Hydrator;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Hydrator\Attribute\HydrationStrategy;
use Atto\Hydrator\Attribute\HydrationStrategyType;
use Atto\Hydrator\Exception\AttributeNotApplicable;
use Atto\Hydrator\Exception\StrategyNotApplicable;
use Atto\Hydrator\Exception\TypeHintException;
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
            $propertyName = $property->getName();

            $type = $property->getType() ??
                throw TypeHintException::missing($propertyName);

            if (!($type instanceof \ReflectionNamedType)) {
                throw TypeHintException::unsupported($propertyName);
            }

            $typeName = $type->getName();
            $serialisationStrategy = null;

            if ($typeName === 'array') {
                $serialisationStrategy = $this->getSerialisationStrategy($property);
                $hydrationStrategy = $this->getHydrationStrategy($property) ??
                    $this->typeNameToHydrationStrategy($this->getSubtype($property))
                ;

                if ($hydrationStrategy === HydrationStrategyType::Json) {
                    throw StrategyNotApplicable::collectionCannotUseJsonHydration($propertyName);
                }

                if ($hydrationStrategy === HydrationStrategyType::Merge) {
                    throw StrategyNotApplicable::collectionCannotUseMergeHydration($propertyName);
                }

                if (
                    $hydrationStrategy === HydrationStrategyType::Passthrough
                    && $this->hasSerialisationStrategy($property)
                ) {
                    throw StrategyNotApplicable::passthroughHydrationCannotSerialise($propertyName);
                }

            } else {
                if ($this->getSubtype($property) !== null) {
                    throw AttributeNotApplicable::subtype($typeName, $propertyName);
                }

                if ($this->hasSerialisationStrategy($property)) {
                    throw AttributeNotApplicable::serialisationStrategy($typeName, $propertyName);
                }

                $hydrationStrategy = $this->getHydrationStrategy($property) ??
                    $this->typeNameToHydrationStrategy($typeName)
                ;
            }

            $hydrateCode->addPropertyAccessor($this->hydrateFor(
                $typeName,
                $propertyName,
                $hydrationStrategy,
                $serialisationStrategy,
                $property->getType()->allowsNull(),
            ));
            $extractCode->addPropertyAccessor($this->extractFor(
                $typeName,
                $propertyName,
                $hydrationStrategy,
                $serialisationStrategy,
                $property->getType()->allowsNull(),
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

    private function hasSerialisationStrategy(\ReflectionProperty $property): bool
    {
        $reflectionAttribute = current($property->getAttributes(SerializationStrategy::class)) ?: null;
        return $reflectionAttribute !== null;
    }

    private function getSubtype(\ReflectionProperty $property): ?string
    {
        $reflectionAttribute = current($property->getAttributes(Subtype::class)) ?: null;
        if ($reflectionAttribute === null) {
            return null;
        }

        return $reflectionAttribute->newInstance()->type;
    }

    private function typeNameToHydrationStrategy(string $typeName): HydrationStrategyType
    {
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
        bool $nullable,
    ): \Stringable {
        return match ($hydrationStrategy) {
            HydrationStrategyType::Primitive => new Template\Hydrate\Primitive(
                $propertyName,
                $serialisationStrategy,
                $nullable
            ),
            HydrationStrategyType::Enum => new Template\Hydrate\Enum(
                $propertyName,
                $serialisationStrategy,
                $typeName,
                $nullable,
            ),
            HydrationStrategyType::DateTime => new Template\Hydrate\DateTime(
                $propertyName,
                $serialisationStrategy,
                $typeName,
                $nullable,
            ),
            HydrationStrategyType::String => new Template\Hydrate\StringWrapper(
                $propertyName,
                $serialisationStrategy,
                $typeName,
                $nullable,
            ),
            HydrationStrategyType::Nest => new Template\Hydrate\Nest(
                $propertyName,
                $typeName,
                $nullable,
            ),
            HydrationStrategyType::Json => new Template\Hydrate\Json(
                $propertyName,
                $typeName,
                $nullable,
            ),
            HydrationStrategyType::Merge => new Template\Hydrate\Merge(
                $propertyName,
                $typeName,
                $nullable,
            ),
            HydrationStrategyType::Passthrough => new Template\Hydrate\Passthrough(
                $propertyName,
                $nullable,
            )
        };
    }

    private function extractFor(
        string $typeName,
        string|\Stringable $propertyName,
        HydrationStrategyType $hydrationStrategy,
        ?SerializationStrategyType $serialisationStrategy,
        bool $nullable,
    ): \Stringable {
        return match ($hydrationStrategy) {
            HydrationStrategyType::Primitive => new Template\Extract\Primitive(
                $propertyName,
                $serialisationStrategy,
                $nullable,
            ),
            HydrationStrategyType::Enum => new Template\Extract\Enum(
                $propertyName,
                $serialisationStrategy,
                $nullable,
            ),
            HydrationStrategyType::DateTime => new Template\Extract\DateTime(
                $propertyName,
                $serialisationStrategy,
                $nullable,
            ),
            HydrationStrategyType::String => new Template\Extract\StringWrapper(
                $propertyName,
                $serialisationStrategy,
                $nullable,
            ),
            HydrationStrategyType::Nest => new Template\Extract\Nest(
                $propertyName,
                $typeName,
                $nullable,
            ),
            HydrationStrategyType::Json => new Template\Extract\Json(
                $propertyName,
                $typeName,
                $nullable,
            ),
            HydrationStrategyType::Merge => new Template\Extract\Merge(
                $propertyName,
                $typeName,
                $nullable,
            ),
            HydrationStrategyType::Passthrough => new Template\Extract\Passthrough(
                $propertyName,
                $nullable,
            )
        };
    }
}
