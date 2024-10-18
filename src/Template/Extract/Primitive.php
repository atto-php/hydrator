<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Extract;

use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Template\ObjectReference;

final class Primitive
{
    const SERIALISE = [
        SerializationStrategyType::Json->value => 'json_encode(%s)',
        SerializationStrategyType::CommaDelimited->value => 'implode(\',\', %s)'
    ];
    const ASSIGNMENT = '$values[\'%1$s\'] = %2$s;' . "\n";

    private readonly ObjectReference $valueReference;

    public function __construct(
        private readonly string $propertyName,
        private readonly ?SerializationStrategyType $serialisationStrategy = null
    ) {
        $this->valueReference = new ObjectReference($this->propertyName);
    }

    public function __toString(): string
    {
        if ($this->serialisationStrategy === null) {
            return sprintf(self::ASSIGNMENT, $this->propertyName, $this->valueReference);
        }

        return sprintf(
            self::ASSIGNMENT,
            $this->propertyName,
            sprintf(
                self::SERIALISE[$this->serialisationStrategy->value],
                $this->valueReference,
            )
        );
    }
}
