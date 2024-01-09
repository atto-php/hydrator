<?php

declare(strict_types=1);

namespace Atto\Hydrator\Template\Serialisation;

final class CommaDelimited
{
    public function __construct(
        private readonly string|\Stringable $valueDecoder,
        private readonly string $arrayReference
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            'implode(\',\', array_map(fn($value) => %s, %s))',
            $this->valueDecoder,
            $this->arrayReference
        );
    }
}