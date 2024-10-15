<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Hydrate;

use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Template\ArrayReference;
use Atto\Hydrator\Template\ObjectReference;

final class Primitive
{
    private const HYDRATE_FORMAT = '%s';

    private const DESERIALISE = [
        SerializationStrategyType::Json->value => 'json_decode(%s, true)',
        SerializationStrategyType::CommaDelimited->value => 'explode(\',\', %s)'
    ];
    private const DESERIALISE_WITH_NULL = [
        SerializationStrategyType::Json->value => 'json_decode(%s ?? \'null\', true)',
        SerializationStrategyType::CommaDelimited->value => 'isset(%1$s) ? explode(\',\', %1$s) : null'
    ];
    private const ASSIGNMENT = '%s = %s;';
    private const ASSIGNMENT_WITH_NULL = '%s = %s ?? null;';
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
        private readonly bool $needsChecks,
    ) {
        $this->arrayReference = new ArrayReference($this->propertyName);
        $this->objectReference = new ObjectReference($this->propertyName);
    }

    public function __toString(): string
    {
        if ($this->serializationStrategy === null) {
            $assignment = sprintf(
                $this->needsChecks ? self::ASSIGNMENT : self::ASSIGNMENT_WITH_NULL,
                $this->objectReference,
                sprintf(self::HYDRATE_FORMAT, $this->arrayReference)
            );
        } else {
            $assignment = sprintf(
                self::ASSIGNMENT,
                $this->objectReference,
                sprintf(
                    $this->needsChecks ?
                        self::DESERIALISE[$this->serializationStrategy->value] :
                        self::DESERIALISE_WITH_NULL[$this->serializationStrategy->value],
                    sprintf(self::HYDRATE_FORMAT, $this->arrayReference),
                    $this->arrayReference
                ));
        }

        return
        $this->needsChecks ?
            sprintf(
                self::CHECKS,
                $this->arrayReference,
                $this->objectReference,
                $this->propertyName,
                $assignment
            ) :
            $assignment;
    }
}
