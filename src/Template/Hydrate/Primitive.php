<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Hydrate;

use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Template\ArrayReference;
use Atto\Hydrator\Template\ObjectReference;

final class Primitive
{
    private const DESERIALISE = [
        SerializationStrategyType::Json->value => 'json_decode(%s, true)',
        SerializationStrategyType::CommaDelimited->value => 'explode(\',\', %s)',
        SerializationStrategyType::PipeDelimited->value => 'explode(\'|\', %s)',
    ];

    private ArrayReference $arrayReference;
    private ObjectReference $objectReference;

    public function __construct(
        private readonly string $propertyName,
        private readonly ?SerializationStrategyType $serializationStrategy,
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
            $this->arrayReference,
            $this->objectReference,
            $this->serializationStrategy === null ?
                $this->arrayReference :
                sprintf(
                    self::DESERIALISE[$this->serializationStrategy->value],
                    $this->arrayReference
                ),
        );
    }
}
