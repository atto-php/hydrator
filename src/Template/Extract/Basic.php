<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Extract;

use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Template\ArrayReference;
use Atto\Hydrator\Template\ObjectReference;

trait Basic
{
    const SERIALISE = [
        SerializationStrategyType::Json->value => 'json_encode(array_map(fn($value) => %s, %s))',
        SerializationStrategyType::CommaDelimited->value => 'implode(\',\', array_map(fn($value) => %s, %s))',
        SerializationStrategyType::PipeDelimited->value => 'implode(\'|\', array_map(fn($value) => %s, %s))',
    ];
    const SERIALISE_NULLABLE = [
        SerializationStrategyType::Json->value => 'isset(%2$s) ? json_encode(array_map(fn($value) => %1$s, %2$s)) : null',
        SerializationStrategyType::CommaDelimited->value => 'isset(%2$s) ? implode(\',\', array_map(fn($value) => %s, %s)) : null',
        SerializationStrategyType::PipeDelimited->value => 'isset(%2$s) ? implode(\'|\', array_map(fn($value) => %s, %s)) : null',
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
                '%2$s = isset(%1$s) ? %3$s : null;' . "\n",
                $this->objectReference,
                $this->arrayReference,
                $this->serialisationStrategy === null ?
                    sprintf(self::EXTRACT_FORMAT, $this->objectReference) :
                    sprintf(
                        self::SERIALISE_NULLABLE[$this->serialisationStrategy->value],
                        sprintf(self::EXTRACT_FORMAT, '$value'),
                        $this->objectReference
                    )
            );
        } else {
            return sprintf(
                'if (isset(%1$s)) %2$s = %3$s;' . "\n",
                $this->objectReference,
                $this->arrayReference,
                $this->serialisationStrategy === null ?
                    sprintf(self::EXTRACT_FORMAT, $this->objectReference) :
                    sprintf(
                        self::SERIALISE[$this->serialisationStrategy->value],
                        sprintf(self::EXTRACT_FORMAT, '$value'),
                        $this->objectReference
                    )
            );
        }
    }
}
