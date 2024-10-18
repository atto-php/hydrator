<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Extract;

use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Template\ObjectReference;

final class Primitive
{
    private const SERIALISE = [
        SerializationStrategyType::Json->value => 'json_encode(%s)',
        SerializationStrategyType::CommaDelimited->value => 'implode(\',\', %s)',
        SerializationStrategyType::PipeDelimited->value => 'implode(\'|\', %s)',
    ];
    private const SERIALISE_GUARDED = [
        SerializationStrategyType::Json->value => 'is_null(%1$s) ? null : json_encode(%1$s)',
        SerializationStrategyType::CommaDelimited->value => 'is_null(%1$s) ? null : implode(\',\', %1$s)',
        SerializationStrategyType::PipeDelimited->value => 'is_null(%1$s) ? null : implode(\'|\', %1$s)',
    ];

    private const ASSIGNMENT = '$values[\'%1$s\'] = %2$s;' . "\n";
    private const ASSIGNMENT_GUARDED = '$values[\'%1$s\'] = %2$s ?? null;' . "\n";

    private readonly ObjectReference $valueReference;

    public function __construct(
        private readonly string $propertyName,
        private readonly ?SerializationStrategyType $serialisationStrategy,
        private readonly bool $needsChecks,
    ) {
        $this->valueReference = new ObjectReference($this->propertyName);
    }

    public function __toString(): string
    {
        return sprintf(
            $this->needsChecks ? self::ASSIGNMENT_GUARDED : self::ASSIGNMENT,
            $this->propertyName,
            $this->serialisationStrategy === null ?
                $this->valueReference :
                sprintf(
                    $this->needsChecks ?
                        self::SERIALISE_GUARDED[$this->serialisationStrategy->value] :
                        self::SERIALISE[$this->serialisationStrategy->value],
                    $this->valueReference
                ),
        );
    }
}
