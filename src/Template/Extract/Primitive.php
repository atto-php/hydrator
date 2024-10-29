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
                $this->objectReference :
                sprintf(
                    self::SERIALISE[$this->serialisationStrategy->value],
                    $this->objectReference,
                ),
        );
    }
}
