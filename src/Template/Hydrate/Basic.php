<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Hydrate;

use Atto\Hydrator\Attribute\SerializationStrategyType;

trait Basic
{
    private const DESERIALISE = [
        SerializationStrategyType::Json->value =>
            'array_map('
            . 'fn($value) => %1$s, '
            . 'json_decode(%2$s, true)'
            . ')',
        SerializationStrategyType::CommaDelimited->value =>
            'array_map('
            . 'fn($value) => %1$s, '
            . '%2$s === \'\' ? [] : explode(\',\', %2$s)'
            . ')',
        SerializationStrategyType::PipeDelimited->value =>
            'array_map('
            . 'fn($value) => %1$s, '
            . '%2$s === \'\' ? [] : explode(\'|\', %2$s)'
            . ')',
    ];

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
                $this->getHydrationFormat((string) $this->arrayReference) :
                sprintf(
                    self::DESERIALISE[$this->serializationStrategy->value],
                    $this->getHydrationFormat('$value'),
                    $this->arrayReference
                ),
        );
    }
}
