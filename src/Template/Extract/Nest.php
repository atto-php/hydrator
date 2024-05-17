<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Extract;

use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Template\ObjectReference;

final class Nest
{
    const EXTRACT_FORMAT = '$extract[\%2$s::class](%1$s)';
    const SERIALISE = [
        SerializationStrategyType::Json->value => 'json_encode(array_map(fn($value) => %s, %s))',
        SerializationStrategyType::CommaDelimited->value => 'implode(\',\', array_map(fn($value) => %s, %s))'
    ];
    const ASSIGNMENT = '$values[\'%1$s\'] = %2$s;' . "\n";
    private readonly ObjectReference $valueReference;

    public function __construct(
        private readonly string $propertyName,
        private readonly string $className,
        private readonly ?SerializationStrategyType $serialisationStrategy = null
    ) {
        $this->valueReference = new ObjectReference($this->propertyName);
    }

    public function __toString(): string
    {
        if ($this->serialisationStrategy === null) {
            return sprintf(
                self::ASSIGNMENT,
                $this->propertyName,
                sprintf(self::EXTRACT_FORMAT, $this->valueReference, $this->className)
            );
        }

        return sprintf(
            self::ASSIGNMENT,
            $this->propertyName,
            sprintf(
                self::SERIALISE[$this->serialisationStrategy->value],
                sprintf(self::EXTRACT_FORMAT, '$value', $this->className),
                $this->valueReference
            )
        );
    }
}