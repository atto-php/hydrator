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
        $format = 'if (isset(%1$s)) {%2$s = %3$s;}';
        if ($this->nullable) {
            $format .= 'else {%2$s = null;}';
        }

        return sprintf(
            $format,
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
