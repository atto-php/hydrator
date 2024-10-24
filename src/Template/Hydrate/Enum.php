<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Hydrate;

use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Template\ArrayReference;
use Atto\Hydrator\Template\ObjectReference;

final class Enum
{
    private const HYDRATE_FORMAT = '\%2$s::tryFrom(%1$s)';

    private const DESERIALISE = [
        SerializationStrategyType::Json->value => 'array_map(fn($value) => %s, json_decode(%s, true))',
        SerializationStrategyType::CommaDelimited->value => 'array_map(fn($value) => %s, explode(\',\', %s))'
    ];

    private const ASSIGNMENT = '%s = %s;';
    private const CHECKS = <<<'EOF'
        if (
            isset(%1$s) ||
            isset(%2$s) &&
            array_key_exists('%3$s', $values)
        ) {
            %4$s
        }
        EOF;

    private ArrayReference $arrayReference;
    private ObjectReference $objectReference;

    public function __construct(
        private readonly string|\Stringable $propertyName,
        private readonly ?SerializationStrategyType $serializationStrategy,
        private readonly string $className
    ) {
        $this->arrayReference = new ArrayReference($this->propertyName);
        $this->objectReference = new ObjectReference($this->propertyName);
    }

    public function __toString(): string
    {
        if ($this->serializationStrategy === null) {
            $assignment = sprintf(
                self::ASSIGNMENT,
                $this->objectReference,
                sprintf(self::HYDRATE_FORMAT, $this->arrayReference, $this->className)
            );
        } else {
            $assignment = sprintf(
                self::ASSIGNMENT,
                $this->objectReference,
                sprintf(
                    self::DESERIALISE[$this->serializationStrategy->value],
                    sprintf(self::HYDRATE_FORMAT, '$value', $this->className),
                    $this->arrayReference
                ));
        }

        return
            sprintf(
                self::CHECKS,
                $this->arrayReference,
                $this->objectReference,
                $this->propertyName,
                $assignment
            );
    }
}
