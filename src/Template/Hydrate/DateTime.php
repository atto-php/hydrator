<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Hydrate;

use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Template\ArrayReference;
use Atto\Hydrator\Template\ObjectReference;

final class DateTime
{
    private readonly string $className;

    private const HYDRATE_FORMAT = 'new \%2$s(%1$s)';

    private const DESERIALISE = [
        SerializationStrategyType::Json->value => 'array_map(fn($value) => %s, json_decode(%s, true))',
        SerializationStrategyType::CommaDelimited->value => 'array_map(fn($value) => %s, explode(\',\', %s))',
        SerializationStrategyType::PipeDelimited->value => 'array_map(fn($value) => %s, explode(\'|\', %s))',
    ];

    private ArrayReference $arrayReference;
    private ObjectReference $objectReference;

    public function __construct(
        private readonly string $propertyName,
        private readonly ?SerializationStrategyType $serializationStrategy,
        string $className,
        private readonly bool $nullable,
    ) {
        if ($className === \DateTimeInterface::class) {
            $className = \DateTime::class;
        }

        $this->arrayReference = new ArrayReference($this->propertyName);
        $this->objectReference = new ObjectReference($this->propertyName);
        $this->className = $className;
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
                sprintf(self::HYDRATE_FORMAT, $this->arrayReference, $this->className) :
                sprintf(
                    self::DESERIALISE[$this->serializationStrategy->value],
                    sprintf(self::HYDRATE_FORMAT, '$value', $this->className),
                    $this->arrayReference
                ),
        );
    }
}
