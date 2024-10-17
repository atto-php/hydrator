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
        private readonly ?SerializationStrategyType $serialisationStrategy = null,
        private readonly bool $needsGuard,
    ) {
        $this->valueReference = new ObjectReference($this->propertyName);
    }

    public function __toString(): string
    {
        return sprintf(
            $this->needsGuard ? $this->guard(self::ASSIGNMENT) : self::ASSIGNMENT,
            $this->propertyName,
            $this->serialisationStrategy === null ?
                $this->valueReference :
                sprintf(
                    self::SERIALISE[$this->serialisationStrategy->value],
                    $this->valueReference
                ),
        );
    }

    private function guard(string $statement): string
    {
        return <<<PHP
            if (isset($this->valueReference) || $this->valueReference === null) {
                $statement
            }
            PHP;
    }
}
