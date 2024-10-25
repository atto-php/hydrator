<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Extract;

use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Template\ArrayReference;
use Atto\Hydrator\Template\ObjectReference;

final class Primitive
{
    private const SERIALISE = [
        SerializationStrategyType::Json->value => 'json_encode(%s)',
        SerializationStrategyType::CommaDelimited->value => 'implode(\',\', %s)',
        SerializationStrategyType::PipeDelimited->value => 'implode(\'|\', %s)',
    ];
    private const SERIALISE_NULLABLE = [
        SerializationStrategyType::Json->value => 'isset(%1$s) ? json_encode(%1$s) : null',
        SerializationStrategyType::CommaDelimited->value => 'isset(%1$s) ? implode(\',\', %1$s) : null',
        SerializationStrategyType::PipeDelimited->value => 'isset(%1$s) ? implode(\'|\', %1$s) : null',
    ];

    private readonly ArrayReference $arrayReference;
    private readonly ObjectReference $objectReference;

    public function __construct(
        private readonly string $propertyName,
        private readonly ?SerializationStrategyType $serialisationStrategy,
        private readonly bool $nullable,
    ) {
        $this->arrayReference = new ArrayReference($this->propertyName);
        $this->objectReference = new ObjectReference($this->propertyName);
    }

    public function __toString(): string
    {
        if ($this->nullable) {
            return sprintf(
                '%1$s = %2$s ?? null;',
                $this->arrayReference,
                $this->serialisationStrategy === null ?
                    $this->objectReference :
                    sprintf(self::SERIALISE_NULLABLE[$this->serialisationStrategy->value], $this->objectReference),
            );
        } else {
            return sprintf(
                'if (isset(%1$s)) %2$s = %3$s;',
                $this->objectReference,
                $this->arrayReference,
                $this->serialisationStrategy === null ?
                    $this->objectReference :
                    sprintf(self::SERIALISE[$this->serialisationStrategy->value], $this->objectReference),
            );
        }
    }
}
